<?php

namespace App\Services\OrderState;

use App\Models\Order;
use InvalidArgumentException;

/**
 * State Pattern — Abstract State.
 *
 * Defines the interface for each order status.
 * Each concrete state knows which transitions are allowed
 * and what actions can be performed.
 */
abstract class OrderState
{
    public function __construct(
        protected Order $order,
    ) {}

    abstract public function getStatus(): string;

    abstract public function getLabel(): string;

    abstract public function getColor(): string;

    /**
     * List of statuses this state can transition to.
     */
    abstract public function allowedTransitions(): array;

    public function canTransitionTo(string $status): bool
    {
        return in_array($status, $this->allowedTransitions());
    }

    public function transitionTo(string $status): void
    {
        if (! $this->canTransitionTo($status)) {
            throw new InvalidArgumentException(
                "Cannot transition from [{$this->getStatus()}] to [{$status}]."
            );
        }

        $this->order->update(['status' => $status]);
    }

    /**
     * Resolve the correct state object for an order.
     */
    public static function resolve(Order $order): self
    {
        return match ($order->status) {
            'pending' => new States\PendingState($order),
            'processing' => new States\ProcessingState($order),
            'shipped' => new States\ShippedState($order),
            'delivered' => new States\DeliveredState($order),
            'cancelled' => new States\CancelledState($order),
            default => throw new InvalidArgumentException("Unknown status: {$order->status}"),
        };
    }
}
