<?php

namespace App\Services\Shipping;

use App\Services\Shipping\Methods\ExpressShipping;
use App\Services\Shipping\Methods\FreeShipping;
use App\Services\Shipping\Methods\StandardShipping;

/**
 * Resolves available shipping methods and calculates costs.
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
     * Get shipping methods available for the given order total.
     */
    public function getAvailableMethods(float $orderTotal): array
    {
        return collect($this->methods)
            ->filter(function (ShippingMethod $method) use ($orderTotal) {
                // Free shipping only for orders >= 500 MDL
                if ($method->getCode() === 'free' && $orderTotal < 500) {
                    return false;
                }

                return true;
            })
            ->map(fn (ShippingMethod $method) => [
                'code' => $method->getCode(),
                'name' => $method->getName(),
                'cost' => $method->getShippingCost($orderTotal),
                'estimated_days' => $method->getDeliveryEstimate(),
            ])
            ->values()
            ->all();
    }

    /**
     * Get the shipping cost for a specific method.
     */
    public function calculateCost(string $methodCode, float $orderTotal): float
    {
        $method = $this->methods[$methodCode] ?? $this->methods['standard'];

        return $method->getShippingCost($orderTotal);
    }
}
