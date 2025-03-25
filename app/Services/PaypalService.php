<?php

namespace App\Services;

use App\Enums\TransactionStatusesEnum;
use App\Services\Contracts\PaypalServiceContract;
use Gloudemans\Shoppingcart\Facades\Cart;
use Srmklive\PayPal\Services\PayPal;

class PaypalService implements PaypalServiceContract
{
    protected Paypal $paypal;
    
    public function __construct()
    {
        $this->paypal = app(PayPal::class);
        $this->paypal->setApiCredentials(config('paypal'));
        $this->paypal->setAccessToken($this->paypal->getAccessToken());
    }
    
    public function create(): ?string
    {
        $paypalOrder = $this->paypal->createOrder(
            $this->buildOrderRequestData()
        );
        
        logs()->info('[PaypalService::create]', [
            'paypalOrder' => $paypalOrder,
        ]);
        
        return $paypalOrder['id'] ?? null;
    }
    
    public function capture(string $vendorOrderId): TransactionStatusesEnum
    {
        $result = $this->paypal->capturePaymentOrder($vendorOrderId);
        
        return match ($result['status']) {
            'COMPLETED', 'APPROVED' => TransactionStatusesEnum::Success,
            'CREATED', 'SAVED' => TransactionStatusesEnum::Pending,
            default => TransactionStatusesEnum::Cancelled,
        };
    }
    
    protected function buildOrderRequestData(): array
    {
        $cart = Cart::instance('cart');
        $currencyCode = config('paypal.currency');
        $items = [];
        $totalTax = 0;
        $itemTotal = 0;
        
        $cart->content()
            ->each(function ($item) use (&$items, $currencyCode, &$totalTax, &$itemTotal) {
                $itemTax = round($item->price * $item->taxRate / 100, 2);
                $totalTax += $itemTax * $item->qty;
                $itemTotal += $item->price * $item->qty;
                
                $items[] = [
                    'name' => $item->name,
                    'quantity' => $item->qty,
                    'sku' => $item->model->sku,
                    'url' => url(route('products.show', $item->model)),
                    'category' => 'PHYSICAL_GOODS',
                    'unit_amount' => [
                        'currency_code' => $currencyCode,
                        'value' => $item->price,
                    ],
                    'tax' => [
                        'currency_code' => $currencyCode,
                        'value' => number_format($itemTax, 2),
                    ],
                ];
            });
        
        return [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $currencyCode,
                        'value' => number_format($itemTotal + $totalTax, 2), // Розраховуємо amount.value вручну
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => $currencyCode,
                                'value' => number_format($itemTotal, 2),
                            ],
                            'tax_total' => [
                                'currency_code' => $currencyCode,
                                'value' => number_format($totalTax, 2),
                            ],
                        ],
                    ],
                    'items' => $items,
                ],
            ],
        ];
    }}
