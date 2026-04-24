<?php

namespace App\Services\Cart\Commands;

use App\Models\CartItem;
use App\Services\Cart\CartCommand;
use App\Services\CartService;

/**
 * Command Pattern — Concrete Command.
 *
 * Encapsulates adding a product to the cart, together with the
 * state required to undo it on a later request (the command is
 * persisted in session between the add and the undo click).
 */
class AddToCartCommand implements CartCommand
{
    private ?int $affectedItemId = null;

    private bool $wasExisting = false;

    private int $previousQuantity = 0;

    public function __construct(
        private int $productId,
        private int $quantity = 1,
    ) {}

    public function execute(): CartItem
    {
        $service = app(CartService::class);
        $cart = $service->getCart();

        $existing = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $this->productId)
            ->first();

        if ($existing) {
            $this->wasExisting = true;
            $this->previousQuantity = $existing->quantity;
        }

        $item = $service->addItem($this->productId, $this->quantity);
        $this->affectedItemId = $item->id;

        return $item;
    }

    public function undo(): void
    {
        if (! $this->affectedItemId) {
            return;
        }

        $item = CartItem::find($this->affectedItemId);

        if (! $item) {
            return;
        }

        if ($this->wasExisting) {
            $item->update(['quantity' => $this->previousQuantity]);
        } else {
            $item->delete();
        }
    }

    public function getDescription(): string
    {
        return "Adaugă produs #{$this->productId} x{$this->quantity} în coș";
    }
}
