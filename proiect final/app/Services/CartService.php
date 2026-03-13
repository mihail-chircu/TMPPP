<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = session()->getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    public function addItem(int $productId, int $quantity = 1): CartItem
    {
        $cart = $this->getCart();
        $product = Product::findOrFail($productId);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            $item->update([
                'quantity' => $item->quantity + $quantity,
                'price' => $product->current_price,
            ]);
        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->current_price,
            ]);
        }

        return $item;
    }

    public function mergeGuestCart(): void
    {
        if (!Auth::check()) return;

        $sessionId = session()->getId();
        $guestCart = Cart::where('session_id', $sessionId)->first();
        if (!$guestCart) return;

        $userCart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        foreach ($guestCart->items as $guestItem) {
            $existingItem = $userCart->items()->where('product_id', $guestItem->product_id)->first();
            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $guestItem->quantity,
                ]);
            } else {
                $guestItem->update(['cart_id' => $userCart->id]);
            }
        }

        $guestCart->delete();
    }

    public function getItemCount(): int
    {
        try {
            $cart = $this->getCart();
            return $cart->items()->sum('quantity');
        } catch (\Exception $e) {
            return 0;
        }
    }
}
