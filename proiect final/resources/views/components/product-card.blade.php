{{-- Product Card — Premium, Apple-inspired --}}
@props([
    'product',
    'showWishlistBadge' => false,
])

@php
    $isOnSale = $product->is_on_sale;
    $currentPrice = $product->current_price;
    $originalPrice = (float) $product->price;
    $discountPercent = $product->discount_percent;
    $imageUrl = $product->primary_image_url;
    $isInWishlist = auth()->check() && auth()->user()->hasInWishlist($product->id);
    $inStock = $product->stock > 0;
@endphp

<article class="card-product group flex flex-col h-full">

    {{-- Image --}}
    <div class="relative overflow-hidden aspect-square bg-white">
        <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full flex items-center justify-center p-3" aria-label="{{ $product->name }}">
            @if ($imageUrl)
                <img src="{{ asset('storage/' . $imageUrl) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-contain group-hover:scale-105 transition-all duration-500 ease-out"
                     loading="lazy">
            @else
                <img src="{{ asset('images/placeholder-robot.svg') }}"
                     alt="Imaginea lipsește"
                     class="w-full h-full object-contain p-8 opacity-40"
                     loading="lazy">
            @endif
        </a>

        {{-- Top-left badges --}}
        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5">
            @if ($isOnSale)
                <span class="badge-sale">-{{ round($discountPercent) }}%</span>
            @endif
            @if ($product->badge === 'new')
                <span class="badge-new">Nou</span>
            @endif
            @if ($product->badge === 'hot')
                <span class="badge-hot">Hot</span>
            @endif
        </div>

        {{-- Wishlist sale badge (wishlist page only) --}}
        @if ($showWishlistBadge && $isOnSale)
            <div class="absolute top-2.5 right-2.5">
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-candy-orange text-white text-[10px] font-bold rounded-full shadow animate-pulse">Reducere!</span>
            </div>
        @endif

        {{-- Wishlist heart --}}
        @if (!$showWishlistBadge)
            <button onclick="Wishlist.toggle({{ $product->id }}, this)"
                    class="absolute top-2.5 right-2.5 btn-wishlist
                           {{ $isInWishlist ? '!opacity-100 !text-kinder-500' : '' }}"
                    title="{{ $isInWishlist ? 'Elimină din favorite' : 'Adaugă la favorite' }}"
                    aria-label="{{ $isInWishlist ? 'Elimină din favorite' : 'Adaugă la favorite' }}">
                <svg class="w-4 h-4" viewBox="0 0 24 24"
                     fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
        @endif

        {{-- Out of stock overlay --}}
        @if (!$inStock)
            <div class="absolute inset-0 bg-white/70 backdrop-blur-sm flex items-end justify-center pb-4">
                <span class="px-3 py-1 bg-kinder-brown-700 text-white text-xs font-semibold rounded-full">Indisponibil</span>
            </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="p-4 sm:p-5 flex flex-col flex-1 gap-1">

        {{-- Category label --}}
        @if ($product->category)
            <span class="text-[10px] uppercase tracking-widest text-kinder-500 font-bold">{{ $product->category->name }}</span>
        @endif

        {{-- Name --}}
        <a href="{{ route('product.show', $product->slug) }}"
           class="text-sm font-bold text-kinder-brown-800 line-clamp-2 group-hover:text-kinder-500 transition-colors leading-snug">
            {{ $product->name }}
        </a>

        {{-- Spacer to push price+button down --}}
        <div class="flex-1 min-h-1"></div>

        {{-- Price --}}
        <div class="mt-1">
            @if ($isOnSale)
                <div class="flex items-baseline gap-1.5">
                    <span class="text-lg font-display font-bold text-candy-orange">{{ number_format($currentPrice, 0) }} lei</span>
                    <span class="text-xs text-kinder-brown-400 line-through">{{ number_format($originalPrice, 0) }} lei</span>
                </div>
            @else
                <span class="text-lg font-display font-bold text-kinder-brown-800">{{ number_format($currentPrice, 0) }} lei</span>
            @endif
        </div>

        {{-- Add to cart --}}
        @if ($inStock)
            <button onclick="Cart.add({{ $product->id }})" class="btn-cart mt-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                </svg>
                În coș
            </button>
        @else
            <button disabled class="mt-2 w-full px-3 py-2.5 text-xs font-semibold text-kinder-brown-400 border border-kinder-brown-200 rounded-xl cursor-not-allowed">
                Indisponibil
            </button>
        @endif
    </div>
</article>
