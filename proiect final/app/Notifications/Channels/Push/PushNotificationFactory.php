<?php

namespace App\Notifications\Channels\Push;

use App\Models\Order;
use App\Notifications\Factory\OrderConfirmationNotification;
use App\Notifications\Factory\OrderNotificationFactory;
use App\Notifications\Factory\ShippingNotification;
use App\Notifications\Factory\StatusUpdateNotification;

/**
 * Abstract Factory Pattern — Concrete Factory.
 *
 * Produces the Push notification family. Push alerts have a stricter
 * length budget than SMS (title + short body shown on a lockscreen)
 * and support deep-linking back into the admin/customer app.
 */
class PushNotificationFactory implements OrderNotificationFactory
{
    public function createOrderConfirmation(Order $order): OrderConfirmationNotification
    {
        $itemCount = $order->items->sum('quantity');

        $summary = $order->items->map(fn ($item) => [
            'name' => $item->product_name,
            'quantity' => $item->quantity,
            'total' => (float) $item->total,
        ])->all();

        return new OrderConfirmationNotification(
            subject: "Comandă #{$order->order_number}",
            body: "{$itemCount} produse · {$order->total} MDL · atinge pentru detalii",
            channel: 'push',
            orderSummary: $summary,
            totalAmount: (float) $order->total,
        );
    }

    public function createStatusUpdate(Order $order, string $newStatus): StatusUpdateNotification
    {
        $statusLabels = [
            'pending' => 'În așteptare',
            'processing' => 'Se procesează',
            'shipped' => 'Expediată',
            'delivered' => 'Livrată',
            'cancelled' => 'Anulată',
        ];

        $label = $statusLabels[$newStatus] ?? ucfirst($newStatus);
        $previous = $order->getOriginal('status') ?? $order->status;

        return new StatusUpdateNotification(
            subject: $label,
            body: "Comanda #{$order->order_number}",
            channel: 'push',
            previousStatus: (string) $previous,
            newStatus: $newStatus,
        );
    }

    public function createShippingNotification(Order $order): ShippingNotification
    {
        $tracking = 'KND-' . strtoupper(substr(md5($order->order_number), 0, 10));
        $eta = now()->addDays(3)->format('d.m.Y');

        return new ShippingNotification(
            subject: 'Coletul tău a pornit',
            body: "#{$order->order_number} · ETA {$eta}",
            channel: 'push',
            trackingCode: $tracking,
            estimatedDelivery: $eta,
        );
    }
}
