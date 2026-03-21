<?php

namespace App\Services\Payment\Adapters;

use App\Models\Order;
use App\Services\Payment\PaymentProcessorInterface;
use Illuminate\Support\Str;

/**
 * Adapter: wraps card payment gateway into the unified interface.
 *
 * In production this would integrate with a real payment gateway
 * (e.g., mPay, Stripe). For now it simulates the card processing.
 */
class CardPaymentAdapter implements PaymentProcessorInterface
{
    public function getName(): string
    {
        return 'Card bancar';
    }

    public function getCode(): string
    {
        return 'card';
    }

    public function requiresOnlineProcessing(): bool
    {
        return true;
    }

    public function process(Order $order): array
    {
        // Simulate gateway call — in production, this would call
        // an external API (Stripe, mPay, etc.) and adapt its
        // response to our unified format.
        $transactionId = 'CARD-' . strtoupper(Str::random(12));

        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'message' => 'Plata cu cardul a fost procesată cu succes.',
        ];
    }
}
