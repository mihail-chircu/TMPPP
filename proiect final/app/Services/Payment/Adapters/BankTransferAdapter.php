<?php

namespace App\Services\Payment\Adapters;

use App\Models\Order;
use App\Services\Payment\Gateways\LocalBankApiClient;
use App\Services\Payment\PaymentProcessorInterface;

/**
 * Adapter Pattern — Adapter.
 *
 * Adapts LocalBankApiClient (Adaptee) to PaymentProcessorInterface
 * (Target). The bank API speaks in IBANs, state machines and nested
 * error objects; the adapter turns that into the simple shape
 * expected by the rest of the app.
 */
class BankTransferAdapter implements PaymentProcessorInterface
{
    private const MERCHANT_IBAN = 'MD24AG000000225100013104';

    public function __construct(
        private LocalBankApiClient $bankApi,
    ) {}

    public function getName(): string
    {
        return 'Transfer bancar';
    }

    public function getCode(): string
    {
        return 'bank_transfer';
    }

    public function requiresOnlineProcessing(): bool
    {
        return false;
    }

    public function process(Order $order): array
    {
        $response = $this->bankApi->openTransferRequest(
            iban: self::MERCHANT_IBAN,
            amountMdl: (float) $order->total,
            purpose: "Comanda #{$order->order_number}",
        );

        if ($response['state'] === 'REJECTED') {
            $error = $response['error']['message'] ?? 'Cererea de transfer a fost respinsă.';

            return [
                'success' => false,
                'transaction_id' => null,
                'message' => $error,
            ];
        }

        return [
            'success' => true,
            'transaction_id' => $response['reference'],
            'message' => 'Detaliile bancare au fost trimise pe email. Instrucțiuni: '
                . $response['instructions_url']
                . '. Comanda va fi procesată după confirmarea plății.',
        ];
    }
}
