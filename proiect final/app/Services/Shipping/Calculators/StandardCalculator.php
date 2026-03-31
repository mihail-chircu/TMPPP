<?php

namespace App\Services\Shipping\Calculators;

use App\Services\Shipping\ShippingCalculator;

class StandardCalculator implements ShippingCalculator
{
    public function calculate(float $orderTotal): float
    {
        return 35.00;
    }

    public function getEstimatedDays(): string
    {
        return '3-5 zile lucrătoare';
    }
}
