<?php

namespace App\Services\Cart\Commands;

use App\Models\CartItem;
use App\Services\Cart\CartCommand;

/**
 * Command Pattern — Concrete Command.
 *
 * Encapsulates updating a cart item's quantity.
 * Stores the previous quantity for undo capability.
 */
class UpdateQuantityCommand implements CartCommand
{
    private int $previousQuantity;

    public function __construct(
        private CartItem $cartItem,
        private int $newQuantity,
    ) {
        $this->previousQuantity = $cartItem->quantity;
    }

    public function execute(): CartItem
    {
        $this->cartItem->update(['quantity' => $this->newQuantity]);

        return $this->cartItem->fresh();
    }

    public function undo(): void
    {
        $this->cartItem->update(['quantity' => $this->previousQuantity]);
    }

    public function getDescription(): string
    {
        return "Actualizează cantitate: {$this->previousQuantity} → {$this->newQuantity}";
    }
}
