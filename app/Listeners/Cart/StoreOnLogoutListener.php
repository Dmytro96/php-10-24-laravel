<?php

namespace App\Listeners\Cart;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreOnLogoutListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(Logout $event): void
    {
        if (Cart::instance('cart')->count() > 0) {
            Cart::instance('cart')->store('cart_' . $event->user->id);
        }
    }
}
