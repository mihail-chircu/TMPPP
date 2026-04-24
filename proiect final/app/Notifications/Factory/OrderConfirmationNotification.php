<?php

namespace App\Notifications\Factory;

/**
 * Abstract Factory Pattern — Concrete Product A.
 *
 * Produced by every OrderNotificationFactory when a customer
 * completes a checkout. Carries a per-item order summary so the
 * customer can see what they bought.
 */
class OrderConfirmationNotification extends NotificationContent
{
    public function __construct(
        string $subject,
        string $body,
        string $channel,
        public readonly array $orderSummary,
        public readonly float $totalAmount,
    ) {
        parent::__construct($subject, $body, $channel);
    }
}
