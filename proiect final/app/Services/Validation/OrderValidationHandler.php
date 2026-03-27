<?php

namespace App\Services\Validation;

/**
 * Chain of Responsibility Pattern — Abstract Handler.
 *
 * Each handler validates one aspect of the order.
 * If validation passes, it forwards to the next handler in the chain.
 * If it fails, the chain stops and returns the error.
 */
abstract class OrderValidationHandler
{
    private ?OrderValidationHandler $next = null;

    /**
     * Link the next handler in the chain.
     */
    public function setNext(OrderValidationHandler $handler): OrderValidationHandler
    {
        $this->next = $handler;

        return $handler;
    }

    /**
     * Run validation and pass to the next handler if successful.
     */
    public function handle(array $data): ValidationResult
    {
        $result = $this->validate($data);

        if (! $result->passed) {
            return $result;
        }

        if ($this->next) {
            return $this->next->handle($data);
        }

        return ValidationResult::pass();
    }

    /**
     * Each concrete handler implements its specific validation logic.
     */
    abstract protected function validate(array $data): ValidationResult;
}
