<?php

namespace App\Services\Cart\Commands;

use App\Models\CartItem;
use App\Services\Cart\CartCommand;
use App\Services\CartService;

/**
 * Command Pattern — Concrete Command.
 *
 * Encapsulates removing a product from the cart.
 * Stores the removed item data for undo capability.
 */
class RemoveFromCartCommand implements CartCommand
{
    private ?array $removedItemData = null;

    public function __construct(
        private CartService $cartService,
        private CartItem $cartItem,
    ) {}

    public function execute(): bool
    {
        $this->removedItemData = [
            'cart_id' => $this->cartItem->cart_id,
            'product_id' => $this->cartItem->product_id,
            'quantity' => $this->cartItem->quantity,
            'price' => $this->cartItem->price,
        ];

        $this->cartItem->delete();

        return true;
    }

    public function undo(): void
    {
        if ($this->removedItemData) {
            CartItem::create($this->removedItemData);
        }
    }

    public function getDescription(): string
    {
        return "Elimină produs din coș";
    }
}
