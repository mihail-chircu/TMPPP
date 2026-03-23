<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Observer Pattern — Subject Event.
 *
 * Dispatched when a new order is placed. Multiple listeners
 * (observers) react independently to this event without
 * the checkout code knowing about them.
 */
class OrderPlaced
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Order $order,
    ) {}
}
