@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">

        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[
            ['label' => $product->category->name ?? 'Catalog', 'url' => route('catalog.index', ['category' => $product->category->slug ?? ''])],
            ['label' => $product->name],
        ]" />

        {{-- ============================================================== --}}
        {{-- Product Detail: Two-Column Layout                              --}}
        {{-- ============================================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">

            {{-- ========================================================== --}}
            {{-- Left Column: Image Gallery                                 --}}
            {{-- ========================================================== --}}
            <div class="space-y-4">
                {{-- Main Image --}}
                <div class="group relative rounded-3xl overflow-hidden bg-kinder-brown-50/50 aspect-square">
                    @php
                        $mainImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                    @endphp
                    @if ($mainImage)
                        <img
                            id="main-product-image"
                            src="{{ asset('storage/' . $mainImage->path) }}"
                            alt="{{ $mainImage->alt ?? $product->name }}"
                            class="w-full h-full object-contain p-4 group-hover:scale-105 transition-all duration-700"
                        >
                    @else
                        <img
                            id="main-product-image"
                            src="{{ asset('images/placeholder-robot.svg') }}"
                            alt="Imaginea lipsește"
                            class="w-full h-full object-contain p-12 opacity-40"
                        >
                    @endif

                    {{-- Badges --}}
                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                        @if ($product->is_on_sale)
                            <span class="badge-sale text-sm px-3 py-1">-{{ round($product->discount_percent) }}%</span>
                        @endif
                        @if ($product->badge === 'new')
                            <span class="badge-new text-sm px-3 py-1">NOU</span>
                        @endif
                        @if ($product->badge === 'hot')
                            <span class="badge-hot text-sm px-3 py-1">HOT</span>
                        @endif
                    </div>
                </div>

                {{-- Thumbnail Strip --}}
                @if ($product->images->count() > 1)
                    <div class="flex gap-3 overflow-x-auto pb-1">
                        @foreach ($product->images->sortBy('sort_order') as $image)
                            <button
                                type="button"
                                class="gallery-thumb flex-shrink-0 w-20 h-20 rounded-2xl overflow-hidden transition-all duration-200 cursor-pointer hover:opacity-90 {{ $image->is_primary ? 'ring-2 ring-kinder-500 ring-offset-2' : 'ring-1 ring-kinder-brown-100' }}"
                                data-src="{{ asset('storage/' . $image->path) }}"
                                data-alt="{{ $image->alt ?? $product->name }}"
                                onclick="
                                    document.getElementById('main-product-image').src = this.dataset.src;
                                    document.getElementById('main-product-image').alt = this.dataset.alt;
                                    document.querySelectorAll('.gallery-thumb').forEach(t => { t.classList.remove('ring-2', 'ring-kinder-500', 'ring-offset-2'); t.classList.add('ring-1', 'ring-kinder-brown-100'); });
                                    this.classList.remove('ring-1', 'ring-kinder-brown-100');
                                    this.classList.add('ring-2', 'ring-kinder-500', 'ring-offset-2');
                                "
                            >
                                <img
                                    src="{{ asset('storage/' . $image->path) }}"
                                    alt="{{ $image->alt ?? $product->name }}"
                                    class="w-full h-full object-contain p-1 rounded-2xl"
                                    loading="lazy"
                                >
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ========================================================== --}}
            {{-- Right Column: Product Info                                 --}}
            {{-- ========================================================== --}}
            <div class="flex flex-col">

                {{-- Category --}}
                @if ($product->category)
                    <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}"
                       class="text-xs uppercase tracking-widest text-kinder-500 font-bold hover:text-kinder-500/80 transition-colors mb-3">
                        {{ $product->category->name }}
                    </a>
                @endif

                {{-- Product Name --}}
                <h1 class="text-3xl md:text-4xl font-display font-bold tracking-tight text-kinder-brown-800 leading-tight mb-5">
                    {{ $product->name }}
                </h1>

                {{-- Price --}}
                <div class="flex items-baseline flex-wrap gap-3 mb-6">
                    @if ($product->is_on_sale)
                        <span class="text-2xl md:text-3xl font-display font-bold text-candy-orange">
                            {{ number_format($product->current_price, 0) }} lei
                        </span>
                        <span class="line-through text-lg text-kinder-brown-400">
                            {{ number_format($product->price, 0) }} lei
                        </span>
                        <span class="badge-sale text-sm px-3 py-1">
                            -{{ round($product->discount_percent) }}%
                        </span>
                    @else
                        <span class="text-2xl md:text-3xl font-display font-bold text-kinder-brown-800">
                            {{ number_format($product->current_price, 0) }} lei
                        </span>
                    @endif
                </div>

                {{-- Short Description --}}
                @if ($product->short_description)
                    <p class="text-base text-kinder-brown-600 leading-relaxed mb-6">
                        {{ $product->short_description }}
                    </p>
                @endif

                {{-- Info Chips --}}
                <div class="flex flex-wrap gap-3 mb-8">
                    {{-- Stock Status --}}
                    @if ($product->stock > 0)
                        <div class="inline-flex items-center gap-2.5 bg-kinder-brown-50 rounded-2xl px-4 py-3">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span class="text-sm font-semibold text-green-700">În stoc</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2.5 bg-kinder-brown-50 rounded-2xl px-4 py-3">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span class="text-sm font-semibold text-red-600">Indisponibil</span>
                        </div>
                    @endif

                    {{-- Age Range --}}
                    @if ($product->age_min !== null && $product->age_max !== null)
                        <div class="inline-flex items-center gap-2.5 bg-kinder-brown-50 rounded-2xl px-4 py-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-kinder-brown-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-semibold text-kinder-brown-600">{{ $product->age_min }}{{ $product->age_max > $product->age_min ? '-' . $product->age_max : '' }}+ ani</span>
                        </div>
                    @endif

                    {{-- Brand --}}
                    @if ($product->brand)
                        <div class="inline-flex items-center gap-2.5 bg-kinder-brown-50 rounded-2xl px-4 py-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-kinder-brown-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            <span class="text-sm font-semibold text-kinder-brown-600">{{ $product->brand }}</span>
                        </div>
                    @endif
                </div>

                {{-- Quantity Selector + Add to Cart + Wishlist --}}
                @if ($product->stock > 0)
                    <div class="space-y-4 mb-8">
                        <div class="flex flex-col sm:flex-row gap-3">
                            {{-- Quantity Selector --}}
                            <div class="quantity-selector inline-flex items-center bg-kinder-brown-50 rounded-2xl overflow-hidden">
                                <button
                                    type="button"
                                    data-action="minus"
                                    class="qty-minus w-10 h-10 flex items-center justify-center text-kinder-brown-500 hover:bg-kinder-brown-100 transition-colors font-bold text-lg rounded-xl"
                                >
                                    &minus;
                                </button>
                                <input
                                    type="number"
                                    id="product-quantity"
                                    value="1"
                                    min="1"
                                    max="{{ min($product->stock, 99) }}"
                                    class="w-14 h-10 text-center bg-transparent border-0 font-display font-bold text-kinder-brown-800 focus:outline-none focus:ring-0"
                                >
                                <button
                                    type="button"
                                    data-action="plus"
                                    class="qty-plus w-10 h-10 flex items-center justify-center text-kinder-brown-500 hover:bg-kinder-brown-100 transition-colors font-bold text-lg rounded-xl"
                                >
                                    +
                                </button>
                            </div>

                            {{-- Add to Cart Button --}}
                            <button
                                onclick="Cart.add({{ $product->id }}, parseInt(document.getElementById('product-quantity').value))"
                                class="btn-primary w-full py-4 text-base rounded-2xl flex items-center justify-center gap-2.5"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                                </svg>
                                Adaugă în coș
                            </button>
                        </div>

                        {{-- Wishlist Button --}}
                        @php
                            $isInWishlist = auth()->check() && auth()->user()->hasInWishlist($product->id);
                        @endphp
                        <button
                            onclick="Wishlist.toggle({{ $product->id }}, this)"
                            class="inline-flex items-center justify-center gap-2.5 w-full py-3.5 rounded-2xl border-2 transition-all duration-200 text-sm font-bold
                                {{ $isInWishlist ? 'border-kinder-500 bg-kinder-50 text-kinder-500' : 'border-kinder-brown-200 text-kinder-brown-500 hover:border-kinder-500 hover:text-kinder-500' }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            {{ $isInWishlist ? 'În lista de favorite' : 'Adaugă la favorite' }}
                        </button>
                    </div>
                @else
                    {{-- Wishlist Button (out of stock) --}}
                    @php
                        $isInWishlist = auth()->check() && auth()->user()->hasInWishlist($product->id);
                    @endphp
                    <div class="mb-8">
                        <button
                            onclick="Wishlist.toggle({{ $product->id }}, this)"
                            class="inline-flex items-center justify-center gap-2.5 w-full py-3.5 rounded-2xl border-2 transition-all duration-200 text-sm font-bold
                                {{ $isInWishlist ? 'border-kinder-500 bg-kinder-50 text-kinder-500' : 'border-kinder-brown-200 text-kinder-brown-500 hover:border-kinder-500 hover:text-kinder-500' }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            {{ $isInWishlist ? 'În lista de favorite' : 'Adaugă la favorite' }}
                        </button>
                    </div>
                @endif

                {{-- Product Meta --}}
                <div class="border-t border-kinder-brown-100 pt-6 space-y-3">
                    @if ($product->sku)
                        <div class="flex items-center gap-3 text-sm">
                            <span class="font-bold text-kinder-brown-600 min-w-[5rem]">SKU</span>
                            <span class="text-kinder-brown-500">{{ $product->sku }}</span>
                        </div>
                    @endif
                    @if ($product->brand)
                        <div class="flex items-center gap-3 text-sm">
                            <span class="font-bold text-kinder-brown-600 min-w-[5rem]">Brand</span>
                            <span class="text-kinder-brown-500">{{ $product->brand }}</span>
                        </div>
                    @endif
                    @if ($product->category)
                        <div class="flex items-center gap-3 text-sm">
                            <span class="font-bold text-kinder-brown-600 min-w-[5rem]">Categorie</span>
                            <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" class="text-kinder-500 hover:text-kinder-500/80 font-semibold transition-colors">
                                {{ $product->category->name }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- Full Description                                               --}}
        {{-- ============================================================== --}}
        @if ($product->description)
            <div class="mt-14 bg-kinder-brown-50 rounded-3xl p-8 md:p-10">
                <h2 class="font-display font-bold text-xl text-kinder-brown-800 mb-5">Descriere completă</h2>
                <div class="prose prose-kinder max-w-none text-kinder-brown-600 leading-relaxed">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
        @endif

        {{-- ============================================================== --}}
        {{-- Related Products                                               --}}
        {{-- ============================================================== --}}
        @if ($relatedProducts->count())
            <section class="mt-16 animate-on-scroll">
                <h2 class="section-title mb-8">Produse similare</h2>
                <div class="stagger-children">
                    <x-product-grid :products="$relatedProducts" :cols="4" />
                </div>
            </section>
        @endif

    </div>

    {{-- Track recently viewed --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                RecentlyViewed.add({{ $product->id }});
            });
        </script>
    @endpush

@endsection
