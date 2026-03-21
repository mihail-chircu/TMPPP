<?php

namespace App\Services\Pricing;

/**
 * Decorator Pattern — Component interface.
 *
 * Defines the interface for objects that can have
 * pricing responsibilities added dynamically.
 */
interface PriceCalculator
{
    public function calculate(): float;

    public function getBreakdown(): array;
}
