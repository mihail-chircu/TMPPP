<?php

namespace App\Services\Shipping;

/**
 * Factory Method Pattern — Product Interface.
 *
 * The object created by the factory method.
 * Each calculator knows how to compute shipping cost based on
 * order total, item count and destination city.
 */
interface ShippingCalculator
{
    /**
     * Calculate shipping cost for the given quote.
     */
    public function calculate(ShippingQuote $quote): float;

    /**
     * Estimated delivery time for the given destination.
     */
    public function getEstimatedDays(ShippingQuote $quote): string;

    /**
     * Whether this calculator can service the given quote.
     * Used by the ShippingService to filter methods the customer
     * is not eligible for (e.g. free shipping under threshold).
     */
    public function isEligible(ShippingQuote $quote): bool;
}
