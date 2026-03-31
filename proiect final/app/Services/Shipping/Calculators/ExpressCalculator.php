<?php

namespace App\Services\Shipping\Calculators;

use App\Services\Shipping\ShippingCalculator;

class ExpressCalculator implements ShippingCalculator
{
    public function calculate(float $orderTotal): float
    {
        return 65.00;
    }

    public function getEstimatedDays(): string
    {
        return '1-2 zile lucrătoare';
    }
}
