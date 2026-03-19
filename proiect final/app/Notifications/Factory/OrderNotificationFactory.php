<?php

namespace App\Notifications\Factory;

use App\Models\Order;

/**
 * Abstract Factory Pattern
 *
 * Declares the interface for creating a family of order notifications.
 * Each concrete factory produces notifications for a specific channel
 * (Email, SMS) while keeping the same product family.
 */
interface OrderNotificationFactory
{
    public function createOrderConfirmation(Order $order): NotificationContent;

    public function createStatusUpdate(Order $order, string $newStatus): NotificationContent;

    public function createShippingNotification(Order $order): NotificationContent;
}
