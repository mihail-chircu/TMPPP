<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Services\Checkout\CheckoutFacade;
use App\Services\Payment\PaymentService;
use App\Services\Shipping\ShippingQuote;
use App\Services\Shipping\ShippingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CheckoutFacade $checkoutFacade,
        private PaymentService $paymentService,
        private ShippingService $shippingService,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $cart = $this->getCart($request);

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('warning', __('Your cart is empty.'));
        }

        $cart->load(['items.product.primaryImage', 'items.product.activeDiscount']);

        // Adapter Pattern: pass available payment methods to view
        $paymentMethods = $this->paymentService->getAvailableMethods();

        // Factory Method Pattern: get shipping methods with calculated costs
        $subtotal = $cart->items->sum(fn ($item) => $item->price * $item->quantity);
        $itemCount = $cart->items->sum('quantity');

        $defaultCity = $request->input('shipping_city')
            ?? Auth::user()?->city
            ?? 'Chișinău';

        $quote = new ShippingQuote(
            orderTotal: (float) $subtotal,
            itemCount: (int) $itemCount,
            destinationCity: $defaultCity,
        );

        $shippingMethods = $this->shippingService->getAvailableMethods($quote);

        return view('frontend.checkout.index', compact('cart', 'paymentMethods', 'shippingMethods'));
    }

    /**
     * Facade Pattern: the entire checkout process is delegated
     * to CheckoutFacade, which orchestrates all subsystems.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_city' => ['required', 'string', 'max:255'],
            'shipping_postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'gift_wrap' => ['nullable', 'boolean'],
            'payment_method' => ['required', 'string', 'in:cash_on_delivery,bank_transfer,card'],
            'shipping_method' => ['required', 'string', 'in:standard,express,free'],
        ]);

        $cart = $this->getCart($request);

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('warning', __('Your cart is empty.'));
        }

        $validated['user_id'] = Auth::id();
        $validated['gift_wrap'] = $request->boolean('gift_wrap', false);

        // Facade Pattern: one call handles validation, order creation,
        // payment, shipping, notification, and cart cleanup
        $result = $this->checkoutFacade->processCheckout($validated, $cart);

        if (! $result['success']) {
            return back()->withInput()->with('error', $result['error']);
        }

        return redirect()->route('checkout.confirmation', $result['order'])
            ->with('success', __('Your order has been placed successfully!'));
    }

    public function confirmation(Order $order): View
    {
        $order->load(['items.product']);

        return view('frontend.checkout.confirmation', compact('order'));
    }

    protected function getCart(Request $request): ?Cart
    {
        if (Auth::check()) {
            return Cart::with('items')->where('user_id', Auth::id())->first();
        }

        return Cart::with('items')->where('session_id', $request->session()->getId())->first();
    }
}
