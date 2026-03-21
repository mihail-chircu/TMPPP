<?php

namespace App\Services\Pricing;

/**
 * Concrete Decorator: applies a percentage discount.
 */
class DiscountDecorator extends PriceDecorator
{
    public function __construct(
        PriceCalculator $wrapped,
        private float $percent,
    ) {
        parent::__construct($wrapped);
    }

    public function calculate(): float
    {
        $base = $this->wrapped->calculate();

        return round($base * (1 - $this->percent / 100), 2);
    }

    public function getBreakdown(): array
    {
        $base = $this->wrapped->calculate();
        $discount = round($base * $this->percent / 100, 2);

        return array_merge($this->wrapped->getBreakdown(), [
            ['label' => "Reducere ({$this->percent}%)", 'amount' => -$discount],
        ]);
    }
}
