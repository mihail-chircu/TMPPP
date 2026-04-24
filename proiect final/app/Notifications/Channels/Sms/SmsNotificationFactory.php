<?php

namespace App\Notifications\Channels\Sms;

use App\Models\Order;
use App\Notifications\Factory\OrderConfirmationNotification;
use App\Notifications\Factory\OrderNotificationFactory;
use App\Notifications\Factory\ShippingNotification;
use App\Notifications\Factory\StatusUpdateNotification;

/**
 * Abstract Factory Pattern — Concrete Factory.
 *
 * Produces the SMS family of order notifications. All products share
 * the short, compact tone suitable for the 160-character SMS channel.
 */
class SmsNotificationFactory implements OrderNotificationFactory
{
    public function createOrderConfirmation(Order $order): OrderConfirmationNotification
    {
        $summary = $order->items->map(fn ($item) => [
            'name' => $item->product_name,
            'quantity' => $item->quantity,
            'total' => (float) $item->total,
        ])->all();

        return new OrderConfirmationNotification(
            subject: 'Confirmare comandă',
            body: "Kinder: Comanda #{$order->order_number} ({$order->total} MDL) a fost plasată. Mulțumim!",
            channel: 'sms',
            orderSummary: $summary,
            totalAmount: (float) $order->total,
        );
    }

    public function createStatusUpdate(Order $order, string $newStatus): StatusUpdateNotification
    {
        $statusLabels = [
            'pending' => 'în așteptare',
            'processing' => 'se procesează',
            'shipped' => 'expediată',
            'delivered' => 'livrată',
            'cancelled' => 'anulată',
        ];

        $label = $statusLabels[$newStatus] ?? $newStatus;
        $previous = $order->getOriginal('status') ?? $order->status;

        return new StatusUpdateNotification(
            subject: 'Status comandă',
            body: "Kinder: Comanda #{$order->order_number} — status nou: {$label}.",
            channel: 'sms',
            previousStatus: (string) $previous,
            newStatus: $newStatus,
        );
    }

    public function createShippingNotification(Order $order): ShippingNotification
    {
        $tracking = 'KND-' . strtoupper(substr(md5($order->order_number), 0, 10));
        $eta = now()->addDays(3)->format('d.m.Y');

        return new ShippingNotification(
            subject: 'Expediere',
            body: "Kinder: Comanda #{$order->order_number} expediată. Tracking: {$tracking}. ETA: {$eta}.",
            channel: 'sms',
            trackingCode: $tracking,
            estimatedDelivery: $eta,
        );
    }
}
