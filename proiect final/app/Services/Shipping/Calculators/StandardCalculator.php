<?php

namespace App\Services\Shipping\Calculators;

use App\Services\Shipping\ShippingCalculator;
use App\Services\Shipping\ShippingQuote;

/**
 * Standard shipping pricing:
 *   - base rate 35 MDL
 *   - +3 MDL for each item beyond the second (bulky parcel surcharge)
 *   - +15 MDL surcharge for destinations outside Chișinău
 *   - tier discount: orders over 300 MDL get a 5 MDL volume discount
 */
class StandardCalculator implements ShippingCalculator
{
    private const BASE_RATE = 35.00;

    private const PER_EXTRA_ITEM = 3.00;

    private const OUT_OF_CITY_SURCHARGE = 15.00;

    private const VOLUME_DISCOUNT_THRESHOLD = 300.00;

    private const VOLUME_DISCOUNT = 5.00;

    public function calculate(ShippingQuote $quote): float
    {
        $cost = self::BASE_RATE;

        $extraItems = max(0, $quote->itemCount - 2);
        $cost += $extraItems * self::PER_EXTRA_ITEM;

        if (! $quote->isLocal()) {
            $cost += self::OUT_OF_CITY_SURCHARGE;
        }

        if ($quote->orderTotal >= self::VOLUME_DISCOUNT_THRESHOLD) {
            $cost -= self::VOLUME_DISCOUNT;
        }

        return round(max($cost, 0), 2);
    }

    public function getEstimatedDays(ShippingQuote $quote): string
    {
        return $quote->isLocal() ? '2-3 zile lucrătoare' : '4-6 zile lucrătoare';
    }

    public function isEligible(ShippingQuote $quote): bool
    {
        return true;
    }
}
