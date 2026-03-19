<?php

namespace App\Notifications\Factory;

/**
 * Value object holding notification content produced by factories.
 */
class NotificationContent
{
    public function __construct(
        public readonly string $subject,
        public readonly string $body,
        public readonly string $channel,
    ) {}
}
