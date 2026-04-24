<?php

namespace App\Services\Payment\Adapters;

use App\Models\Order;
use App\Services\Payment\PaymentProcessorInterface;

/**
 * Concrete implementation of PaymentProcessorInterface.
 *
 * Unlike CardPaymentAdapter and BankTransferAdapter, this one does
 * not wrap a foreign gateway — cash-on-delivery is purely an internal
 * operation (the courier collects the money). It exists in the
 * adapter family so the PaymentService can treat every method
 * uniformly.
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
