<?php

namespace App\Services\Validation;

class ValidationResult
{
    public function __construct(
        public readonly bool $passed,
        public readonly string $message = '',
    ) {}

    public static function pass(): self
    {
        return new self(true);
    }

    public static function fail(string $message): self
    {
        return new self(false, $message);
    }
}
