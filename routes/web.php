<?php

use App\Http\Controllers\Ajax\Payments\PaypalController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Pages\ThankYouController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

Auth::routes();

Route::resource('products', ProductsController::class)->only(['index', 'show']);
Route::resource('categories', CategoriesController::class)->only(['index', 'show']);

Route::get('/orders/{vendor_order_id}/thank-you', ThankYouController::class)->name('order.thank-you');
Route::get('checkout', CheckoutController::class)->name('checkout');

Route::name('cart.')->prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::delete('/', [CartController::class, 'remove'])->name('remove');
    Route::post('{product}', [CartController::class, 'add'])->name('add');
    Route::put('{product}', [CartController::class, 'update'])->name('update');
});

Route::prefix('admin')->name('admin.')
    ->middleware(['auth', 'role:admin|moderator'])
    ->group(function () {
        Route::get('/', App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');
        Route::resource('categories', App\Http\Controllers\Admin\CategoriesController::class)
            ->except(['show']);
        Route::resource('products', App\Http\Controllers\Admin\ProductsController::class)
            ->except(['show']);
    });


Route::prefix('ajax')->name('ajax.')->group(function () {
    Route::middleware(['auth', 'role:admin|moderator'])->group(function () {
        Route::delete('images/{image}', App\Http\Controllers\Ajax\RemoveImageController::class)
            ->name('images.remove');
    });
    
    Route::post('{product}', App\Http\Controllers\Ajax\AddToCartController::class)->name('cart.add');
    
    Route::prefix('paypal')->name('paypal.')->group(function () {
        Route::post('order', [PaypalController::class, 'create'])->name('order.create');
        Route::post('order/{vendorOrderId}/capture', [PaypalController::class, 'capture'])->name('order.capture');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('orders/{order}/invoice', InvoiceController::class)->name('order.invoice');
});
