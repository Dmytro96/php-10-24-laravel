<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 *
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $SKU
 * @property string|null $description
 * @property float $price
 * @property int|null $discount
 * @property int $quantity
 * @property string $thumbnail
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Image> $images
 * @property-read int|null $images_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSKU($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[ObservedBy(ProductObserver::class)]
class Product extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageble');
    }
    
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
    
    public function thumbnailUrl(): Attribute
    {
        return Attribute::get(function () {
            return Storage::url($this->attributes['thumbnail']);
        });
    }
    
    public function setThumbnailAttribute(UploadedFile $file): void
    {
        if (!empty($this->attributes['thumbnail'])) {
            Storage::delete($this->attributes['thumbnail']);
        }

        $fileName = Str::slug(microtime() . '_' . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
        $filePath = "products/" . $this->attributes['slug']  . "/$fileName";
        
        Storage::put($filePath, File::get($file));
        Storage::setVisibility($filePath, 'public');
        
        $this->attributes['thumbnail'] = $filePath;
    }
    
    public function imagesFolderPath(): string
    {
        ds($this->slug)->label('imagesFolderPath');
        return "products/$this->slug";
    }
}
