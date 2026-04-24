<?php

namespace App\Services\Shipping;

/**
 * Factory Method Pattern — Abstract Creator.
 *
 * Declares the factory method createCalculator() that subclasses
 * override to produce different shipping calculators.
 * The getShippingCost() / getDeliveryEstimate() methods use the
 * factory method without knowing which concrete calculator will be
 * created.
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
    public function getShippingCost(ShippingQuote $quote): float
    {
        return $this->createCalculator()->calculate($quote);
    }

    /**
     * Uses the factory method to get estimated delivery time.
     */
    public function getDeliveryEstimate(ShippingQuote $quote): string
    {
        return $this->createCalculator()->getEstimatedDays($quote);
    }

    /**
     * Uses the factory method to check eligibility.
     */
    public function isEligible(ShippingQuote $quote): bool
    {
        return $this->createCalculator()->isEligible($quote);
    }
}
