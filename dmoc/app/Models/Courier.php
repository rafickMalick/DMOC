<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'license_number',
    'vehicle_type',
    'vehicle_plate',
    'delivery_zone',
    'status',
    'rating',
    'completed_deliveries',
    'current_location',
    'profile_photo_path',
])]
class Courier extends Model
{
    protected function casts(): array
    {
        return [
            'current_location' => 'array',
            'rating' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }
}
