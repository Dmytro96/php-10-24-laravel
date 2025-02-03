<?php

namespace App\Observers;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageObserver
{
    public function deleted(Image $image): void
    {
        Storage::delete($image->getAttribute('path'));
        
        $dir = dirname($image->getAttribute('path'));
        if (empty(Storage::files($dir))) {
            Storage::deleteDirectory($dir);
        }
    }
}
