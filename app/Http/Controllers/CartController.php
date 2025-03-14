<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::instance('cart');
        
        return view('cart.index', compact('cart'));
    }
    
    public function add(Request $request, Product $product)
    {
        $attributes = $product->attributesToArray();
        Cart::instance('cart')->add(
            $attributes['id'],
            $attributes['title'],
            1,
            $attributes['price'],
        )->associate(Product::class);
        notify()->success('Product added to cart');
        
        return back();
    }
    
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'rowId' => ['required', 'string'],
            'qty' => ['required', 'numeric', 'min:1'],
        ]);
        
        if ($data['qty'] > $product->quantity) {
            notify()->warning("Maximum available quantity is {$product->quantity}");
            return back();
        }
    
        Cart::instance('cart')->update($data['rowId'], $data['qty']);
        notify()->success('Cart updated');
        return back();
    }
    
    public function remove(Request $request)
    {
        $data = $request->validate([
            'rowId' => ['required', 'string'],
        ]);
        
        Cart::instance('cart')->remove($data['rowId']);
        
        notify()->warning('Product removed');
        
        return redirect()->route('cart.index');
    }
}
