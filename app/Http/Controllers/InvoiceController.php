<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Contracts\InvoiceServiceContract;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $vendorOrderId, InvoiceServiceContract $invoiceService)
    {
        $order = Order::where('vendor_order_id', $vendorOrderId)->firstOrFail();
        if (auth()->user()->cannot('view', $order)) {
            notify()->warning('You are not allowed to view this invoice');
            return redirect()->route('home');
        }

        return $invoiceService->generate($order)->stream();
    }
}
