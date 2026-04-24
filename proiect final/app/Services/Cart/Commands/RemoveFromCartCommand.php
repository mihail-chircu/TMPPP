<?php

namespace App\Services\Cart\Commands;

use App\Models\CartItem;
use App\Services\Cart\CartCommand;

/**
 * Command Pattern — Concrete Command.
 *
 * Encapsulates removing a product from the cart. Snapshots the
 * removed row so the delete can be reversed on a later request.
 */
class RemoveFromCartCommand implements CartCommand
{
    private ?array $removedItemData = null;

    public function __construct(
        private int $cartItemId,
    ) {}

    public function execute(): bool
    {
        $item = CartItem::find($this->cartItemId);

        if (! $item) {
            return false;
        }

        $this->removedItemData = [
            'cart_id' => $item->cart_id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->price,
        ];

        $item->delete();

        return true;
    }

    public function undo(): void
    {
        if (! $this->removedItemData) {
            return;
        }

        $cartExists = \App\Models\Cart::whereKey($this->removedItemData['cart_id'])->exists();
        $productExists = \App\Models\Product::whereKey($this->removedItemData['product_id'])->exists();

        if (! $cartExists || ! $productExists) {
            return;
        }

        CartItem::create($this->removedItemData);
    }

    public function getDescription(): string
    {
        return 'Elimină produs din coș';
    }
}
