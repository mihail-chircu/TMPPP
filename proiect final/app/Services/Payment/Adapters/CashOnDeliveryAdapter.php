<?php

namespace App\Services\Payment\Adapters;

use App\Models\Order;
use App\Services\Payment\PaymentProcessorInterface;

/**
 * Adapter: wraps cash-on-delivery logic into the unified interface.
 */
class CashOnDeliveryAdapter implements PaymentProcessorInterface
{
    public function getName(): string
    {
        return 'Plata la livrare';
    }

    public function getCode(): string
    {
        return 'cash_on_delivery';
    }

    public function requiresOnlineProcessing(): bool
    {
        return false;
    }

    public function process(Order $order): array
    {
        // Cash on delivery doesn't need online processing.
        // Payment is collected by the courier upon delivery.
        return [
            'success' => true,
            'transaction_id' => 'COD-' . $order->order_number,
            'message' => 'Plata va fi colectată la livrare.',
        ];
    }
}
