<?php

namespace App\Services\Payment\Adapters;

use App\Models\Order;
use App\Services\Payment\Gateways\StripeGatewayClient;
use App\Services\Payment\PaymentProcessorInterface;
use Illuminate\Support\Str;

/**
 * Adapter Pattern — Adapter.
 *
 * Adapts StripeGatewayClient (Adaptee) to PaymentProcessorInterface
 * (Target). Responsibilities of the adaptation:
 *   - converts our Order's MDL total into integer minor units
 *     expected by Stripe
 *   - lowercases the currency code
 *   - generates a card token placeholder in the format Stripe expects
 *   - maps the Stripe response (id, status, card.last4...) back to
 *     our unified shape (success, transaction_id, message).
 */
class CardPaymentAdapter implements PaymentProcessorInterface
{
    public function __construct(
        private StripeGatewayClient $gateway,
    ) {}

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
        $amountInCents = (int) round(((float) $order->total) * 100);
        $currency = strtolower($order->currency ?? 'mdl');
        $cardToken = 'tok_' . Str::random(24);

        $response = $this->gateway->createCharge($amountInCents, $currency, $cardToken);

        $success = $response['status'] === 'succeeded';
        $last4 = $response['card']['last4'] ?? '****';

        return [
            'success' => $success,
            'transaction_id' => $success ? $response['id'] : null,
            'message' => $success
                ? "Plata cu cardul **** {$last4} a fost procesată cu succes."
                : 'Plata a fost respinsă de procesator.',
        ];
    }
}
