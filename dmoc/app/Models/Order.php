<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'total_xof',
    'delivery_fee_xof',
    'status',
    'payment_method',
    'delivery_zone_id',
    'estimated_delivery',
    'shipping_address',
    'shipping_phone',
    'recipient_name',
    'notes',
    'cancelled_reason',
])]
class Order extends Model
{
    protected function casts(): array
    {
        return [
            'estimated_delivery' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'delivery_zone_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class)->latest();
    }
}
