@extends('layouts.app')

@section('content')

    @php
        // Determine page context for dynamic title & breadcrumb
        $pageTitle = 'Catalog Jucării';
        $breadcrumbLabel = 'Catalog';

        if (request('on_sale')) {
            $pageTitle = 'Reduceri';
            $breadcrumbLabel = 'Reduceri';
        } elseif (request('sort') === 'newest' && !request('search') && !request('category')) {
            $pageTitle = 'Noutăți';
            $breadcrumbLabel = 'Noutăți';
        } elseif (request('search')) {
            $pageTitle = 'Rezultate pentru „' . request('search') . '"';
            $breadcrumbLabel = 'Căutare';
        }

        $currentSort = request('sort', 'newest');
    @endphp

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">

        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[['label' => $breadcrumbLabel]]" />

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
            <div>
                <h1 class="section-title">{{ $pageTitle }}</h1>
                <p class="text-sm text-gray-500 mt-1.5">
                    {{ $products->total() }} {{ $products->total() == 1 ? 'produs găsit' : 'produse găsite' }}
                </p>
            </div>

            {{-- Sort dropdown --}}
            <div class="flex items-center gap-3">
                <label for="sort-select" class="text-sm text-gray-500 whitespace-nowrap">Sortare:</label>
                <select
                    id="sort-select"
                    name="sort"
                    form="filter-form"
                    onchange="document.getElementById('filter-form').submit()"
                    class="input-field rounded-full py-2.5 px-5 !text-sm !w-auto min-w-[170px] focus:border-kinder-500 focus:ring-kinder-500"
                >
                    <option value="newest" {{ $currentSort === 'newest' ? 'selected' : '' }}>Cele mai noi</option>
                    <option value="price_asc" {{ $currentSort === 'price_asc' ? 'selected' : '' }}>Preț: mic la mare</option>
                    <option value="price_desc" {{ $currentSort === 'price_desc' ? 'selected' : '' }}>Preț: mare la mic</option>
                    <option value="popular" {{ $currentSort === 'popular' ? 'selected' : '' }}>Cele mai populare</option>
                </select>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-10">

            {{-- ============================================================== --}}
            {{-- Mobile Filter Toggle                                           --}}
            {{-- ============================================================== --}}
            <button
                id="mobile-filter-toggle"
                type="button"
                class="lg:hidden btn-secondary w-full flex items-center justify-center gap-2.5 rounded-2xl"
                onclick="document.getElementById('mobile-filter-overlay').classList.remove('hidden')"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filtre și căutare
            </button>

            {{-- ============================================================== --}}
            {{-- Mobile Filter Overlay                                          --}}
            {{-- ============================================================== --}}
            <div id="mobile-filter-overlay" class="hidden fixed inset-0 z-50 lg:hidden">
                {{-- Backdrop --}}
                <div
                    class="absolute inset-0 bg-kinder-brown-900/40 backdrop-blur-sm"
                    onclick="document.getElementById('mobile-filter-overlay').classList.add('hidden')"
                ></div>

                {{-- Panel --}}
                <div class="absolute inset-y-0 left-0 w-full max-w-sm bg-white shadow-2xl overflow-y-auto">
                    {{-- Close button --}}
                    <div class="flex items-center justify-between p-5 border-b border-kinder-brown-100">
                        <h2 class="font-display font-bold text-kinder-brown-800">Filtre</h2>
                        <button
                            type="button"
                            class="p-2 rounded-xl text-kinder-brown-400 hover:text-kinder-brown-600 hover:bg-kinder-brown-50 transition-colors"
                            onclick="document.getElementById('mobile-filter-overlay').classList.add('hidden')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Duplicate filter form for mobile --}}
                    <form method="GET" action="{{ route('catalog.index') }}" class="p-5 space-y-6">

                        {{-- Search --}}
                        <div>
                            <label class="font-display font-bold text-sm text-kinder-brown-700 uppercase tracking-wider block mb-2">Căutare</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Caută jucării..."
                                    class="input-field rounded-full !pr-10"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-kinder-brown-300 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        {{-- Categories --}}
                        <div class="border-b border-kinder-brown-100 pb-5">
                            <h3 class="font-display font-bold text-sm text-kinder-brown-700 uppercase tracking-wider mb-3">Categorii</h3>
                            <div class="space-y-2 max-h-52 overflow-y-auto">
                                @foreach ($categories as $category)
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input
                                            type="checkbox"
                                            name="category[]"
                                            value="{{ $category->slug }}"
                                            {{ in_array($category->slug, (array) request('category')) ? 'checked' : '' }}
                                            class="rounded-lg w-4 h-4 text-kinder-500 focus:ring-kinder-500/40 border-gray-300"
                                        >
                                        <span class="text-sm text-gray-600 group-hover:text-kinder-500 transition-colors">{{ $category->name }}</span>
                                    </label>
                                    @if ($category->children->count())
                                        <div class="ml-6 space-y-2">
                                            @foreach ($category->children as $child)
                                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                                    <input
                                                        type="checkbox"
                                                        name="category[]"
                                                        value="{{ $child->slug }}"
                                                        {{ in_array($child->slug, (array) request('category')) ? 'checked' : '' }}
                                                        class="rounded-lg w-4 h-4 text-kinder-500 focus:ring-kinder-500/40 border-gray-300"
                                                    >
                                                    <span class="text-sm text-gray-500 group-hover:text-kinder-500 transition-colors">{{ $child->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Price Range --}}
                        <div class="border-b border-kinder-brown-100 pb-5">
                            <h3 class="font-display font-bold text-sm text-kinder-brown-700 uppercase tracking-wider mb-3">Preț (lei)</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">De la</label>
                                    <input
                                        type="number"
                                        name="min_price"
                                        value="{{ request('min_price', $priceRange['min']) }}"
                                        min="{{ $priceRange['min'] }}"
                                        max="{{ $priceRange['max'] }}"
                                        step="any"
                                        class="input-field rounded-full text-sm py-2.5 px-4"
                                    >
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">Până la</label>
                                    <input
                                        type="number"
                                        name="max_price"
                                        value="{{ request('max_price', $priceRange['max']) }}"
                                        min="{{ $priceRange['min'] }}"
                                        max="{{ $priceRange['max'] }}"
                                        step="any"
                                        class="input-field rounded-full text-sm py-2.5 px-4"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Age Range --}}
                        <div class="border-b border-kinder-brown-100 pb-5">
                            <h3 class="font-display font-bold text-sm text-kinder-brown-700 uppercase tracking-wider mb-3">Vârstă</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ([['value' => '1', 'label' => '0-2'], ['value' => '4', 'label' => '3-5'], ['value' => '7', 'label' => '6-8'], ['value' => '10', 'label' => '9-12'], ['value' => '14', 'label' => '13+']] as $age)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="age" value="{{ $age['value'] }}" class="peer sr-only" {{ request('age') == $age['value'] ? 'checked' : '' }}>
                                        <span class="inline-block px-3 py-1.5 text-xs font-semibold rounded-full border border-gray-200 text-gray-500 peer-checked:border-kinder-500 peer-checked:bg-kinder-500 peer-checked:text-white transition-all duration-200 hover:border-kinder-500/50">
                                            {{ $age['label'] }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Brands --}}
                        @if ($brands->count())
                            <div class="border-b border-kinder-brown-100 pb-5">
                                <h3 class="font-display font-bold text-sm text-kinder-brown-700 uppercase tracking-wider mb-3">Branduri</h3>
                                <div class="space-y-2 max-h-40 overflow-y-auto">
                                    @foreach ($brands as $brand)
                                        <label class="flex items-center gap-2.5 cursor-pointer group">
                                            <input
                                                type="checkbox"
                                                name="brand[]"
                                                value="{{ $brand }}"
                                                {{ in_array($brand, (array) request('brand')) ? 'checked' : '' }}
                                                class="rounded-lg w-4 h-4 text-kinder-500 focus:ring-kinder-500/40 border-gray-300"
                                            >
                                            <span class="text-sm text-gray-600 group-hover:text-kinder-500 transition-colors">{{ $brand }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Availability --}}
                        <div class="space-y-2.5">
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    name="in_stock"
                                    value="1"
                                    {{ request('in_stock') ? 'checked' : '' }}
                                    class="rounded-lg w-4 h-4 text-kinder-500 focus:ring-kinder-500/40 border-gray-300"
                                >
                                <span class="text-sm font-semibold text-gray-600">Doar în stoc</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    name="on_sale"
                                    value="1"
                                    {{ request('on_sale') ? 'checked' : '' }}
                                    class="rounded-lg w-4 h-4 text-kinder-500 focus:ring-kinder-500/40 border-gray-300"
                                >
                                <span class="text-sm font-semibold text-gray-600">La reducere</span>
                            </label>
                        </div>

                        {{-- Actions --}}
                        <div class="space-y-2 pt-2">
                            <button type="submit" class="btn-primary w-full">Aplică filtrele</button>
                            <a href="{{ route('catalog.index') }}" class="btn-ghost w-full text-center block">Resetează</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ============================================================== --}}
            {{-- Sidebar Filters (Desktop)                                      --}}
            {{-- ============================================================== --}}
            <aside class="hidden lg:block lg:w-64 xl:w-72 flex-shrink-0 animate-on-scroll">
                <div class="sticky top-28 max-h-[calc(100vh-8rem)] overflow-y-auto">
                    <form id="filter-form" method="GET" action="{{ route('catalog.index') }}" class="bg-white rounded-3xl shadow-soft p-6 space-y-6">

                        {{-- Search --}}
                        <div>
                            <label for="filter-search" class="font-display font-bold text-sm text-kinder-brown-700 uppercase tracking-wider block mb-2">Căutare</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="filter-search"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Caută jucării..."
                                    class="input-field rounded-full !pr-10"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-kinder-brown-300 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        {{-- Categories --}}
                        <div class="border-b border-kinder-brown-100 pb-5">
                            <h3 class="font-display font-bold text-sm text-kinder-brown-700 uppercase tracking-wider mb-3">Categorii</h3>
                            <div class="space-y-2 max-h-52 overflow-y-auto pr-1">
                                @foreach ($categories as $category)
                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                        <input
                                            type="checkbox"
                                            name="category[]"
                                            value="{{ $category->slug }}"
                                            {{ in_array($category->slug, (array) request('category')) ? 'checked' : '' }}
                                            class="rounded-lg w-4 h-4 text-kinder-500 focus:ring-kinder-500/40 border-gray-300"
                                        >
                                        <span class="text-sm text-gray-600 group-hover:text-kinder-500 transition-colors">{{ $category->name }}</span>
                                    </label>
                                    @if ($category->children->count())
                                        <div class="ml-6 space-y-2">
                                            @foreach ($category->children as $child)
                                                <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                    <input
                                                        type="checkbox"
                                                        name="category[]"
                                                        value="{{ $child->slug }}"
                                                        {{ in_array($child->slug, (array) request('category')) ? 'checked' : '' }}
                                                        class="rounded-lg w-4 h-4 text-kinder-500 focus:ring-kinder-500/40 border-gray-300"
                                                    >
                                                    <span class="text-sm text-gray-500 group-hover:text-kinder-500 transition-colors">{{ $child->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Price Range --}}
                        <div class="border-b border-kinder-brown-100 pb-5">
                            <h3 class="font-display font-bold text-sm text-kinder-brown-700 uppercase tracking-wider mb-3">Preț (lei)</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="min-price-input" class="text-xs text-gray-400 mb-1.5 block">De la</label>
                                    <input
                                        type="number"
                                        id="min-price-input"
                                        name="min_price"
                                        value="{{ request('min_price', $priceRange['min']) }}"
                                        min="{{ $priceRange['min'] }}"
                                        max="{{ $priceRange['max'] }}"
                                        step="any"
                                        class="input-field rounded-full text-sm py-2.5 px-4"
                                    >
                                </div>
                                <div>
                                    <label for="max-price-input" class="text-xs text-gray-400 mb-1.5 block">Până la</label>
                                    <input
                                        type="number"
                                        id="max-price-input"
                                        name="max_price"
                                        value="{{ request('max_price', $priceRange['max']) }}"
                                        min="{{ $priceRange['min'] }}"
                                        max="{{ $priceRange['max'] }}"
                                        step="any"
                                        class="input-field rounded-full text-sm py-2.5 px-4"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Age Range --}}
                        <div class="border-b border-kinder-brown-100 pb-5">
                            <h3 class="font-display font-bold text-sm text-kinder-brown-700 uppercase tracking-wider mb-3">Vârstă</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ([['value' => '1', 'label' => '0-2'], ['value' => '4', 'label' => '3-5'], ['value' => '7', 'label' => '6-8'], ['value' => '10', 'label' => '9-12'], ['value' => '14', 'label' => '13+']] as $age)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="age" value="{{ $age['value'] }}" class="peer sr-only" {{ request('age') == $age['value'] ? 'checked' : '' }}>
                                        <span class="inline-block px-3 py-1.5 text-xs font-semibold rounded-full border border-gray-200 text-gray-500 peer-checked:border-kinder-500 peer-checked:bg-kinder-500 peer-checked:text-white transition-all duration-200 hover:border-kinder-500/50">
                                            {{ $age['label'] }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Brands --}}
                        @if ($brands->count())
                            <div class="border-b border-kinder-brown-100 pb-5">
                                <h3 class="font-display font-bold text-sm text-kinder-brown-700 uppercase tracking-wider mb-3">Branduri</h3>
                                <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                                    @foreach ($brands as $brand)
                                        <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                            <input
                                                type="checkbox"
                                                name="brand[]"
                                                value="{{ $brand }}"
                                                {{ in_array($brand, (array) request('brand')) ? 'checked' : '' }}
                                                class="rounded-lg w-4 h-4 text-kinder-500 focus:ring-kinder-500/40 border-gray-300"
                                            >
                                            <span class="text-sm text-gray-600 group-hover:text-kinder-500 transition-colors">{{ $brand }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Availability toggles --}}
                        <div class="border-b border-kinder-brown-100 pb-5 space-y-2.5">
                            <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                <input
                                    type="checkbox"
                                    name="in_stock"
                                    value="1"
                                    {{ request('in_stock') ? 'checked' : '' }}
                                    class="rounded-lg w-4 h-4 text-kinder-500 focus:ring-kinder-500/40 border-gray-300"
                                >
                                <span class="text-sm font-semibold text-gray-600 group-hover:text-kinder-500 transition-colors">Doar în stoc</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                <input
                                    type="checkbox"
                                    name="on_sale"
                                    value="1"
                                    {{ request('on_sale') ? 'checked' : '' }}
                                    class="rounded-lg w-4 h-4 text-kinder-500 focus:ring-kinder-500/40 border-gray-300"
                                >
                                <span class="text-sm font-semibold text-gray-600 group-hover:text-kinder-500 transition-colors">La reducere</span>
                            </label>
                        </div>

                        {{-- Actions --}}
                        <div class="space-y-2.5">
                            <button type="submit" class="btn-primary w-full">
                                Aplică filtrele
                            </button>
                            <a href="{{ route('catalog.index') }}" class="btn-ghost w-full text-center block">
                                Resetează
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            {{-- ============================================================== --}}
            {{-- Product Grid Area                                              --}}
            {{-- ============================================================== --}}
            <div class="flex-1 min-w-0">

                {{-- Product Grid --}}
                <div id="product-grid" class="animate-on-scroll">
                    @include('frontend.catalog._product-grid', ['products' => $products])
                </div>

                {{-- Pagination --}}
                <div id="product-pagination" class="mt-10">
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </section>

@endsection
