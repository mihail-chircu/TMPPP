<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Services\Cart\CartCommandInvoker;
use App\Services\Cart\Commands\AddToCartCommand;
use App\Services\Cart\Commands\RemoveFromCartCommand;
use App\Services\Cart\Commands\UpdateQuantityCommand;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private CartCommandInvoker $invoker,
    ) {}

    public function index(Request $request): View
    {
        $cart = $this->getCart($request);

        if ($cart) {
            $cart->load(['items.product.primaryImage', 'items.product.activeDiscount']);
        }

        $canUndo = $this->invoker->canUndo();
        $history = $this->invoker->getHistory();

        return view('frontend.cart.index', compact('cart', 'canUndo', 'history'));
    }

    /**
     * Command Pattern: add product to cart via AddToCartCommand.
     */
    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $this->getOrCreateCart($request);

        $command = new AddToCartCommand(
            $validated['product_id'],
            $validated['quantity'],
        );

        $this->invoker->execute($command);

        $cart = $this->cartService->getCart();

        return response()->json([
            'success' => true,
            'cart_count' => $cart->items()->sum('quantity'),
            'message' => 'Produs adăugat în coș!',
        ]);
    }

    /**
     * Command Pattern: update quantity via UpdateQuantityCommand.
     */
    public function update(Request $request, CartItem $item): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $command = new UpdateQuantityCommand($item->id, $validated['quantity']);
        $this->invoker->execute($command);

        $cart = $item->cart;

        return response()->json([
            'success' => true,
            'cart_count' => $cart->items()->sum('quantity'),
            'item_subtotal' => number_format($item->price * $item->fresh()->quantity, 2),
            'cart_total' => number_format($cart->items()->get()->sum(fn (CartItem $i) => $i->price * $i->quantity), 2),
        ]);
    }

    /**
     * Command Pattern: remove item via RemoveFromCartCommand.
     */
    public function remove(Request $request, CartItem $item): JsonResponse
    {
        $cart = $item->cart;

        $command = new RemoveFromCartCommand($item->id);
        $this->invoker->execute($command);

        return response()->json([
            'success' => true,
            'cart_count' => $cart->items()->sum('quantity'),
            'cart_total' => number_format($cart->items()->get()->sum(fn (CartItem $i) => $i->price * $i->quantity), 2),
        ]);
    }

    /**
     * Command Pattern: undo the last operation via the invoker.
     */
    public function undo(Request $request): RedirectResponse
    {
        $description = $this->invoker->undoLast();

        if (! $description) {
            return redirect()->route('cart.index')
                ->with('info', 'Nu există operații de anulat.');
        }

        return redirect()->route('cart.index')
            ->with('success', "Operație anulată: {$description}");
    }

    protected function getCart(Request $request): ?Cart
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->first();
        }

        return Cart::where('session_id', $request->session()->getId())->first();
    }

    protected function getOrCreateCart(Request $request): Cart
    {
        $cart = $this->getCart($request);

        if ($cart) {
            return $cart;
        }

        return Cart::create([
            'user_id' => Auth::id(),
            'session_id' => Auth::check() ? null : $request->session()->getId(),
        ]);
    }
}
