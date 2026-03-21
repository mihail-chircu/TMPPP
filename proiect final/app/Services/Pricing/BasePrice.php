<?php

namespace App\Services\Pricing;

/**
 * Concrete Component: the base product price.
 */
class BasePrice implements PriceCalculator
{
    public function __construct(
        private float $price,
    ) {}

    public function calculate(): float
    {
        return $this->price;
    }

    public function getBreakdown(): array
    {
        return [
            ['label' => 'Preț de bază', 'amount' => $this->price],
        ];
    }
}
