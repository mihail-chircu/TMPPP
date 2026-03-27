<?php

namespace App\Services\Validation\Handlers;

use App\Services\Validation\OrderValidationHandler;
use App\Services\Validation\ValidationResult;

/**
 * Chain of Responsibility — Concrete Handler.
 *
 * Validates that the shipping address is complete.
 */
class ShippingValidationHandler extends OrderValidationHandler
{
    protected function validate(array $data): ValidationResult
    {
        if (empty($data['shipping_address'])) {
            return ValidationResult::fail('Adresa de livrare este obligatorie.');
        }

        if (empty($data['shipping_city'])) {
            return ValidationResult::fail('Orașul de livrare este obligatoriu.');
        }

        if (strlen($data['shipping_address']) < 10) {
            return ValidationResult::fail('Adresa de livrare trebuie să conțină cel puțin 10 caractere.');
        }

        return ValidationResult::pass();
    }
}
