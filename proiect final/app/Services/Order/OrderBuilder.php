<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Product;
use App\Services\Pricing\BasePrice;
use App\Services\Pricing\GiftWrapDecorator;
use App\Services\Pricing\PriceCalculator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Builder Pattern: construiește un obiect Order complex pas cu pas,
 * separând logica de asamblare de reprezentarea finală.
 */
class OrderBuilder
{
    private ?int $userId = null;

    private string $customerName;

    private string $customerEmail;

    private ?string $customerPhone = null;

    private string $shippingAddress;

    private string $shippingCity;

    private ?string $shippingPostalCode = null;

    private ?string $notes = null;

    private string $paymentMethod;

    private bool $giftWrap = false;

    private string $shippingMethod = 'standard';

    private float $shippingCost = 35.00;

    private Collection $cartItems;

    public function forUser(?int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function withCustomer(string $name, string $email, ?string $phone = null): static
    {
        $this->customerName = $name;
        $this->customerEmail = $email;
        $this->customerPhone = $phone;

        return $this;
    }

    public function withShipping(string $address, string $city, ?string $postalCode = null): static
    {
        $this->shippingAddress = $address;
        $this->shippingCity = $city;
        $this->shippingPostalCode = $postalCode;

        return $this;
    }

    public function withNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function withPaymentMethod(string $method): static
    {
        $this->paymentMethod = $method;

        return $this;
    }

    public function withShippingMethod(string $method, float $cost): static
    {
        $this->shippingMethod = $method;
        $this->shippingCost = $cost;

        return $this;
    }

    public function withGiftWrap(bool $giftWrap): static
    {
        $this->giftWrap = $giftWrap;

        return $this;
    }

    public function withCartItems(Collection $items): static
    {
        $this->cartItems = $items;

        return $this;
    }

    public function build(): Order
    {
        return DB::transaction(function () {
            $subtotal = $this->cartItems->sum(fn ($item) => $item->price * $item->quantity);

            $total = $this->buildPrice($subtotal)->calculate() + $this->shippingCost;

            $order = Order::create([
                'user_id'              => $this->userId,
                'status'               => 'pending',
                'subtotal'             => $subtotal,
                'total'                => $total,
                'shipping_method'      => $this->shippingMethod,
                'shipping_cost'        => $this->shippingCost,
                'customer_name'        => $this->customerName,
                'customer_email'       => $this->customerEmail,
                'customer_phone'       => $this->customerPhone,
                'shipping_address'     => $this->shippingAddress,
                'shipping_city'        => $this->shippingCity,
                'shipping_postal_code' => $this->shippingPostalCode,
                'notes'                => $this->notes,
                'payment_method'       => $this->paymentMethod,
            ]);

            foreach ($this->cartItems as $item) {
                $order->items()->create([
                    'product_id'     => $item->product_id,
                    'product_name'   => $item->product->name,
                    'price'          => $item->product->price,
                    'discount_price' => $item->price < $item->product->price ? $item->price : null,
                    'quantity'       => $item->quantity,
                    'total'          => $item->price * $item->quantity,
                ]);

                Product::where('id', $item->product_id)
                    ->where('stock', '>=', $item->quantity)
                    ->decrement('stock', $item->quantity);

                Product::where('id', $item->product_id)
                    ->increment('sales_count', $item->quantity);
            }

            return $order;
        });
    }

    private function buildPrice(float $subtotal): PriceCalculator
    {
        $price = new BasePrice($subtotal);

        if ($this->giftWrap) {
            $price = new GiftWrapDecorator($price);
        }

        return $price;
    }
}
