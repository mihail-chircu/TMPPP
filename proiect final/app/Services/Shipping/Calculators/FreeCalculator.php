<?php

namespace App\Services\Shipping\Calculators;

use App\Services\Shipping\ShippingCalculator;

class FreeCalculator implements ShippingCalculator
{
    public function calculate(float $orderTotal): float
    {
        return 0.00;
    }

    public function getEstimatedDays(): string
    {
        return '5-7 zile lucrătoare';
    }
}
