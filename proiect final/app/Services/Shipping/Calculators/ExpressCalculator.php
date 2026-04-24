<?php

namespace App\Services\Shipping\Calculators;

use App\Services\Shipping\ShippingCalculator;
use App\Services\Shipping\ShippingQuote;

/**
 * Express shipping pricing:
 *   - base rate 65 MDL
 *   - +6 MDL for each item (courier capacity is limited)
 *   - +25 MDL for destinations outside Chișinău (dedicated courier run)
 *   - +20 MDL weekend surcharge (Saturday / Sunday dispatch)
 *   - insurance fee: 1% of order total, capped at 40 MDL
 */
class ExpressCalculator implements ShippingCalculator
{
    private const BASE_RATE = 65.00;

    private const PER_ITEM = 6.00;

    private const OUT_OF_CITY_SURCHARGE = 25.00;

    private const WEEKEND_SURCHARGE = 20.00;

    private const INSURANCE_RATE = 0.01;

    private const INSURANCE_CAP = 40.00;

    public function calculate(ShippingQuote $quote): float
    {
        $cost = self::BASE_RATE;

        $cost += $quote->itemCount * self::PER_ITEM;

        if (! $quote->isLocal()) {
            $cost += self::OUT_OF_CITY_SURCHARGE;
        }

        if ($this->isWeekend()) {
            $cost += self::WEEKEND_SURCHARGE;
        }

        $insurance = min($quote->orderTotal * self::INSURANCE_RATE, self::INSURANCE_CAP);
        $cost += $insurance;

        return round($cost, 2);
    }

    public function getEstimatedDays(ShippingQuote $quote): string
    {
        return $quote->isLocal() ? 'azi sau mâine' : '1-2 zile lucrătoare';
    }

    public function isEligible(ShippingQuote $quote): bool
    {
        return true;
    }

    private function isWeekend(): bool
    {
        $day = (int) date('N');

        return $day >= 6;
    }
}
