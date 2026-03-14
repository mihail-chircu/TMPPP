@extends('layouts.app')

@section('content')

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Success Header --}}
        <div class="text-center mb-10">
            <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-green-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="font-display text-2xl md:text-3xl font-bold text-kinder-brown-800 mb-2">
                Comanda confirmată!
            </h1>
            <p class="text-sm text-kinder-brown-500">
                Mulțumim pentru comandă. Pregătim jucăriile tale cu grijă deosebită!
            </p>
        </div>

        {{-- Order Number --}}
        <div class="bg-kinder-500 rounded-2xl p-5 text-center text-white mb-8">
            <p class="text-xs font-semibold text-kinder-100 uppercase tracking-wider mb-1">Număr comandă</p>
            <p class="font-display text-xl font-bold">{{ $order->order_number }}</p>
        </div>

        {{-- Order Details --}}
        <div class="bg-white rounded-2xl border border-kinder-brown-100/60 shadow-soft overflow-hidden mb-6">
            <div class="p-5">
                <h3 class="font-display font-bold text-sm text-kinder-brown-800 mb-4">Produse comandate</h3>
                <div class="space-y-3">
                    @foreach ($order->items as $item)
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-kinder-brown-50 flex-shrink-0">
                                @if ($item->product && $item->product->primary_image_url)
                                    <img src="{{ asset('storage/' . $item->product->primary_image_url) }}" alt="{{ $item->product_name }}" class="w-full h-full object-contain p-1">
                                @else
                                    <img src="{{ asset('images/placeholder-robot.svg') }}" alt="Imaginea lipsește" class="w-full h-full object-contain p-1 opacity-40">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-kinder-brown-700 text-sm truncate">{{ $item->product_name }}</p>
                                <p class="text-xs text-kinder-brown-400">
                                    {{ $item->quantity }} x
                                    @if ($item->discount_price)
                                        <span class="text-kinder-500">{{ number_format($item->discount_price, 0) }} lei</span>
                                        <span class="line-through text-kinder-brown-300">{{ number_format($item->price, 0) }} lei</span>
                                    @else
                                        {{ number_format($item->price, 0) }} lei
                                    @endif
                                </p>
                            </div>
                            <span class="text-sm font-bold text-kinder-brown-800 flex-shrink-0">
                                {{ number_format($item->total, 0) }} lei
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Totals --}}
            <div class="border-t border-kinder-brown-100 bg-kinder-brown-50/50 p-5 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-kinder-brown-500">Subtotal</span>
                    <span class="font-semibold text-kinder-brown-700">{{ number_format($order->subtotal, 0) }} lei</span>
                </div>
                @if ($order->shipping_cost > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-kinder-brown-500">Livrare</span>
                        <span class="font-semibold text-kinder-brown-700">{{ number_format($order->shipping_cost, 0) }} lei</span>
                    </div>
                @endif
                @if ($order->discount_total > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-kinder-brown-500">Reducere</span>
                        <span class="font-semibold text-kinder-500">-{{ number_format($order->discount_total, 0) }} lei</span>
                    </div>
                @endif
                <div class="flex justify-between pt-2 border-t border-kinder-brown-100">
                    <span class="font-display font-bold text-kinder-brown-800">Total</span>
                    <span class="font-display text-lg font-bold text-kinder-brown-800">{{ number_format($order->total, 0) }} lei</span>
                </div>
            </div>
        </div>

        {{-- Shipping Information --}}
        <div class="bg-white rounded-2xl border border-kinder-brown-100/60 shadow-soft p-5 mb-8">
            <h3 class="font-display font-bold text-sm text-kinder-brown-800 mb-4">Informații Livrare</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div>
                    <span class="text-xs text-kinder-brown-400 block mb-0.5">Nume</span>
                    <span class="font-semibold text-kinder-brown-700">{{ $order->customer_name }}</span>
                </div>
                <div>
                    <span class="text-xs text-kinder-brown-400 block mb-0.5">Email</span>
                    <span class="font-semibold text-kinder-brown-700">{{ $order->customer_email }}</span>
                </div>
                @if ($order->customer_phone)
                    <div>
                        <span class="text-xs text-kinder-brown-400 block mb-0.5">Telefon</span>
                        <span class="font-semibold text-kinder-brown-700">{{ $order->customer_phone }}</span>
                    </div>
                @endif
                <div>
                    <span class="text-xs text-kinder-brown-400 block mb-0.5">Adresa</span>
                    <span class="font-semibold text-kinder-brown-700">
                        {{ $order->shipping_address }},
                        {{ $order->shipping_city }}
                        @if ($order->shipping_postal_code), {{ $order->shipping_postal_code }}@endif
                    </span>
                </div>
                @if ($order->notes)
                    <div class="md:col-span-2">
                        <span class="text-xs text-kinder-brown-400 block mb-0.5">Note</span>
                        <span class="text-kinder-brown-700">{{ $order->notes }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('profile.index') }}" class="btn-secondary text-center text-sm">
                Istoric comenzi
            </a>
            <a href="{{ route('catalog.index') }}" class="btn-primary text-center text-sm">
                Continuă cumpărăturile
            </a>
        </div>

    </div>

@endsection
