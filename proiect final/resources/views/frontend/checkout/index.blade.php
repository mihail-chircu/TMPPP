@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">

        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[
            ['label' => 'Coș', 'url' => route('cart.index')],
            ['label' => 'Finalizare comandă'],
        ]" />

        {{-- ====================================================== --}}
        {{-- Steps Indicator                                         --}}
        {{-- ====================================================== --}}
        <div class="mb-10 md:mb-14">
            <div class="flex items-center justify-center gap-3">
                {{-- Step 1: Coș (completed) --}}
                <div class="flex items-center gap-2">
                    <span class="w-9 h-9 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-bold shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <span class="text-sm font-semibold text-green-600 hidden sm:inline">Coș</span>
                </div>

                {{-- Line --}}
                <div class="w-8 md:w-14 h-0.5 bg-green-400 rounded-full"></div>

                {{-- Step 2: Livrare (active) --}}
                <div class="flex items-center gap-2">
                    <span class="w-9 h-9 rounded-full bg-kinder-500 text-white flex items-center justify-center text-sm font-bold shadow-sm ring-4 ring-kinder-500/20">2</span>
                    <span class="text-sm font-semibold text-kinder-500 hidden sm:inline">Livrare</span>
                </div>

                {{-- Line --}}
                <div class="w-8 md:w-14 h-0.5 bg-kinder-brown-200 rounded-full"></div>

                {{-- Step 3: Plată (upcoming) --}}
                <div class="flex items-center gap-2">
                    <span class="w-9 h-9 rounded-full bg-kinder-brown-100 text-kinder-brown-400 flex items-center justify-center text-sm font-bold">3</span>
                    <span class="text-sm font-medium text-kinder-brown-400 hidden sm:inline">Plată</span>
                </div>

                {{-- Line --}}
                <div class="w-8 md:w-14 h-0.5 bg-kinder-brown-200 rounded-full"></div>

                {{-- Step 4: Confirmare (upcoming) --}}
                <div class="flex items-center gap-2">
                    <span class="w-9 h-9 rounded-full bg-kinder-brown-100 text-kinder-brown-400 flex items-center justify-center text-sm font-bold">4</span>
                    <span class="text-sm font-medium text-kinder-brown-400 hidden sm:inline">Confirmare</span>
                </div>
            </div>
        </div>

        <h1 class="section-title mb-8 md:mb-10">Finalizare Comandă</h1>

        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf

            <div class="flex flex-col lg:flex-row gap-8 lg:gap-10">

                {{-- ====================================================== --}}
                {{-- Left Column: Checkout Form                             --}}
                {{-- ====================================================== --}}
                <div class="flex-1 min-w-0 space-y-6 md:space-y-8">

                    {{-- Customer Information --}}
                    <div class="bg-white rounded-3xl border border-kinder-brown-100/40 shadow-soft p-6 md:p-8">
                        <h2 class="font-display text-lg font-bold text-kinder-brown-800 mb-6 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-kinder-500 text-white flex items-center justify-center text-sm font-bold">1</span>
                            Informații Client
                        </h2>

                        <div class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="customer_name" class="block text-xs font-semibold text-kinder-brown-600 uppercase tracking-wider mb-2">Nume complet *</label>
                                    <input
                                        type="text"
                                        id="customer_name"
                                        name="customer_name"
                                        value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                        class="input-field"
                                        placeholder="Numele și prenumele"
                                        required
                                    >
                                    @error('customer_name')
                                        <p class="mt-1.5 text-xs text-kinder-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_email" class="block text-xs font-semibold text-kinder-brown-600 uppercase tracking-wider mb-2">Email *</label>
                                    <input
                                        type="email"
                                        id="customer_email"
                                        name="customer_email"
                                        value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                        class="input-field"
                                        placeholder="email@exemplu.com"
                                        required
                                    >
                                    @error('customer_email')
                                        <p class="mt-1.5 text-xs text-kinder-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-xs font-semibold text-kinder-brown-600 uppercase tracking-wider mb-2">Telefon</label>
                                <input
                                    type="tel"
                                    id="customer_phone"
                                    name="customer_phone"
                                    value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                                    class="input-field"
                                    placeholder="+373 XX XXX XXX"
                                >
                                @error('customer_phone')
                                    <p class="mt-1.5 text-xs text-kinder-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Shipping Address --}}
                    <div class="bg-white rounded-3xl border border-kinder-brown-100/40 shadow-soft p-6 md:p-8">
                        <h2 class="font-display text-lg font-bold text-kinder-brown-800 mb-6 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-kinder-500 text-white flex items-center justify-center text-sm font-bold">2</span>
                            Adresa de Livrare
                        </h2>

                        <div class="space-y-5">
                            <div>
                                <label for="shipping_address" class="block text-xs font-semibold text-kinder-brown-600 uppercase tracking-wider mb-2">Adresa *</label>
                                <textarea
                                    id="shipping_address"
                                    name="shipping_address"
                                    rows="2"
                                    class="input-field resize-none"
                                    placeholder="Strada, numărul, apartamentul"
                                    required
                                >{{ old('shipping_address', auth()->user()->address ?? '') }}</textarea>
                                @error('shipping_address')
                                    <p class="mt-1.5 text-xs text-kinder-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="shipping_city" class="block text-xs font-semibold text-kinder-brown-600 uppercase tracking-wider mb-2">Orașul *</label>
                                    <input
                                        type="text"
                                        id="shipping_city"
                                        name="shipping_city"
                                        value="{{ old('shipping_city', auth()->user()->city ?? '') }}"
                                        class="input-field"
                                        placeholder="ex. Chișinău"
                                        required
                                    >
                                    @error('shipping_city')
                                        <p class="mt-1.5 text-xs text-kinder-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="shipping_postal_code" class="block text-xs font-semibold text-kinder-brown-600 uppercase tracking-wider mb-2">Cod poștal</label>
                                    <input
                                        type="text"
                                        id="shipping_postal_code"
                                        name="shipping_postal_code"
                                        value="{{ old('shipping_postal_code', auth()->user()->postal_code ?? '') }}"
                                        class="input-field"
                                        placeholder="ex. MD-2001"
                                    >
                                    @error('shipping_postal_code')
                                        <p class="mt-1.5 text-xs text-kinder-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Factory Method Pattern: Shipping Methods --}}
                    <div class="bg-white rounded-3xl border border-kinder-brown-100/40 shadow-soft p-6 md:p-8">
                        <h2 class="font-display text-lg font-bold text-kinder-brown-800 mb-6 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-kinder-500 text-white flex items-center justify-center text-sm font-bold">3</span>
                            Metoda de Livrare
                        </h2>

                        <div class="space-y-3">
                            @foreach ($shippingMethods as $method)
                                <label class="group flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200
                                    {{ $loop->first ? 'bg-kinder-50/50 border-kinder-500/40 shadow-sm' : 'bg-white border-kinder-brown-100/60 hover:border-kinder-500/30 hover:shadow-sm' }}">
                                    <input type="radio" name="shipping_method" value="{{ $method['code'] }}" {{ $loop->first ? 'checked' : '' }} class="text-kinder-500 focus:ring-kinder-400 w-4 h-4">

                                    {{-- Shipping truck icon --}}
                                    <div class="w-10 h-10 rounded-xl {{ $method['cost'] == 0 ? 'bg-green-50 text-green-500' : 'bg-kinder-brown-50 text-kinder-brown-400' }} flex items-center justify-center flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                        </svg>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <span class="font-display font-semibold text-sm text-kinder-brown-800 block">{{ $method['name'] }}</span>
                                        <span class="text-sm text-kinder-brown-400 mt-0.5 block">{{ $method['estimated_days'] }}</span>
                                    </div>

                                    <div class="flex-shrink-0 text-right">
                                        @if ($method['cost'] == 0)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-50 text-green-600 text-sm font-bold">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Gratuită
                                            </span>
                                        @else
                                            <span class="font-display font-bold text-sm text-kinder-brown-700">{{ number_format($method['cost'], 2) }} lei</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('shipping_method')
                            <p class="mt-2 text-xs text-kinder-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Adapter Pattern: Payment Methods --}}
                    <div class="bg-white rounded-3xl border border-kinder-brown-100/40 shadow-soft p-6 md:p-8">
                        <h2 class="font-display text-lg font-bold text-kinder-brown-800 mb-6 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-kinder-500 text-white flex items-center justify-center text-sm font-bold">4</span>
                            Metoda de Plată
                        </h2>

                        <div class="space-y-3">
                            @foreach ($paymentMethods as $method)
                                <label class="group flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200
                                    {{ $loop->first ? 'bg-kinder-50/50 border-kinder-500/40 shadow-sm' : 'bg-white border-kinder-brown-100/60 hover:border-kinder-500/30 hover:shadow-sm' }}">
                                    <input type="radio" name="payment_method" value="{{ $method['code'] }}" {{ $loop->first ? 'checked' : '' }} class="text-kinder-500 focus:ring-kinder-400 w-4 h-4">

                                    {{-- Payment icon based on method code --}}
                                    <div class="w-10 h-10 rounded-xl bg-kinder-brown-50 text-kinder-brown-500 flex items-center justify-center flex-shrink-0">
                                        @if (str_contains($method['code'], 'cash') || str_contains($method['code'], 'numerar'))
                                            {{-- Cash / Banknote icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                            </svg>
                                        @elseif (str_contains($method['code'], 'bank') || str_contains($method['code'], 'transfer'))
                                            {{-- Bank / Building icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                                            </svg>
                                        @elseif (str_contains($method['code'], 'card'))
                                            {{-- Credit card icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                            </svg>
                                        @else
                                            {{-- Default wallet icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 110-6h5.25A2.25 2.25 0 0121 6v-1.5a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 4.5v15a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 19.5v-1.5a2.25 2.25 0 00-2.25-2.25H15a3 3 0 110-6h3.75A2.25 2.25 0 0021 12z" />
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <span class="font-display font-semibold text-sm text-kinder-brown-800 block">{{ $method['name'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('payment_method')
                            <p class="mt-2 text-xs text-kinder-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Decorator Pattern: Gift Wrap + Notes --}}
                    <div class="bg-white rounded-3xl border border-kinder-brown-100/40 shadow-soft p-6 md:p-8">
                        <h2 class="font-display text-lg font-bold text-kinder-brown-800 mb-6 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-kinder-500 text-white flex items-center justify-center text-sm font-bold">5</span>
                            Opțiuni Suplimentare
                        </h2>

                        <div class="space-y-5">
                            {{-- Gift Wrap --}}
                            <label class="flex items-center gap-4 p-4 rounded-2xl border-2 border-kinder-brown-100/60 cursor-pointer hover:border-kinder-500/30 hover:shadow-sm transition-all duration-200 bg-gradient-to-r from-white to-kinder-50/30">
                                <input type="checkbox" name="gift_wrap" value="1" {{ old('gift_wrap') ? 'checked' : '' }} class="rounded-lg text-kinder-500 focus:ring-kinder-400 w-5 h-5">

                                <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-400 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                    </svg>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <span class="font-display font-semibold text-sm text-kinder-brown-800 block">Ambalare cadou</span>
                                    <span class="text-sm text-kinder-brown-400 mt-0.5 block">Ambalaj festiv cu fundiță</span>
                                </div>

                                <span class="flex-shrink-0 inline-flex items-center px-3 py-1 rounded-full bg-pink-50 text-pink-600 text-sm font-bold">
                                    +25 lei
                                </span>
                            </label>

                            {{-- Order Notes --}}
                            <div>
                                <label for="notes" class="block text-xs font-semibold text-kinder-brown-600 uppercase tracking-wider mb-2">Note comandă (opțional)</label>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows="3"
                                    class="input-field resize-none"
                                    placeholder="Instrucțiuni speciale pentru livrare..."
                                >{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button (Mobile) --}}
                    <div class="lg:hidden">
                        <button type="submit" class="btn-primary w-full py-4 text-base rounded-2xl">
                            Plasează comanda
                        </button>
                    </div>
                </div>

                {{-- ====================================================== --}}
                {{-- Right Column: Order Summary                            --}}
                {{-- ====================================================== --}}
                <div class="lg:w-[400px] flex-shrink-0">
                    <div class="sticky top-28">
                        <div class="bg-kinder-brown-50 rounded-3xl p-6 md:p-8">
                            <h3 class="font-display text-lg font-bold text-kinder-brown-800 mb-6">Sumar Comandă</h3>

                            {{-- Items List --}}
                            <div class="space-y-4 mb-6 max-h-80 overflow-y-auto pr-1">
                                @foreach ($cart->items as $item)
                                    <div class="flex items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl overflow-hidden bg-white flex-shrink-0 shadow-sm border border-kinder-brown-100/40">
                                            @if ($item->product->primary_image_url)
                                                <img src="{{ asset('storage/' . $item->product->primary_image_url) }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain p-1">
                                            @else
                                                <img src="{{ asset('images/placeholder-robot.svg') }}" alt="Imaginea lipseste" class="w-full h-full object-contain p-1.5 opacity-40">
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-kinder-brown-700 truncate">{{ $item->product->name }}</p>
                                            <p class="text-xs text-kinder-brown-400 mt-0.5">Cantitate: {{ $item->quantity }}</p>
                                        </div>
                                        <span class="text-sm font-bold text-kinder-brown-800 flex-shrink-0">
                                            {{ number_format($item->subtotal, 0) }} lei
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Price Breakdown --}}
                            <div class="border-t border-kinder-brown-200/60 pt-5 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-kinder-brown-500">Subtotal</span>
                                    <span class="font-semibold text-kinder-brown-700">{{ number_format($cart->total, 0) }} lei</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-kinder-brown-500">Livrare</span>
                                    <span class="font-semibold {{ $cart->total >= 500 ? 'text-green-600' : 'text-kinder-brown-500' }}">
                                        {{ $cart->total >= 500 ? 'Gratuită' : 'Se calculează' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="border-t border-kinder-brown-200/60 pt-5 mt-4">
                                <div class="flex justify-between items-baseline">
                                    <span class="font-display font-bold text-kinder-brown-800 text-base">Total</span>
                                    <span class="text-2xl font-display font-bold text-kinder-brown-800">{{ number_format($cart->total, 0) }} lei</span>
                                </div>
                            </div>

                            {{-- Submit Button (Desktop) --}}
                            <div class="hidden lg:block mt-6">
                                <button type="submit" class="btn-primary w-full py-4 text-base rounded-2xl">
                                    Plasează comanda
                                </button>
                            </div>

                            {{-- Trust Badges --}}
                            <div class="mt-5 pt-5 border-t border-kinder-brown-200/60 flex items-center justify-center gap-5 text-xs text-kinder-brown-400">
                                <span class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Plată securizată
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Date criptate
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

@endsection
