<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ThankYouController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $vendorOrderId)
    {
        try {
            $order = Order::with(['transaction', 'products'])
                ->where('vendor_order_id', $vendorOrderId)->firstOrFail();
            
            $showInvoiceButton = !!$order->user_id;
            
            return view('orders.thank-you', compact('order', 'showInvoiceButton'));
        } catch (\Throwable $throwable) {
            logs()->error('[ThankYouController::__invoke]' . $throwable->getMessage(), [
                'exception' => $throwable,
                'vendorOrderId' => $vendorOrderId,
            ]);
            return redirect()->route('/');
        }
    }
}
