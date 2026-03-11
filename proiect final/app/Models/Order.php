<?php

namespace App\Models;

use App\Services\OrderState\OrderState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'discount_total',
        'shipping_cost',
        'total',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'notes',
        'payment_method',
        'shipping_method',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = 'KND-' . time() . '-' . mt_rand(1000, 9999);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * State Pattern: get the current state object for this order.
     */
    public function state(): OrderState
    {
        return OrderState::resolve($this);
    }

    /**
     * State Pattern: transition to a new status with validation.
     */
    public function transitionTo(string $status): void
    {
        $this->state()->transitionTo($status);
    }
}
