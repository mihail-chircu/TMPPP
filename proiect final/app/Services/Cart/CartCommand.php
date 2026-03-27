<?php

namespace App\Services\Cart;

/**
 * Command Pattern — Command Interface.
 *
 * Encapsulates a cart operation as an object,
 * enabling execute/undo and operation history.
 */
interface CartCommand
{
    public function execute(): mixed;

    public function undo(): void;

    public function getDescription(): string;
}
