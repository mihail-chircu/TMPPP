<?php

namespace App\Services\Payment\Adapters;

use App\Models\Order;
use App\Services\Payment\PaymentProcessorInterface;

/**
 * Adapter: wraps bank transfer logic into the unified interface.
 */
class BankTransferAdapter implements PaymentProcessorInterface
{
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
        // Bank transfer: the customer pays manually via bank.
        // Order is held until payment is confirmed by admin.
        return [
            'success' => true,
            'transaction_id' => 'BT-' . $order->order_number,
            'message' => 'Detaliile bancare au fost trimise pe email. Comanda va fi procesată după confirmarea plății.',
        ];
    }
}
