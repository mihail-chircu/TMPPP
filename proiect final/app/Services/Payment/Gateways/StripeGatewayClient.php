<?php

namespace App\Services\Payment\Gateways;

/**
 * Adapter Pattern — Adaptee (third-party style).
 *
 * Simulates a Stripe-like payment gateway client. Its API is
 * intentionally incompatible with our PaymentProcessorInterface:
 *   - amounts are minor units (cents), not MDL
 *   - currency must be lowercase ISO-4217
 *   - input is a card token, not our Order
 *   - response uses Stripe-style field names (id, object, status,
 *     amount_captured, card.last4) — not our unified array
 *
 * In production this class would be replaced by the official SDK.
 * The CardPaymentAdapter is what lets the rest of the app call
 * this foreign API through our own unified interface.
 */
class StripeGatewayClient
{
    /**
     * Charge a card. Mirrors the shape of Stripe's /v1/charges endpoint.
     *
     * @param  int  $amountInCents  Amount in minor units (e.g. 123.45 MDL => 12345)
     * @param  string  $currency  ISO-4217 lowercase (e.g. "mdl", "usd")
     * @param  string  $cardToken  One-time tokenized card reference (tok_*)
     * @return array{
     *     id: string,
     *     object: string,
     *     status: string,
     *     amount_captured: int,
     *     currency: string,
     *     card: array{last4: string, brand: string},
     *     created: int
     * }
     */
    public function createCharge(int $amountInCents, string $currency, string $cardToken): array
    {
        if ($amountInCents <= 0) {
            return [
                'id' => 'ch_' . bin2hex(random_bytes(10)),
                'object' => 'charge',
                'status' => 'failed',
                'failure_code' => 'amount_too_small',
                'amount_captured' => 0,
                'currency' => $currency,
                'card' => ['last4' => '0000', 'brand' => 'unknown'],
                'created' => time(),
            ];
        }

        return [
            'id' => 'ch_' . bin2hex(random_bytes(12)),
            'object' => 'charge',
            'status' => 'succeeded',
            'amount_captured' => $amountInCents,
            'currency' => $currency,
            'card' => [
                'last4' => substr($cardToken, -4),
                'brand' => 'visa',
            ],
            'created' => time(),
        ];
    }
}
