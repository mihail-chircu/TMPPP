<?php

namespace App\Services\Shipping\Methods;

use App\Services\Shipping\Calculators\StandardCalculator;
use App\Services\Shipping\ShippingCalculator;
use App\Services\Shipping\ShippingMethod;

/**
 * Factory Method Pattern — Concrete Creator.
 *
 * Overrides the factory method to create a StandardCalculator.
 */
class StandardShipping extends ShippingMethod
{
    public function createCalculator(): ShippingCalculator
    {
        return new StandardCalculator();
    }

    public function getName(): string
    {
        return 'Livrare standard';
    }

    public function getCode(): string
    {
        return 'standard';
    }
}
