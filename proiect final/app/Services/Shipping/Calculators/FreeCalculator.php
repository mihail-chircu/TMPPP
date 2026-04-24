<?php

namespace App\Services\Shipping\Calculators;

use App\Services\Shipping\ShippingCalculator;
use App\Services\Shipping\ShippingQuote;

/**
 * Free shipping:
 *   - cost is always 0
 *   - only eligible when the order total reaches the promotion threshold
 *   - delivery time is slower than standard (low-priority queue)
 */
class FreeCalculator implements ShippingCalculator
{
    private const MIN_ORDER_TOTAL = 500.00;

    public function calculate(ShippingQuote $quote): float
    {
        return 0.00;
    }

    public function getEstimatedDays(ShippingQuote $quote): string
    {
        return '5-7 zile lucrătoare';
    }

    public function isEligible(ShippingQuote $quote): bool
    {
        return $quote->orderTotal >= self::MIN_ORDER_TOTAL;
    }
}
