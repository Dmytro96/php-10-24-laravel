<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\Contracts\ProductsRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ProductsRepositoryContract $productsRepository)
    {
        $per_page = $request->get('per_page', $productsRepository::PER_PAGE);
        $selectedCategory = $request->get('category');
        
        $products = Cache::flexible(
            "products_index_{$per_page}_{$selectedCategory}",
            [5, 600],
            fn () => $productsRepository->paginate($request),
        );
        $categories = Cache::flexible(
            'products_categories',
            [5, 3600],
            fn () => Category::whereHas('products')->get(),
        );
        
        return view(
            'products.index',
            compact('products', 'per_page', 'categories', 'selectedCategory'),
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['categories', 'images']);
        
        $gallery = [
            $product->thumbnailUrl,
            ...$product->images->map(fn ($image) => $image->url),
        ];
        
        return view('products.show', compact('product', 'gallery'));
    }
}
