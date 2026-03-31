<?php

namespace App\Services\Shipping;

/**
 * Factory Method Pattern — Product Interface.
 *
 * The object created by the factory method.
 * Each calculator knows how to compute shipping cost.
 */
interface ShippingCalculator
{
    /**
     * Calculate shipping cost based on order total.
     */
    public function calculate(float $orderTotal): float;

    /**
     * Estimated delivery time.
     */
    public function getEstimatedDays(): string;
}
