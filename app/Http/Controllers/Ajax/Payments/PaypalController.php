<?php

namespace App\Http\Controllers\Ajax\Payments;

use App\Enums\PaymentSystemEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Repositories\OrderRepository;
use App\Repositories\PaypalService;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;

class PaypalController extends Controller
{
    public function __construct(
        protected PaypalService $paypalService,
        protected OrderRepository $orderRepository,
    ) {
    }
    
    public function create(CreateOrderRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();
            
            $paypalOrderId = $this->paypalService->create();
            
            if (!$paypalOrderId) {
                throw new \Exception('Paypal order not created. Payment failed');
            }
            
            $data['vendor_order_id'] = $paypalOrderId;
            $order = $this->orderRepository->create($data);
            
            DB::commit();
            
            return response()->json($order);

        } catch (\Throwable $exception) {
            DB::rollBack();
            logs()->error('[PaypalController::create]' . $exception->getMessage(), [
                'exception' => $exception,
                'data' => $data,
            ]);
            
            return response()->json([
                'error' => $exception->getMessage(),
            ], 422);
        }
    }
    
    public function capture(string $vendorOrderId)
    {
        try {
            DB::beginTransaction();
            
            $paymentStatus = $this->paypalService->capture();
            
            $order = $this->orderRepository->setTransaction(
                $vendorOrderId,
                PaymentSystemEnum::PayPal,
                $paymentStatus,
            );
            
            Cart::instance('cart')->destroy();
            
            DB::commit();
            
            return response()->json($order);
            
        } catch (\Throwable $exception) {
            DB::rollBack();
            logs()->error('[PaypalController::capture]' . $exception->getMessage(), [
                'exception' => $exception,
                'vendor_order_id' => $vendorOrderId,
            ]);
            
            return response()->json([
                'error' => $exception->getMessage(),
            ], 422);
        }
    }
}
