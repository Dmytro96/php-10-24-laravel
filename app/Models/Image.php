<?php

namespace App\Models;

use App\Observers\ImageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 *
 *
 * @property int $id
 * @property string $path
 * @property string $imageble_type
 * @property int $imageble_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read Model|\Eloquent $imageble
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereImagebleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereImagebleType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[ObservedBy(ImageObserver::class)]
class Image extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function imageble(): MorphTo
    {
        return $this->morphTo();
    }
    
    public function url(): Attribute
    {
        return Attribute::get(function () {
            return Storage::url($this->attributes['path']);
        });
    }
    
    public function setPathAttribute(array $pathData): void
    {
        /**
         *  @var \Illuminate\Http\UploadedFile $file
         */
        $file = $pathData['image'];
        $fileName = Str::slug(microtime());
        $filePath = $pathData['path'] . "/$fileName" . $file->getClientOriginalName();
        
        ds($filePath)->label('File Path');
        
        Storage::put($filePath, File::get($file));
        Storage::setVisibility($filePath, 'public');
        
        $this->attributes['path'] = $filePath;
    }
}
