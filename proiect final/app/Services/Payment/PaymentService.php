<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Services\Payment\Adapters\BankTransferAdapter;
use App\Services\Payment\Adapters\CardPaymentAdapter;
use App\Services\Payment\Adapters\CashOnDeliveryAdapter;
use InvalidArgumentException;

/**
 * Resolves the correct payment adapter and processes payment.
 */
class PaymentService
{
    /** @var array<string, PaymentProcessorInterface> */
    private array $adapters;

    public function __construct()
    {
        // Register available payment adapters
        $this->adapters = [
            'cash_on_delivery' => new CashOnDeliveryAdapter(),
            'bank_transfer' => new BankTransferAdapter(),
            'card' => new CardPaymentAdapter(),
        ];
    }

    public function getAvailableMethods(): array
    {
        return array_map(fn (PaymentProcessorInterface $adapter) => [
            'code' => $adapter->getCode(),
            'name' => $adapter->getName(),
            'requires_online' => $adapter->requiresOnlineProcessing(),
        ], $this->adapters);
    }

    public function process(Order $order, string $method): array
    {
        $adapter = $this->adapters[$method] ?? null;

        if (! $adapter) {
            throw new InvalidArgumentException("Payment method [{$method}] is not supported.");
        }

        return $adapter->process($order);
    }
}
