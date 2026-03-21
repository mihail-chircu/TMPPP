<?php

namespace App\Services\Payment;

use App\Models\Order;

/**
 * Adapter Pattern — Target Interface.
 *
 * A unified interface for processing payments,
 * regardless of the underlying payment provider.
 */
interface PaymentProcessorInterface
{
    /**
     * Get the human-readable name of this payment method.
     */
    public function getName(): string;

    /**
     * Get the machine-readable identifier.
     */
    public function getCode(): string;

    /**
     * Whether this method requires online processing.
     */
    public function requiresOnlineProcessing(): bool;

    /**
     * Process the payment for the given order.
     *
     * @return array{success: bool, transaction_id: string|null, message: string}
     */
    public function process(Order $order): array;
}
