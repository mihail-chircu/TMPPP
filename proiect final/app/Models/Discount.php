<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'discount_percent',
        'original_price',
        'discounted_price',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('starts_at', '>', now());
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('ends_at', '<', now());
    }

    public function getIsCurrentlyActiveAttribute(): bool
    {
        return $this->is_active
            && $this->starts_at->lte(now())
            && $this->ends_at->gte(now());
    }
}
