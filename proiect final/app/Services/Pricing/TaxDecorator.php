<?php

namespace App\Services\Pricing;

/**
 * Concrete Decorator: applies VAT/tax.
 */
class TaxDecorator extends PriceDecorator
{
    public function __construct(
        PriceCalculator $wrapped,
        private float $rate = 20.0, // 20% TVA in Moldova
    ) {
        parent::__construct($wrapped);
    }

    public function calculate(): float
    {
        $base = $this->wrapped->calculate();

        return round($base * (1 + $this->rate / 100), 2);
    }

    public function getBreakdown(): array
    {
        $base = $this->wrapped->calculate();
        $tax = round($base * $this->rate / 100, 2);

        return array_merge($this->wrapped->getBreakdown(), [
            ['label' => "TVA ({$this->rate}%)", 'amount' => $tax],
        ]);
    }
}
