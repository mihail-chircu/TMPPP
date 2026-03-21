<?php

namespace App\Services\Pricing;

/**
 * Concrete Decorator: adds gift wrapping fee.
 */
class GiftWrapDecorator extends PriceDecorator
{
    private const FEE = 25.00; // 25 MDL for gift wrapping

    public function calculate(): float
    {
        return round($this->wrapped->calculate() + self::FEE, 2);
    }

    public function getBreakdown(): array
    {
        return array_merge($this->wrapped->getBreakdown(), [
            ['label' => 'Ambalare cadou', 'amount' => self::FEE],
        ]);
    }
}
