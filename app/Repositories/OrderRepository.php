<?php

namespace App\Repositories;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentSystemEnum;
use App\Enums\TransactionStatusesEnum;
use App\Models\Order;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderRepository implements Contracts\OrderRepositoryContract
{
    public function create(array $data): Order|false
    {
        $data = [
            ...$data,
            'total' => Cart::instance('cart')->total(),
            'status' => OrderStatusEnum::InProcess,
        ];
        
        $order = auth()->check()
            ? auth()->user()->orders()->create($data)
            : Order::create($data);
        
        try {
            $this->addProductsToOrder($order);
        } catch (\Exception $e) {
            return false;
        }
        
        return $order;
    }
    
    public function setTransaction(
        string $vendorOrderId, PaymentSystemEnum $paymentSystem, TransactionStatusesEnum $status,
    ): void {
        
        $order = Order::where('vendor_order_id', $vendorOrderId)->firstOrFail();
        
        $order->transaction()->updateOrCreate([
            'payment_system' => $paymentSystem,
            'status' => $status,
        ]);
        
        $order->updateOrFail([
            'status' => match ($status) {
                TransactionStatusesEnum::Success => OrderStatusEnum::Paid,
                TransactionStatusesEnum::Cancelled => OrderStatusEnum::Cancelled,
                default => OrderStatusEnum::InProcess,
            },
        ]);
    }
    
    protected function addProductsToOrder(Order $order): void
    {
        Cart::instance('cart')->content()
            ->each(function ($item) use ($order) {
                $product = $item->model;
                
                $order->products()->attach($product, [
                    'single_price' => $product->price,
                    'quantity'     => $item->qty,
                    'name'         => $product->title,
                ]);
                
                $quantity = $product->quantity - $item->qty;
                
                if ($quantity < 0 || !$product->update(['quantity' => $quantity])) {
                    throw new \Exception("Product {$product->name} is out of stock");
                }
            });
    }
}
