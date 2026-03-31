<?php

namespace App\Services\Shipping;

/**
 * Factory Method Pattern — Abstract Creator.
 *
 * Declares the factory method createCalculator() that subclasses
 * override to produce different shipping calculators.
 * The getShippingCost() method uses the factory method
 * without knowing which concrete calculator will be created.
 */
abstract class ShippingMethod
{
    /**
     * Factory Method: subclasses decide which calculator to create.
     */
    abstract public function createCalculator(): ShippingCalculator;

    abstract public function getName(): string;

    abstract public function getCode(): string;

    /**
     * Uses the factory method to get the calculator and compute cost.
     */
    public function getShippingCost(float $orderTotal): float
    {
        $calculator = $this->createCalculator();

        return $calculator->calculate($orderTotal);
    }

    /**
     * Uses the factory method to get estimated delivery time.
     */
    public function getDeliveryEstimate(): string
    {
        $calculator = $this->createCalculator();

        return $calculator->getEstimatedDays();
    }
}
