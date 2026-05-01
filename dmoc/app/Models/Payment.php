<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'order_id',
    'method',
    'amount_xof',
    'status',
    'transaction_id',
    'reference',
    'response_data',
])]
class Payment extends Model
{
    protected function casts(): array
    {
        return [
            'response_data' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
