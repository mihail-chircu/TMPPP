<?php

namespace App\Services\Shipping;

/**
 * Value object passed to ShippingCalculator.
 *
 * Encapsulates everything a calculator needs to price a shipment.
 */
final class ShippingQuote
{
    public function __construct(
        public readonly float $orderTotal,
        public readonly int $itemCount,
        public readonly string $destinationCity,
    ) {}

    public function isLocal(): bool
    {
        return mb_strtolower(trim($this->destinationCity)) === 'chișinău'
            || mb_strtolower(trim($this->destinationCity)) === 'chisinau';
    }
}
