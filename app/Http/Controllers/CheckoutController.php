<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __invoke()
    {
        $cart = Cart::instance('cart');
        $user = auth()->user();

        return view('checkout.index', compact('cart', 'user'));
    }
}
