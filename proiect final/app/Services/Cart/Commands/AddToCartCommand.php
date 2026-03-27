<?php

namespace App\Services\Cart\Commands;

use App\Models\CartItem;
use App\Services\Cart\CartCommand;
use App\Services\CartService;

/**
 * Command Pattern — Concrete Command.
 *
 * Encapsulates adding a product to the cart.
 */
class AddToCartCommand implements CartCommand
{
    private ?CartItem $addedItem = null;

    private bool $wasExisting = false;

    private int $previousQuantity = 0;

    public function __construct(
        private CartService $cartService,
        private int $productId,
        private int $quantity = 1,
    ) {}

    public function execute(): CartItem
    {
        $cart = $this->cartService->getCart();
        $existing = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $this->productId)
            ->first();

        if ($existing) {
            $this->wasExisting = true;
            $this->previousQuantity = $existing->quantity;
        }

        $this->addedItem = $this->cartService->addItem($this->productId, $this->quantity);

        return $this->addedItem;
    }

    public function undo(): void
    {
        if (! $this->addedItem) {
            return;
        }

        if ($this->wasExisting) {
            $this->addedItem->update(['quantity' => $this->previousQuantity]);
        } else {
            $this->addedItem->delete();
        }
    }

    public function getDescription(): string
    {
        return "Adaugă produs #{$this->productId} x{$this->quantity} în coș";
    }
}
