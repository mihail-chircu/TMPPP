<?php

namespace App\Notifications\Factory;

use App\Models\Order;

/**
 * Abstract Factory Pattern.
 *
 * Declares the interface for creating a family of related notification
 * products (confirmation, status update, shipping). Each concrete
 * factory produces the same three products but tuned for its own
 * delivery channel (Email, SMS) so every family is internally consistent.
 */
interface OrderNotificationFactory
{
    public function createOrderConfirmation(Order $order): OrderConfirmationNotification;

    public function createStatusUpdate(Order $order, string $newStatus): StatusUpdateNotification;

    public function createShippingNotification(Order $order): ShippingNotification;
}
