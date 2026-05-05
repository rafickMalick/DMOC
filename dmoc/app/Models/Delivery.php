<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'order_id',
    'courier_id',
    'zone_id',
    'status',
    'started_at',
    'assigned_at',
    'picked_up_at',
    'delivered_at',
    'failed_reason',
    'amount_expected',
    'amount_received',
    'cod_received_at',
    'notes',
    'weight_kg',
    'delivery_fee_xof',
    'tracking_updates',
])]
class Delivery extends Model
{
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'assigned_at' => 'datetime',
            'picked_up_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cod_received_at' => 'datetime',
            'tracking_updates' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }
}
