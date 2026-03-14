@extends('layouts.app')

@section('content')

    {{-- ================================================================== --}}
    {{-- HERO                                                               --}}
    {{-- ================================================================== --}}
    <section class="relative overflow-hidden bg-white">
        {{-- Subtle background shape --}}
        <div class="absolute top-0 right-0 w-[60%] h-full bg-gradient-to-l from-kinder-50/80 to-transparent rounded-bl-[4rem] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 xl:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-20 items-center min-h-[90vh] py-16 lg:py-0">

                {{-- Left: Typography --}}
                <div class="max-w-xl">
                    <div class="animate-on-scroll inline-flex items-center gap-2 px-4 py-2 bg-kinder-50 rounded-full mb-8">
                        <span class="w-2 h-2 bg-kinder-500 rounded-full animate-pulse"></span>
                        <span class="text-xs font-bold text-kinder-700 uppercase tracking-widest">Kinder · Leova</span>
                    </div>

                    <h1 class="animate-on-scroll font-display text-5xl sm:text-6xl md:text-7xl font-extrabold text-kinder-brown-800 tracking-tight leading-[1.05] mb-6">
                        Jucării care<br>
                        aduc <span class="text-kinder-500">zâmbete</span>
                    </h1>

                    <p class="animate-on-scroll text-lg md:text-xl text-kinder-brown-400 leading-relaxed mb-10 max-w-md">
                        Descoperă o lume magică de jucării, jocuri și cadouri pentru copii de toate vârstele. Din Leova, cu dragoste!
                    </p>

                    <div class="animate-on-scroll flex flex-wrap items-center gap-4 mb-12">
                        <a href="{{ route('catalog.index') }}" class="btn-primary px-10 py-4 text-base rounded-2xl shadow-glow">
                            Explorează Catalogul
                        </a>
                        <a href="{{ route('catalog.index', ['on_sale' => 1]) }}" class="btn-secondary px-8 py-4 text-base rounded-2xl">
                            Reduceri →
                        </a>
                    </div>

                    {{-- Stats --}}
                    <div class="animate-on-scroll flex items-center gap-8">
                        <div>
                            <span class="font-display font-extrabold text-2xl text-kinder-brown-800">412+</span>
                            <span class="block text-xs text-kinder-brown-400 mt-0.5">Jucării</span>
                        </div>
                        <div class="w-px h-8 bg-kinder-brown-200"></div>
                        <div>
                            <span class="font-display font-extrabold text-2xl text-kinder-brown-800">25</span>
                            <span class="block text-xs text-kinder-brown-400 mt-0.5">Categorii</span>
                        </div>
                        <div class="w-px h-8 bg-kinder-brown-200"></div>
                        <div>
                            <span class="font-display font-extrabold text-2xl text-kinder-500">★ 4.9</span>
                            <span class="block text-xs text-kinder-brown-400 mt-0.5">Satisfacție</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Product slideshow --}}
                <div class="hidden lg:block animate-on-scroll">
                    <div class="relative bg-white rounded-[2rem] border border-kinder-brown-200/30 shadow-soft-lg overflow-hidden" id="hero-slideshow">
                        {{-- Slides --}}
                        <div class="relative aspect-square">
                            @foreach ($featuredProducts->take(10) as $index => $product)
                                <a href="{{ route('product.show', $product->slug) }}"
                                   class="hero-slide absolute inset-0 flex flex-col items-center justify-center p-8 transition-opacity duration-700 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                                    <div class="w-full h-3/4 flex items-center justify-center">
                                        @if ($product->primary_image_url)
                                            <img src="{{ asset('storage/' . $product->primary_image_url) }}"
                                                 alt="{{ $product->name }}"
                                                 class="max-w-full max-h-full object-contain"
                                                 loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                        @endif
                                    </div>
                                    <div class="text-center mt-4">
                                        <h3 class="font-display font-bold text-base text-kinder-brown-800 line-clamp-1">{{ $product->name }}</h3>
                                        <span class="font-display font-bold text-xl {{ $product->is_on_sale ? 'text-kinder-500' : 'text-kinder-brown-700' }}">{{ number_format($product->current_price, 0) }} lei</span>
                                        @if ($product->is_on_sale)
                                            <span class="ml-2 text-sm text-kinder-brown-400 line-through">{{ number_format($product->price, 0) }} lei</span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        {{-- Dots --}}
                        <div class="absolute bottom-4 left-0 right-0 flex items-center justify-center gap-2">
                            @foreach ($featuredProducts->take(10) as $index => $product)
                                <button onclick="goToSlide({{ $index }})"
                                        class="hero-dot w-2 h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-kinder-500 w-6' : 'bg-kinder-brown-300' }}"
                                        aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <script>
                    let currentSlide = 0;
                    const totalSlides = {{ $featuredProducts->take(10)->count() }};

                    function goToSlide(n) {
                        document.querySelectorAll('.hero-slide').forEach((s, i) => {
                            s.style.opacity = i === n ? '1' : '0';
                        });
                        document.querySelectorAll('.hero-dot').forEach((d, i) => {
                            d.className = i === n
                                ? 'hero-dot w-6 h-2 rounded-full transition-all duration-300 bg-kinder-500'
                                : 'hero-dot w-2 h-2 rounded-full transition-all duration-300 bg-kinder-brown-300';
                        });
                        currentSlide = n;
                    }

                    setInterval(() => {
                        goToSlide((currentSlide + 1) % totalSlides);
                    }, 3500);
                </script>
            </div>
        </div>
    </section>

    {{-- ================================================================== --}}
    {{-- CATEGORIES                                                         --}}
    {{-- ================================================================== --}}
    @if ($categories->count())
        <section class="py-20 md:py-26 bg-kinder-brown-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 xl:px-8">
                <div class="text-center mb-12 md:mb-16 animate-on-scroll">
                    <h2 class="section-title">Descoperă categoriile</h2>
                    <p class="text-kinder-brown-400 text-sm mt-3 max-w-md mx-auto">Alege categoria preferată și explorează jucăriile potrivite</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 stagger-children">
                    @foreach ($categories as $category)
                        <a href="{{ route('catalog.index', ['category' => $category->slug]) }}"
                           class="animate-on-scroll group block rounded-3xl overflow-hidden bg-white border border-kinder-brown-100/50 hover:shadow-soft-lg hover:-translate-y-1 transition-all duration-300">
                            {{-- Image area --}}
                            <div class="aspect-square bg-kinder-brown-50/30 flex items-center justify-center p-4 overflow-hidden">
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}"
                                         alt="{{ $category->name }}"
                                         class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500"
                                         loading="lazy">
                                @else
                                    <svg class="w-12 h-12 text-kinder-brown-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                @endif
                            </div>
                            {{-- Category name --}}
                            <div class="px-3 pb-3 pt-1 text-center">
                                <span class="font-display font-semibold text-xs md:text-sm text-kinder-brown-700 group-hover:text-kinder-500 transition-colors">
                                    {{ $category->name }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ================================================================== --}}
    {{-- FEATURED PRODUCTS — Tabbed                                         --}}
    {{-- ================================================================== --}}
    @if ($featuredProducts->count())
        <section class="py-20 md:py-26">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 xl:px-8">

                {{-- Header with pill tabs --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5 mb-10">
                    <h2 class="section-title">Produse</h2>
                    <div class="flex items-center gap-2 self-start sm:self-auto">
                        <button type="button" data-tab="tab-popular"
                                class="product-tab active rounded-full px-6 py-2.5 text-sm font-display font-semibold transition-all duration-200"
                                onclick="switchProductTab('tab-popular', this)">Populare</button>
                        <button type="button" data-tab="tab-new"
                                class="product-tab rounded-full px-6 py-2.5 text-sm font-display font-semibold transition-all duration-200"
                                onclick="switchProductTab('tab-new', this)">Noutăți</button>
                        <button type="button" data-tab="tab-sale"
                                class="product-tab rounded-full px-6 py-2.5 text-sm font-display font-semibold transition-all duration-200"
                                onclick="switchProductTab('tab-sale', this)">Reduceri</button>
                        <button type="button" data-tab="tab-top"
                                class="product-tab rounded-full px-6 py-2.5 text-sm font-display font-semibold transition-all duration-200"
                                onclick="switchProductTab('tab-top', this)">Top</button>
                    </div>
                </div>

                {{-- Tab panels --}}
                <div id="tab-popular" class="product-tab-content animate-on-scroll">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
                        @foreach ($featuredProducts as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                </div>
                <div id="tab-new" class="product-tab-content hidden">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
                        @foreach ($newProducts->count() ? $newProducts : $featuredProducts as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                </div>
                <div id="tab-sale" class="product-tab-content hidden">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
                        @foreach ($saleProducts->count() ? $saleProducts : $featuredProducts as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                </div>
                <div id="tab-top" class="product-tab-content hidden">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
                        @foreach ($featuredProducts as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                </div>

                <div class="text-center mt-12">
                    <a href="{{ route('catalog.index') }}" class="btn-secondary px-8 py-3 rounded-2xl inline-flex items-center gap-2">
                        Vezi Tot Catalogul
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ================================================================== --}}
    {{-- PROMO BANNERS                                                      --}}
    {{-- ================================================================== --}}
    <section class="py-20 md:py-26 bg-kinder-brown-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 xl:px-8">
            <div class="grid md:grid-cols-2 gap-6">

                {{-- New Arrivals --}}
                <a href="{{ route('catalog.index', ['sort' => 'newest']) }}"
                   class="animate-on-scroll group relative overflow-hidden rounded-3xl min-h-[220px] bg-gradient-to-br from-kinder-50 to-kinder-100 flex items-center p-8 md:p-10 hover:shadow-soft-xl transition-all duration-300">
                    <div class="absolute top-0 right-0 w-44 h-44 bg-kinder-500/10 rounded-full -translate-y-1/2 translate-x-1/3 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="absolute bottom-0 right-8 w-24 h-24 bg-kinder-500/8 rounded-full translate-y-1/3"></div>
                    <div class="relative z-10">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-kinder-500/10 backdrop-blur-sm rounded-full text-kinder-500 text-xs font-semibold mb-4">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Tocmai sosit
                        </span>
                        <h3 class="font-display text-2xl md:text-3xl font-bold text-kinder-500 mb-2">Noutăți</h3>
                        <p class="text-kinder-brown-500 text-sm mb-5 max-w-xs">Descoperă cele mai noi jucării adăugate în magazinul nostru</p>
                        <span class="inline-flex items-center gap-2 text-kinder-500 font-display font-semibold text-sm group-hover:gap-3 transition-all duration-200">
                            Vezi Noutățile
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </span>
                    </div>
                </a>

                {{-- Sales --}}
                <a href="{{ route('catalog.index', ['on_sale' => 1]) }}"
                   class="animate-on-scroll group relative overflow-hidden rounded-3xl min-h-[220px] bg-gradient-to-br from-orange-50 to-orange-100 flex items-center p-8 md:p-10 hover:shadow-soft-xl transition-all duration-300">
                    <div class="absolute top-0 right-0 w-44 h-44 bg-candy-orange/10 rounded-full -translate-y-1/2 translate-x-1/3 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="absolute bottom-0 right-8 w-24 h-24 bg-candy-orange/8 rounded-full translate-y-1/3"></div>
                    <div class="relative z-10">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-candy-orange/10 backdrop-blur-sm rounded-full text-candy-orange text-xs font-semibold mb-4">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            Prețuri mici
                        </span>
                        <h3 class="font-display text-2xl md:text-3xl font-bold text-candy-orange mb-2">Reduceri</h3>
                        <p class="text-kinder-brown-500 text-sm mb-5 max-w-xs">Profită de ofertele speciale și economisește la jucăriile preferate</p>
                        <span class="inline-flex items-center gap-2 text-candy-orange font-display font-semibold text-sm group-hover:gap-3 transition-all duration-200">
                            Vezi Reducerile
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- ================================================================== --}}
    {{-- CATEGORY PRODUCT ROWS                                              --}}
    {{-- ================================================================== --}}
    @foreach ($categoryRows as $row)
        <section class="py-16 md:py-20 {{ $loop->even ? 'bg-kinder-brown-50' : '' }}">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 xl:px-8">
                <div class="flex items-center justify-between mb-8 animate-on-scroll">
                    <h2 class="font-display text-xl md:text-2xl font-bold text-kinder-brown-800">{{ $row['category']->name }}</h2>
                    <a href="{{ route('catalog.index', ['category' => $row['category']->slug]) }}"
                       class="text-kinder-500 font-semibold text-sm hover:text-kinder-500/80 transition-colors flex items-center gap-1">
                        Toate
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Horizontal slider --}}
                <div class="relative group/slider animate-on-scroll">
                    <div class="flex gap-4 overflow-x-auto pb-2 snap-x snap-mandatory scrollbar-hide category-slider slider-mask">
                        @foreach ($row['products'] as $product)
                            <div class="min-w-[200px] sm:min-w-[220px] md:min-w-[240px] lg:min-w-0 lg:w-1/4 shrink-0 snap-start">
                                <x-product-card :product="$product" />
                            </div>
                        @endforeach
                    </div>

                    {{-- Scroll arrows --}}
                    <button onclick="this.closest('.group\\/slider').querySelector('.category-slider').scrollBy({left: -280, behavior: 'smooth'})"
                            class="hidden lg:flex absolute -left-5 top-1/2 -translate-y-1/2 w-12 h-12 bg-white rounded-2xl shadow-soft-lg items-center justify-center text-kinder-brown-400 hover:text-kinder-500 hover:shadow-soft-xl opacity-0 group-hover/slider:opacity-100 transition-all duration-300 z-10"
                            aria-label="Derulează stânga">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button onclick="this.closest('.group\\/slider').querySelector('.category-slider').scrollBy({left: 280, behavior: 'smooth'})"
                            class="hidden lg:flex absolute -right-5 top-1/2 -translate-y-1/2 w-12 h-12 bg-white rounded-2xl shadow-soft-lg items-center justify-center text-kinder-brown-400 hover:text-kinder-500 hover:shadow-soft-xl opacity-0 group-hover/slider:opacity-100 transition-all duration-300 z-10"
                            aria-label="Derulează dreapta">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </section>
    @endforeach

    {{-- ================================================================== --}}
    {{-- TRUST SIGNALS                                                      --}}
    {{-- ================================================================== --}}
    <section class="py-20 md:py-26 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 xl:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 stagger-children">
                {{-- Free shipping --}}
                <div class="animate-on-scroll flex items-center gap-5 p-6 rounded-2xl bg-white">
                    <div class="w-16 h-16 rounded-2xl bg-kinder-100 flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-kinder-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-kinder-brown-800 text-lg">Livrare Gratuită</h4>
                        <p class="text-sm text-kinder-brown-400 mt-1">La comenzi peste 500 MDL</p>
                    </div>
                </div>

                {{-- Secure payment --}}
                <div class="animate-on-scroll flex items-center gap-5 p-6 rounded-2xl bg-white">
                    <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-candy-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-kinder-brown-800 text-lg">Plată Securizată</h4>
                        <p class="text-sm text-kinder-brown-400 mt-1">Checkout 100% securizat</p>
                    </div>
                </div>

                {{-- Moldova delivery --}}
                <div class="animate-on-scroll flex items-center gap-5 p-6 rounded-2xl bg-white">
                    <div class="w-16 h-16 rounded-2xl bg-orange-100 flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-candy-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-kinder-brown-800 text-lg">Livrare în Moldova</h4>
                        <p class="text-sm text-kinder-brown-400 mt-1">Livrare rapidă în toată țara</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
<style>
    .product-tab { color: #9a8f7f; background: white; }
    .product-tab:hover { color: #4a4239; background: #f0f7ff; }
    .product-tab.active { background: #2B7DE9; color: white; }
</style>
@endpush

@push('scripts')
<script>
    function switchProductTab(tabId, button) {
        document.querySelectorAll('.product-tab-content').forEach(p => p.classList.add('hidden'));
        var target = document.getElementById(tabId);
        if (target) target.classList.remove('hidden');
        document.querySelectorAll('.product-tab').forEach(t => t.classList.remove('active'));
        button.classList.add('active');
    }
</script>
@endpush
