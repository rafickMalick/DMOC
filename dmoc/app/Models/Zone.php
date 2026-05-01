<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'country', 'base_tariff_xof', 'per_kg_xof', 'polygon'])]
class Zone extends Model
{
    protected function casts(): array
    {
        return [
            'polygon' => 'array',
        ];
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_zone_id');
    }
}
