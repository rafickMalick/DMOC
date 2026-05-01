<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'reviewable_type',
    'reviewable_id',
    'rating',
    'comment',
    'is_verified_purchase',
])]
class Review extends Model
{
    protected function casts(): array
    {
        return [
            'is_verified_purchase' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }
}
