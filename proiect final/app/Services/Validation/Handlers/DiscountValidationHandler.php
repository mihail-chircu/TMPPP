<?php

namespace App\Services\Validation\Handlers;

use App\Services\Validation\OrderValidationHandler;
use App\Services\Validation\ValidationResult;

/**
 * Chain of Responsibility — Concrete Handler.
 *
 * Validates that every cart item's stored price still matches the real
 * current price (product price or active discount). A discount may have
 * expired between the moment the item was added to cart and checkout,
 * in which case the order must be refused to prevent charging an
 * out-of-date price.
 */
class DiscountValidationHandler extends OrderValidationHandler
{
    protected function validate(array $data): ValidationResult
    {
        foreach ($data['cart_items'] as $item) {
            $product = $item->product;

            if (! $product) {
                continue;
            }

            $activeDiscount = $product->activeDiscount;
            $currentPrice = $activeDiscount
                ? (float) $activeDiscount->discounted_price
                : (float) $product->price;

            if (abs($currentPrice - (float) $item->price) > 0.01) {
                return ValidationResult::fail(
                    "Prețul pentru «{$product->name}» s-a schimbat "
                    . "({$item->price} lei → {$currentPrice} lei). "
                    . 'Reîmprospătează coșul și încearcă din nou.'
                );
            }
        }

        return ValidationResult::pass();
    }
}
