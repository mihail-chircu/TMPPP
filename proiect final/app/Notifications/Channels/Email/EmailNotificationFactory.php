<?php

namespace App\Notifications\Channels\Email;

use App\Models\Order;
use App\Notifications\Factory\OrderConfirmationNotification;
use App\Notifications\Factory\OrderNotificationFactory;
use App\Notifications\Factory\ShippingNotification;
use App\Notifications\Factory\StatusUpdateNotification;

/**
 * Abstract Factory Pattern — Concrete Factory.
 *
 * Produces the email family of order notifications. All products
 * share the long-form, narrative tone that fits the email channel.
 */
class EmailNotificationFactory implements OrderNotificationFactory
{
    public function createOrderConfirmation(Order $order): OrderConfirmationNotification
    {
        $summary = $order->items->map(fn ($item) => [
            'name' => $item->product_name,
            'quantity' => $item->quantity,
            'total' => (float) $item->total,
        ])->all();

        $itemsList = collect($summary)->map(
            fn ($item) => "- {$item['name']} x{$item['quantity']}: {$item['total']} MDL"
        )->implode("\n");

        return new OrderConfirmationNotification(
            subject: "Confirmare comandă #{$order->order_number}",
            body: "Dragă {$order->customer_name},\n\n"
                . "Comanda ta #{$order->order_number} a fost plasată cu succes!\n\n"
                . "Produse comandate:\n{$itemsList}\n\n"
                . "Total: {$order->total} MDL\n\n"
                . "Adresa de livrare: {$order->shipping_address}, {$order->shipping_city}\n\n"
                . "Îți mulțumim că ai ales Kinder!",
            channel: 'email',
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

        $label = $statusLabels[$newStatus] ?? $newStatus;
        $previous = $order->getOriginal('status') ?? $order->status;

        return new StatusUpdateNotification(
            subject: "Actualizare comandă #{$order->order_number} — {$label}",
            body: "Dragă {$order->customer_name},\n\n"
                . "Statusul comenzii tale #{$order->order_number} a fost actualizat la: {$label}.\n\n"
                . "Poți urmări detaliile comenzii în contul tău.\n\n"
                . "Echipa Kinder",
            channel: 'email',
            previousStatus: (string) $previous,
            newStatus: $newStatus,
        );
    }

    public function createShippingNotification(Order $order): ShippingNotification
    {
        $tracking = 'KND-' . strtoupper(substr(md5($order->order_number), 0, 10));
        $eta = now()->addDays(3)->format('d.m.Y');

        return new ShippingNotification(
            subject: "Comanda #{$order->order_number} a fost expediată!",
            body: "Dragă {$order->customer_name},\n\n"
                . "Vești bune! Comanda ta #{$order->order_number} a fost expediată.\n\n"
                . "Cod de urmărire: {$tracking}\n"
                . "Livrare estimată: {$eta}\n"
                . "Adresa: {$order->shipping_address}, {$order->shipping_city}\n\n"
                . "Te vom notifica când coletul va fi livrat.\n\n"
                . "Echipa Kinder",
            channel: 'email',
            trackingCode: $tracking,
            estimatedDelivery: $eta,
        );
    }
}
