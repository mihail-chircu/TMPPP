<?php

namespace App\Services;

use App\Models\Order;
use App\Notifications\Factory\NotificationContent;
use App\Notifications\Factory\OrderNotificationFactory;
use Illuminate\Support\Facades\Log;

/**
 * Uses the Abstract Factory to send order notifications
 * through whatever channel the injected factory produces.
 */
class OrderNotificationService
{
    public function __construct(
        private OrderNotificationFactory $factory,
    ) {}

    public function notifyOrderConfirmation(Order $order): NotificationContent
    {
        $notification = $this->factory->createOrderConfirmation($order);
        $this->dispatch($notification, $order);

        return $notification;
    }

    public function notifyStatusUpdate(Order $order, string $newStatus): NotificationContent
    {
        $notification = $this->factory->createStatusUpdate($order, $newStatus);
        $this->dispatch($notification, $order);

        if ($newStatus === 'shipped') {
            $shipping = $this->factory->createShippingNotification($order);
            $this->dispatch($shipping, $order);
        }

        return $notification;
    }

    private function dispatch(NotificationContent $notification, Order $order): void
    {
        Log::channel('single')->info("[{$notification->channel}] To: {$order->customer_email}", [
            'subject' => $notification->subject,
            'order' => $order->order_number,
        ]);
    }
}
