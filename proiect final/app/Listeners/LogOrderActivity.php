<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Log;

/**
 * Observer Pattern — Concrete Observer.
 *
 * Reacts to OrderPlaced by logging the order activity
 * for auditing and debugging purposes.
 */
class LogOrderActivity
{
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order;

        Log::channel('single')->info('New order placed', [
            'order_number' => $order->order_number,
            'customer' => $order->customer_name,
            'email' => $order->customer_email,
            'total' => $order->total,
            'items_count' => $order->items->count(),
            'payment_method' => $order->payment_method,
        ]);
    }
}
