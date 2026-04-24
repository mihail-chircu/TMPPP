<?php

namespace App\Notifications\Factory;

/**
 * Abstract Factory Pattern — Concrete Product B.
 *
 * Produced when an order transitions to a new state. Carries both
 * the previous and the new status so the customer sees the change.
 */
class StatusUpdateNotification extends NotificationContent
{
    public function __construct(
        string $subject,
        string $body,
        string $channel,
        public readonly string $previousStatus,
        public readonly string $newStatus,
    ) {
        parent::__construct($subject, $body, $channel);
    }
}
