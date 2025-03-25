<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentSystemEnum;
use App\Enums\TransactionStatusesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property PaymentSystemEnum $payment_system
 * @property TransactionStatusesEnum $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\Order|null $oder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction wherePaymentSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    protected $casts = [
        'status' => TransactionStatusesEnum::class,
        'payment_system' => PaymentSystemEnum::class,
    ];
    
    public function oder(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
