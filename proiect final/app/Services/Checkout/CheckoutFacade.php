<?php

namespace App\Services\Checkout;

use App\Events\OrderPlaced;
use App\Models\Cart;
use App\Models\Order;
use App\Services\Order\OrderBuilder;
use App\Services\Payment\PaymentService;
use App\Services\Shipping\ShippingService;
use App\Services\Validation\OrderValidationPipeline;
use App\Services\Validation\ValidationResult;

/**
 * Facade Pattern.
 *
 * Provides a simplified interface to the complex checkout subsystem.
 * Orchestrates: Validation (Chain of Responsibility), Order Builder,
 * Payment (Adapter), Shipping (Factory Method), and Events (Observer).
 *
 * The CheckoutController interacts only with this facade,
 * without knowing about the internal subsystem components.
 */
class CheckoutFacade
{
    public function __construct(
        private OrderBuilder $orderBuilder,
        private PaymentService $paymentService,
        private ShippingService $shippingService,
        private OrderValidationPipeline $validationPipeline,
    ) {}

    /**
     * Process the entire checkout in one call.
     *
     * @return array{success: bool, order?: Order, error?: string}
     */
    public function processCheckout(array $data, Cart $cart): array
    {
        $cart->load(['items.product']);

        // Chain of Responsibility: validate through the handler chain
        $validation = $this->validationPipeline->validate([
            'cart_items' => $cart->items,
            'shipping_address' => $data['shipping_address'],
            'shipping_city' => $data['shipping_city'],
        ]);

        if (! $validation->passed) {
            return ['success' => false, 'error' => $validation->message];
        }

        // Factory Method: calculate shipping cost
        $subtotal = $cart->items->sum(fn ($item) => $item->price * $item->quantity);
        $shippingCost = $this->shippingService->calculateCost(
            $data['shipping_method'] ?? 'standard',
            $subtotal,
        );

        // Builder Pattern: construct the order step by step
        $order = $this->orderBuilder
            ->forUser($data['user_id'] ?? null)
            ->withCustomer(
                $data['customer_name'],
                $data['customer_email'],
                $data['customer_phone'] ?? null,
            )
            ->withShipping(
                $data['shipping_address'],
                $data['shipping_city'],
                $data['shipping_postal_code'] ?? null,
            )
            ->withNotes($data['notes'] ?? null)
            ->withPaymentMethod($data['payment_method'])
            ->withShippingMethod($data['shipping_method'] ?? 'standard', $shippingCost)
            ->withGiftWrap($data['gift_wrap'] ?? false)
            ->withCartItems($cart->items)
            ->build();

        // Clear cart
        $cart->items()->delete();
        $cart->delete();

        // Adapter Pattern: process payment
        $this->paymentService->process($order, $data['payment_method']);

        // Observer Pattern: dispatch event for listeners
        event(new OrderPlaced($order));

        return ['success' => true, 'order' => $order];
    }
}
