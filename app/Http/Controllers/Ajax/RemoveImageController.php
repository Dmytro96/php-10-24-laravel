<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Image;

class RemoveImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Image $image)
    {
        try {
            $image->deleteOrFail();

            return response()->json(['message' => 'Image removed']);
        } catch (Throwable $th) {
            logs()->error('[RemoveImageController] ' . $th->getMessage(), [
                'image'   => $image,
                'exception' => $th,
            ]);
            
            return response()->json(['message' => 'Message didn\'t removed'], 422);
        }
    }
}
