@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">

        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[['label' => 'Coș']]" />

        <h1 class="section-title mb-8">Coșul de Cumpărături</h1>

        @if (!$cart || $cart->items->isEmpty())

            {{-- Empty Cart --}}
            <x-empty-state
                icon="cart"
                title="Coșul tău este gol"
                message="Se pare că nu ai adăugat nicio jucărie încă. Explorează catalogul nostru pentru a găsi ceva special!"
                :actionUrl="route('catalog.index')"
                actionLabel="Explorează jucării"
            />

        @else

            <div class="flex flex-col lg:flex-row gap-8 lg:gap-10 items-start">

                {{-- ====================================================== --}}
                {{-- Cart Items                                             --}}
                {{-- ====================================================== --}}
                <div class="flex-1 min-w-0 lg:w-2/3">
                    <div class="space-y-4">
                        @foreach ($cart->items as $item)
                            <div id="cart-item-{{ $item->id }}" class="group bg-white rounded-2xl border border-kinder-brown-100/40 p-4 md:p-5 transition-shadow hover:shadow-soft">
                                <div class="flex gap-4 md:gap-6">

                                    {{-- Product Image --}}
                                    <a href="{{ route('product.show', $item->product->slug) }}" class="flex-shrink-0">
                                        <div class="w-24 h-24 md:w-28 md:h-28 rounded-2xl overflow-hidden bg-kinder-brown-50/50">
                                            @if ($item->product->primary_image_url)
                                                <img src="{{ asset('storage/' . $item->product->primary_image_url) }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain p-1">
                                            @else
                                                <img src="{{ asset('images/placeholder-robot.svg') }}" alt="Imaginea lipseste" class="w-full h-full object-contain p-3 opacity-40">
                                            @endif
                                        </div>
                                    </a>

                                    {{-- Product Details --}}
                                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <a href="{{ route('product.show', $item->product->slug) }}" class="font-display font-bold text-kinder-brown-800 group-hover:text-kinder-600 transition-colors line-clamp-2 text-sm md:text-base">
                                                        {{ $item->product->name }}
                                                    </a>
                                                    @if ($item->product->is_on_sale)
                                                        <span class="badge-sale text-2xs mt-1 inline-block">REDUCERE</span>
                                                    @endif
                                                </div>

                                                {{-- Remove Button --}}
                                                <button
                                                    onclick="removeCartItem({{ $item->id }})"
                                                    class="flex-shrink-0 p-1.5 rounded-lg text-kinder-brown-400 hover:text-kinder-500 hover:bg-kinder-brown-50 transition-all"
                                                    title="Elimină"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>

                                            {{-- Unit Price --}}
                                            <p class="text-sm text-kinder-brown-500 mt-1">
                                                {{ number_format($item->price, 0) }} lei / buc
                                            </p>
                                        </div>

                                        {{-- Bottom Row: Quantity + Subtotal --}}
                                        <div class="flex items-center justify-between mt-3">
                                            {{-- Quantity Selector --}}
                                            <div class="quantity-selector inline-flex items-center bg-kinder-brown-50/80 rounded-xl border border-kinder-brown-100 overflow-hidden">
                                                <button
                                                    type="button"
                                                    data-action="minus"
                                                    class="w-10 h-10 flex items-center justify-center text-kinder-brown-500 hover:bg-kinder-brown-100 hover:text-kinder-brown-700 transition-colors text-sm font-medium"
                                                >
                                                    &minus;
                                                </button>
                                                <input
                                                    type="number"
                                                    value="{{ $item->quantity }}"
                                                    min="1"
                                                    max="99"
                                                    class="w-10 h-10 text-center bg-transparent border-0 text-sm font-display font-bold text-kinder-brown-800 focus:outline-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                    onchange="updateCartItem({{ $item->id }}, this.value)"
                                                >
                                                <button
                                                    type="button"
                                                    data-action="plus"
                                                    class="w-10 h-10 flex items-center justify-center text-kinder-brown-500 hover:bg-kinder-brown-100 hover:text-kinder-brown-700 transition-colors text-sm font-medium"
                                                >
                                                    +
                                                </button>
                                            </div>

                                            {{-- Line Subtotal --}}
                                            <span class="text-lg font-display font-bold text-kinder-brown-800">
                                                {{ number_format($item->subtotal, 0) }} lei
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ====================================================== --}}
                {{-- Cart Summary                                           --}}
                {{-- ====================================================== --}}
                <div class="w-full lg:w-1/3 flex-shrink-0">
                    <div class="sticky top-28 bg-kinder-brown-50 rounded-3xl p-6 md:p-8">
                        <h3 class="font-display text-lg font-bold text-kinder-brown-800 mb-6">Sumar Comandă</h3>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-kinder-brown-500">Subtotal ({{ $cart->item_count }} {{ $cart->item_count == 1 ? 'produs' : 'produse' }})</span>
                                <span id="cart-subtotal" class="font-semibold text-kinder-brown-700">{{ number_format($cart->total, 0) }} lei</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-kinder-brown-500">Livrare</span>
                                <span class="font-semibold {{ $cart->total >= 500 ? 'text-green-600' : 'text-kinder-brown-500' }}">
                                    {{ $cart->total >= 500 ? 'Gratuită' : 'Se calculează' }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-kinder-brown-200/60 pt-5 mb-6">
                            <div class="flex justify-between items-baseline">
                                <span class="font-display font-bold text-kinder-brown-700">Total</span>
                                <span id="cart-total" class="text-2xl font-display font-bold text-kinder-brown-800">{{ number_format($cart->total, 0) }} lei</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <a href="{{ route('checkout.index') }}" class="btn-primary w-full text-center text-base py-4 rounded-2xl block">
                                Finalizează comanda
                            </a>
                            <a href="{{ route('catalog.index') }}" class="btn-ghost w-full text-center block">
                                Continuă cumpărăturile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        @endif

    </div>

    @push('scripts')
        <script>
            function updateCartItem(itemId, quantity) {
                quantity = Math.max(1, Math.min(99, parseInt(quantity) || 1));
                fetch('/cart/update/' + itemId, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ quantity: quantity }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
            }

            function removeCartItem(itemId) {
                fetch('/cart/remove/' + itemId, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
            }
        </script>
    @endpush

@endsection
