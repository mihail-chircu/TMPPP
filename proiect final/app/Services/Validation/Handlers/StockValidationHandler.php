<?php

namespace App\Services\Validation\Handlers;

use App\Services\Validation\OrderValidationHandler;
use App\Services\Validation\ValidationResult;

/**
 * Chain of Responsibility — Concrete Handler.
 *
 * Validates that all cart items have sufficient stock.
 */
class StockValidationHandler extends OrderValidationHandler
{
    protected function validate(array $data): ValidationResult
    {
        foreach ($data['cart_items'] as $item) {
            if ($item->product->stock < $item->quantity) {
                return ValidationResult::fail(
                    "Stoc insuficient pentru «{$item->product->name}». "
                    . "Disponibil: {$item->product->stock}, solicitat: {$item->quantity}."
                );
            }
        }

        return ValidationResult::pass();
    }
}
