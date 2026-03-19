<?php

namespace App\Notifications\Channels\Email;

use App\Models\Order;
use App\Notifications\Factory\NotificationContent;
use App\Notifications\Factory\OrderNotificationFactory;

/**
 * Concrete Factory: creates Email notifications for orders.
 */
class EmailNotificationFactory implements OrderNotificationFactory
{
    public function createOrderConfirmation(Order $order): NotificationContent
    {
        $itemsList = $order->items->map(
            fn ($item) => "- {$item->product_name} x{$item->quantity}: {$item->total} MDL"
        )->implode("\n");

        return new NotificationContent(
            subject: "Confirmare comandă #{$order->order_number}",
            body: "Dragă {$order->customer_name},\n\n"
                . "Comanda ta #{$order->order_number} a fost plasată cu succes!\n\n"
                . "Produse comandate:\n{$itemsList}\n\n"
                . "Total: {$order->total} MDL\n\n"
                . "Adresa de livrare: {$order->shipping_address}, {$order->shipping_city}\n\n"
                . "Îți mulțumim că ai ales Kinder! 🧸",
            channel: 'email',
        );
    }

    public function createStatusUpdate(Order $order, string $newStatus): NotificationContent
    {
        $statusLabels = [
            'pending' => 'În așteptare',
            'processing' => 'Se procesează',
            'shipped' => 'Expediată',
            'delivered' => 'Livrată',
            'cancelled' => 'Anulată',
        ];

        $label = $statusLabels[$newStatus] ?? $newStatus;

        return new NotificationContent(
            subject: "Actualizare comandă #{$order->order_number} — {$label}",
            body: "Dragă {$order->customer_name},\n\n"
                . "Statusul comenzii tale #{$order->order_number} a fost actualizat la: {$label}.\n\n"
                . "Poți urmări detaliile comenzii în contul tău.\n\n"
                . "Echipa Kinder",
            channel: 'email',
        );
    }

    public function createShippingNotification(Order $order): NotificationContent
    {
        return new NotificationContent(
            subject: "Comanda #{$order->order_number} a fost expediată! 🚚",
            body: "Dragă {$order->customer_name},\n\n"
                . "Vești bune! Comanda ta #{$order->order_number} a fost expediată.\n\n"
                . "Adresa de livrare: {$order->shipping_address}, {$order->shipping_city}\n\n"
                . "Te vom notifica când coletul va fi livrat.\n\n"
                . "Echipa Kinder 🧸",
            channel: 'email',
        );
    }
}
