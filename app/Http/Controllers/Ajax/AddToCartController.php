<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Request;
use PhpParser\Error;

class AddToCartController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Product $product)
    {
        try {
            $attributes = $product->attributesToArray();
            Cart::instance('cart')->add(
                $attributes['id'],
                $attributes['title'],
                1,
                $attributes['price'],
            )->associate(Product::class);

            return response()->json([
                'message' => 'Product added to cart',
                'cart_count' => Cart::instance('cart')->countItems(),
            ]);
        } catch (Throwable $th) {
            logs()->error('[AddToCartController] ' . $th->getMessage(), [
                'product'   => $product['id'],
                'exception' => $th,
            ]);
            
            return response()->json(['message' => 'Oops! Something went wrong'], 422);
        }
    }
}
