<?php

namespace App\Services\Pricing;

/**
 * Base Decorator: wraps another PriceCalculator.
 */
abstract class PriceDecorator implements PriceCalculator
{
    public function __construct(
        protected PriceCalculator $wrapped,
    ) {}

    public function calculate(): float
    {
        return $this->wrapped->calculate();
    }

    public function getBreakdown(): array
    {
        return $this->wrapped->getBreakdown();
    }
}
