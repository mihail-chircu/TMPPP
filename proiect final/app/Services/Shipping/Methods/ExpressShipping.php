<?php

namespace App\Services\Shipping\Methods;

use App\Services\Shipping\Calculators\ExpressCalculator;
use App\Services\Shipping\ShippingCalculator;
use App\Services\Shipping\ShippingMethod;

/**
 * Factory Method Pattern — Concrete Creator.
 *
 * Overrides the factory method to create an ExpressCalculator.
 */
class ExpressShipping extends ShippingMethod
{
    public function createCalculator(): ShippingCalculator
    {
        return new ExpressCalculator();
    }

    public function getName(): string
    {
        return 'Livrare express';
    }

    public function getCode(): string
    {
        return 'express';
    }
}
