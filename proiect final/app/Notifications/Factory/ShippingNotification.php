<?php

namespace App\Notifications\Factory;

/**
 * Abstract Factory Pattern — Concrete Product C.
 *
 * Produced when an order is handed off to the courier. Carries the
 * tracking code and ETA so the customer can follow the parcel.
 */
class ShippingNotification extends NotificationContent
{
    public function __construct(
        string $subject,
        string $body,
        string $channel,
        public readonly string $trackingCode,
        public readonly string $estimatedDelivery,
    ) {
        parent::__construct($subject, $body, $channel);
    }
}
