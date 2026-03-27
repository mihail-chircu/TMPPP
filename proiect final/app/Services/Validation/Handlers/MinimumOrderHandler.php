<?php

namespace App\Services\Validation\Handlers;

use App\Services\Validation\OrderValidationHandler;
use App\Services\Validation\ValidationResult;

/**
 * Chain of Responsibility — Concrete Handler.
 *
 * Validates that the order meets the minimum order amount.
 */
class MinimumOrderHandler extends OrderValidationHandler
{
    private const MINIMUM_ORDER = 50.00;

    protected function validate(array $data): ValidationResult
    {
        $subtotal = $data['cart_items']->sum(fn ($item) => $item->price * $item->quantity);

        if ($subtotal < self::MINIMUM_ORDER) {
            return ValidationResult::fail(
                "Suma minimă a comenzii este " . self::MINIMUM_ORDER . " lei. "
                . "Totalul curent: {$subtotal} lei."
            );
        }

        return ValidationResult::pass();
    }
}
