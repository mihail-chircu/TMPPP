<?php

namespace App\Services\Shipping\Methods;

use App\Services\Shipping\Calculators\FreeCalculator;
use App\Services\Shipping\ShippingCalculator;
use App\Services\Shipping\ShippingMethod;

/**
 * Factory Method Pattern — Concrete Creator.
 *
 * Overrides the factory method to create a FreeCalculator.
 * Available only for orders over 500 MDL.
 */
class FreeShipping extends ShippingMethod
{
    public function createCalculator(): ShippingCalculator
    {
        return new FreeCalculator();
    }

    public function getName(): string
    {
        return 'Livrare gratuită';
    }

    public function getCode(): string
    {
        return 'free';
    }
}
