<?php

namespace App\Services\Cart\Commands;

use App\Models\CartItem;
use App\Services\Cart\CartCommand;

/**
 * Command Pattern — Concrete Command.
 *
 * Encapsulates updating a cart item's quantity and remembers the
 * previous quantity so the change can be undone on a later request.
 */
class UpdateQuantityCommand implements CartCommand
{
    private int $previousQuantity = 0;

    public function __construct(
        private int $cartItemId,
        private int $newQuantity,
    ) {}

    public function execute(): ?CartItem
    {
        $item = CartItem::find($this->cartItemId);

        if (! $item) {
            return null;
        }

        $this->previousQuantity = $item->quantity;
        $item->update(['quantity' => $this->newQuantity]);

        return $item->fresh();
    }

    public function undo(): void
    {
        $item = CartItem::find($this->cartItemId);

        if ($item) {
            $item->update(['quantity' => $this->previousQuantity]);
        }
    }

    public function getDescription(): string
    {
        return "Actualizează cantitate: {$this->previousQuantity} → {$this->newQuantity}";
    }
}
