<?php

namespace App\Observers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        Storage::delete($product->getAttribute('thumbnail'));
        $product->images->each(function (Image $image) {
            $image->delete();
        });
    }
}
