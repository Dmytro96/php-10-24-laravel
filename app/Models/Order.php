<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 
 *
 * @property int $id
 * @property string $vendor_order_id
 * @property int|null $user_id
 * @property OrderStatusEnum $status
 * @property string $name
 * @property string $lastname
 * @property string $email
 * @property string $phone
 * @property string $city
 * @property string $address
 * @property float $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\Transaction|null $transaction
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereVendorOrderId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    protected $casts = [
        'status' => OrderStatusEnum::class,
        'total' => 'float',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['single_price', 'quantity', 'name']);
    }
    
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }
}
