<?php

namespace App\Repositories;

use App\Repositories\Contracts\ImagesRepositoryContract;
use Exception;
use Illuminate\Database\Eloquent\Model;

class ImagesRepository implements ImagesRepositoryContract
{
    
    public function attach(Model $model, string $relation, array $images = [], $path = null): void
    {
        if (!method_exists($model, $relation)) {
            throw new Exception("The relation ($relation) does not exist on the model($model).");
        }
        
        if (!empty($images)) {
            foreach ($images as $image) {
                call_user_func([$model, $relation])->create([
                    'path' => compact('path', 'image'),
                ]);
            }
        }
    }
}
