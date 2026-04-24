<?php

namespace App\Services\Validation;

use App\Services\Validation\Handlers\DiscountValidationHandler;
use App\Services\Validation\Handlers\MinimumOrderHandler;
use App\Services\Validation\Handlers\ShippingValidationHandler;
use App\Services\Validation\Handlers\StockValidationHandler;

/**
 * Chain of Responsibility Pattern — Pipeline Builder.
 *
 * Assembles the chain of validation handlers and runs
 * the data through the entire chain.
 *
 * Chain: Stock → Discount → MinimumOrder → Shipping
 */
class OrderValidationPipeline
{
    /**
     * Build and execute the validation chain.
     */
    public function validate(array $data): ValidationResult
    {
        // Build the chain
        $stock = new StockValidationHandler();
        $discount = new DiscountValidationHandler();
        $minimum = new MinimumOrderHandler();
        $shipping = new ShippingValidationHandler();

        $stock->setNext($discount)->setNext($minimum)->setNext($shipping);

        // Start the chain
        return $stock->handle($data);
    }
}
