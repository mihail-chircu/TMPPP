<?php

namespace App\Services\Payment\Gateways;

/**
 * Adapter Pattern — Adaptee (third-party style).
 *
 * Simulates a local bank API used for bank transfer payments.
 * Its API is very different from our unified interface:
 *   - uses IBAN and SWIFT codes instead of our Order object
 *   - returns a multi-step transfer state machine
 *     (PENDING_CONFIRMATION → AWAITING_FUNDS → SETTLED / REJECTED)
 *   - ISO-8601 timestamps, not Unix seconds
 *   - error objects, not boolean flags
 *
 * BankTransferAdapter bridges this shape to PaymentProcessorInterface
 * so the rest of the app doesn't care about bank-specific fields.
 */
class LocalBankApiClient
{
    /**
     * Open a transfer request.
     *
     * @return array{
     *     reference: string,
     *     state: string,
     *     iban_destination: string,
     *     amount_mdl: float,
     *     opened_at: string,
     *     instructions_url: string,
     *     error: array{code: string, message: string}|null
     * }
     */
    public function openTransferRequest(string $iban, float $amountMdl, string $purpose): array
    {
        if ($amountMdl <= 0) {
            return [
                'reference' => 'BT-' . strtoupper(bin2hex(random_bytes(4))),
                'state' => 'REJECTED',
                'iban_destination' => $iban,
                'amount_mdl' => $amountMdl,
                'opened_at' => date('c'),
                'instructions_url' => '',
                'error' => [
                    'code' => 'invalid_amount',
                    'message' => 'Amount must be greater than zero.',
                ],
            ];
        }

        $reference = 'BT-' . strtoupper(bin2hex(random_bytes(4)));

        return [
            'reference' => $reference,
            'state' => 'PENDING_CONFIRMATION',
            'iban_destination' => $iban,
            'amount_mdl' => $amountMdl,
            'opened_at' => date('c'),
            'instructions_url' => "https://bank.example.md/transfer/{$reference}",
            'error' => null,
        ];
    }
}
