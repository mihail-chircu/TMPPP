<?php

namespace App\Notifications\Factory;

/**
 * Abstract Factory Pattern — Abstract Product.
 *
 * Common base for all notification products. Concrete notification
 * classes (confirmation, status update, shipping) extend this and
 * add their own fields.
 */
abstract class NotificationContent
{
    public function __construct(
        public readonly string $subject,
        public readonly string $body,
        public readonly string $channel,
    ) {}
}
