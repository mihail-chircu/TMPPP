<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Services\OrderNotificationService;

/**
 * Observer Pattern — Concrete Observer.
 *
 * Reacts to OrderPlaced by sending a confirmation
 * notification through the configured channel (email/SMS).
 */
class SendOrderConfirmation
{
    public function __construct(
        private OrderNotificationService $notificationService,
    ) {}

    public function handle(OrderPlaced $event): void
    {
        $this->notificationService->notifyOrderConfirmation($event->order);
    }
}
