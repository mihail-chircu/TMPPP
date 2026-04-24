<?php

namespace App\Services\Shipping;

use App\Services\Shipping\Methods\ExpressShipping;
use App\Services\Shipping\Methods\FreeShipping;
use App\Services\Shipping\Methods\StandardShipping;

/**
 * Resolves available shipping methods and calculates costs.
 *
 * Relies on the Factory Method pattern exposed by ShippingMethod —
 * each concrete method produces its own ShippingCalculator.
 */
class ShippingService
{
    /** @var ShippingMethod[] */
    private array $methods;

    public function __construct()
    {
        $this->methods = [
            'standard' => new StandardShipping(),
            'express' => new ExpressShipping(),
            'free' => new FreeShipping(),
        ];
    }

    /**
     * Get shipping methods available for the given quote.
     */
    public function getAvailableMethods(ShippingQuote $quote): array
    {
        return collect($this->methods)
            ->filter(fn (ShippingMethod $method) => $method->isEligible($quote))
            ->map(fn (ShippingMethod $method) => [
                'code' => $method->getCode(),
                'name' => $method->getName(),
                'cost' => $method->getShippingCost($quote),
                'estimated_days' => $method->getDeliveryEstimate($quote),
            ])
            ->values()
            ->all();
    }

    /**
     * Get the shipping cost for a specific method and quote.
     */
    public function calculateCost(string $methodCode, ShippingQuote $quote): float
    {
        $method = $this->methods[$methodCode] ?? $this->methods['standard'];

        return $method->getShippingCost($quote);
    }
}
