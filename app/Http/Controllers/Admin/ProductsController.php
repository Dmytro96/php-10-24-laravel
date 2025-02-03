<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Permissions\ProductEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\CreateRequest;
use App\Http\Requests\Admin\Products\EditRequest;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\Contracts\ProductsRepositoryContract;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('categories')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin/products/index', compact('products'));
    }
    
    public function create()
    {
        $categories = Category::select(['id', 'name'])->get();

        return view('admin/products/create', compact('categories'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request, ProductsRepositoryContract $productsRepository)
    {
        if ($product = $productsRepository->store($request)) {
            notify()->success("Product '$product->name' created successfully");
            return redirect()->route('admin.products.index');
        }
        
        notify()->error('Product not created');
        return redirect()->back()->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::select(['id', 'name'])->get();
        $productCategories = $product->categories->pluck('id')->toArray();
        $product->load(['categories', 'images']);
        
        return view('admin/products/edit', compact('product', 'categories', 'productCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditRequest $request, Product $product, ProductsRepositoryContract $productsRepository)
    {
        if ($productsRepository->update($request, $product)) {
            notify()->success("Product '$product->title' updated successfully");
            return redirect()->route('admin.products.edit', $product);
        }
        
        notify()->error('Product not updated');
        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $this->middleware("permission:" . ProductEnum::DELETE->value);
            $product->deleteOrFail();
            
            notify()->success("Product '{$product->title}' deleted successfully");
        } catch (\Exception $e) {
            logs()->error("[ProductsController@destroy] {$e->getMessage()}", [
                'product' => $product->toArray(),
                'exception' => $e,
            ]);
            notify()->error("Failed to delete product '{$product->title}'");
        }
        
        return redirect()->back()->withInput();
    }
}
