<?php

namespace App\Notifications\Channels\Sms;

use App\Models\Order;
use App\Notifications\Factory\NotificationContent;
use App\Notifications\Factory\OrderNotificationFactory;

/**
 * Concrete Factory: creates SMS notifications for orders.
 *
 * SMS messages are shorter and more concise than email.
 */
class SmsNotificationFactory implements OrderNotificationFactory
{
    public function createOrderConfirmation(Order $order): NotificationContent
    {
        return new NotificationContent(
            subject: 'Confirmare comandă',
            body: "Kinder: Comanda #{$order->order_number} ({$order->total} MDL) a fost plasată. Mulțumim!",
            channel: 'sms',
        );
    }

    public function createStatusUpdate(Order $order, string $newStatus): NotificationContent
    {
        $statusLabels = [
            'pending' => 'în așteptare',
            'processing' => 'se procesează',
            'shipped' => 'expediată',
            'delivered' => 'livrată',
            'cancelled' => 'anulată',
        ];

        $label = $statusLabels[$newStatus] ?? $newStatus;

        return new NotificationContent(
            subject: 'Status comandă',
            body: "Kinder: Comanda #{$order->order_number} — status nou: {$label}.",
            channel: 'sms',
        );
    }

    public function createShippingNotification(Order $order): NotificationContent
    {
        return new NotificationContent(
            subject: 'Expediere',
            body: "Kinder: Comanda #{$order->order_number} a fost expediată spre {$order->shipping_city}. Livrare în curând!",
            channel: 'sms',
        );
    }
}
