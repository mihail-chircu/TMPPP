<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', 'Kinder - Magazin pentru Copii')</title>
    <meta name="description" content="@yield('meta_description', 'Kinder — magazin pentru copii din Leova, Moldova. Descoperă o lume magică de jucării, jocuri și cadouri pentru copii de toate vârstele.')">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=2">

    <!-- Google Fonts: preconnect for speed -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col">

    {{-- ================================================================== --}}
    {{-- TOP BAR — Slim contact info strip                                  --}}
    {{-- ================================================================== --}}
    <div class="hidden lg:block bg-kinder-brown-800 text-kinder-brown-300 text-[11px]">
        <div class="max-w-7xl mx-auto px-6 xl:px-8">
            <div class="flex items-center justify-between h-8">
                <div class="flex items-center gap-5">
                    <span class="flex items-center gap-1.5 border-l-2 border-kinder-500/40 pl-3">
                        <svg class="w-3 h-3 text-kinder-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Leova, Moldova
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3 h-3 text-kinder-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        078 184 044
                    </span>
                </div>
                <div class="flex items-center gap-5">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3 h-3 text-kinder-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        hello@kinder.md
                    </span>
                    <span class="text-kinder-brown-600">|</span>
                    <span class="tracking-wide">Luni - Duminică, 8:00 - 18:00</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================== --}}
    {{-- MAIN HEADER — Sticky, single-row on desktop                       --}}
    {{-- ================================================================== --}}
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-lg border-b border-kinder-brown-100/60 transition-shadow duration-300" id="site-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 xl:px-8">

            {{-- Main header row --}}
            <div class="flex items-center h-16 lg:h-18 gap-4 lg:gap-6">

                {{-- Hamburger (mobile) --}}
                <button id="mobile-menu-toggle" type="button"
                        class="lg:hidden p-2 -ml-2 rounded-lg text-kinder-brown-500 hover:bg-kinder-brown-50 transition"
                        aria-label="Deschide meniul">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="shrink-0 flex items-center">
                    <x-kinder-logo size="md" class="h-12" />
                </a>

                {{-- Desktop navigation --}}
                <nav class="hidden lg:flex items-center gap-1 ml-3" aria-label="Navigare principala">
                    <a href="{{ route('home') }}"
                       class="nav-link text-[13px] {{ request()->routeIs('home') ? 'active' : '' }}">Acasă</a>
                    <a href="{{ route('catalog.index') }}"
                       class="nav-link text-[13px] {{ request()->routeIs('catalog.*') && !request('on_sale') && !request('sort') ? 'active' : '' }}">Catalog</a>
                    <a href="{{ route('catalog.index', ['sort' => 'newest']) }}"
                       class="nav-link text-[13px] {{ request('sort') === 'newest' ? 'active' : '' }}">Noutăți</a>
                    <a href="{{ route('catalog.index', ['on_sale' => 1]) }}"
                       class="nav-link text-[13px] {{ request('on_sale') ? 'active' : '' }}">Reduceri</a>
                    <a href="{{ route('catalog.index') }}"
                       class="nav-link text-[13px]">Branduri</a>
                </nav>

                {{-- Search bar (desktop) with expandable animation --}}
                <div class="hidden md:block flex-1 max-w-md ml-auto">
                    <form action="{{ route('catalog.index') }}" method="GET" class="relative group">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Caută jucării..."
                               class="w-full pl-10 pr-4 py-2.5 bg-kinder-brown-50 border border-transparent rounded-xl text-sm text-kinder-brown-700 placeholder:text-kinder-brown-400 focus:bg-white focus:border-kinder-300 focus:ring-2 focus:ring-kinder-400/15 focus:outline-none focus:max-w-lg transition-all duration-300 ease-out">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-kinder-brown-400 group-focus-within:text-kinder-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </form>
                </div>

                {{-- Right actions --}}
                <div class="flex items-center gap-1 sm:gap-1.5 shrink-0 ml-auto md:ml-0">

                    {{-- Mobile search toggle --}}
                    <button type="button"
                            class="md:hidden p-2 rounded-lg text-kinder-brown-500 hover:bg-kinder-brown-50 transition"
                            onclick="document.getElementById('mobile-search').classList.toggle('hidden')"
                            aria-label="Caută">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    {{-- Wishlist --}}
                    <a href="{{ route('wishlist.index') }}"
                       class="relative p-2 rounded-lg text-kinder-brown-500 hover:bg-kinder-brown-50 hover:text-kinder-500 transition group"
                       title="Favorite" aria-label="Favorite">
                        <svg class="w-6 h-6 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        @if($wishlistCount > 0)
                            <span data-wishlist-count class="absolute -top-0.5 -right-0.5 flex items-center justify-center w-[18px] h-[18px] text-[10px] font-bold text-white bg-kinder-500 rounded-full ring-2 ring-white animate-bounce-once">{{ $wishlistCount }}</span>
                        @else
                            <span data-wishlist-count class="hidden absolute -top-0.5 -right-0.5 flex items-center justify-center w-[18px] h-[18px] text-[10px] font-bold text-white bg-kinder-500 rounded-full ring-2 ring-white animate-bounce-once"></span>
                        @endif
                    </a>

                    {{-- Cart --}}
                    <a href="{{ route('cart.index') }}"
                       class="relative p-2 rounded-lg text-kinder-brown-500 hover:bg-kinder-brown-50 hover:text-kinder-600 transition group"
                       title="Coșul meu" aria-label="Coșul meu">
                        <svg class="w-6 h-6 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if($cartCount > 0)
                            <span data-cart-count class="absolute -top-0.5 -right-0.5 flex items-center justify-center w-[18px] h-[18px] text-[10px] font-bold text-white bg-kinder-500 rounded-full ring-2 ring-white animate-bounce-once">{{ $cartCount }}</span>
                        @else
                            <span data-cart-count class="hidden absolute -top-0.5 -right-0.5 flex items-center justify-center w-[18px] h-[18px] text-[10px] font-bold text-white bg-kinder-500 rounded-full ring-2 ring-white animate-bounce-once"></span>
                        @endif
                    </a>

                    {{-- User dropdown (desktop) --}}
                    <div class="relative hidden md:block">
                        <button onclick="this.parentElement.querySelector('.user-dropdown').classList.toggle('hidden')"
                                class="p-2 rounded-lg text-kinder-brown-500 hover:bg-kinder-brown-50 transition"
                                title="Contul meu" aria-label="Contul meu">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </button>

                        {{-- Dropdown panel --}}
                        <div class="user-dropdown hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-soft-lg border border-kinder-brown-100 py-1.5 z-50 animate-fade-in">
                            @auth
                                <div class="px-4 py-3 border-b border-kinder-brown-100">
                                    <p class="text-sm font-display font-semibold text-kinder-brown-800 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-kinder-brown-400 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-kinder-brown-700 hover:bg-kinder-brown-50 transition">
                                    <svg class="w-4 h-4 text-kinder-brown-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profilul meu
                                </a>
                                <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-kinder-brown-700 hover:bg-kinder-brown-50 transition">
                                    <svg class="w-4 h-4 text-kinder-brown-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Comenzile mele
                                </a>
                                @if(auth()->user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-kinder-brown-700 hover:bg-kinder-brown-50 transition">
                                        <svg class="w-4 h-4 text-kinder-brown-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Panou Admin
                                    </a>
                                @endif
                                <hr class="my-1 border-kinder-brown-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-kinder-brown-700 hover:bg-kinder-brown-50 transition">
                                        <svg class="w-4 h-4 text-kinder-brown-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Deconectare
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-kinder-brown-700 hover:bg-kinder-brown-50 transition">
                                    <svg class="w-4 h-4 text-kinder-brown-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                    Autentificare
                                </a>
                                <a href="{{ route('register') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-kinder-brown-700 hover:bg-kinder-brown-50 transition">
                                    <svg class="w-4 h-4 text-kinder-brown-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                    Înregistrare
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mobile search (hidden by default) --}}
            <div id="mobile-search" class="hidden md:hidden pb-3">
                <form action="{{ route('catalog.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Caută jucării, jocuri, cadouri..."
                           class="w-full pl-10 pr-4 py-2.5 bg-kinder-brown-50 border border-transparent rounded-xl text-sm text-kinder-brown-700 placeholder:text-kinder-brown-400 focus:bg-white focus:border-kinder-300 focus:ring-2 focus:ring-kinder-400/15 focus:outline-none transition-all duration-200">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-kinder-brown-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </form>
            </div>
        </div>
    </header>

    {{-- ================================================================== --}}
    {{-- MOBILE SLIDE-OVER MENU                                             --}}
    {{-- ================================================================== --}}
    <div id="mobile-menu-overlay" class="hidden fixed inset-0 z-50 bg-black/30 backdrop-blur-sm lg:hidden"></div>
    <nav id="mobile-menu"
         class="fixed top-0 right-0 z-50 w-96 max-w-sm h-full bg-white shadow-soft-xl transform translate-x-full transition-transform duration-300 ease-in-out lg:hidden overflow-y-auto"
         aria-label="Meniu mobil">

        <div class="p-6">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <x-kinder-logo size="sm" />
                <button id="mobile-menu-close" type="button" class="p-2 rounded-lg text-kinder-brown-400 hover:bg-kinder-brown-50 transition" aria-label="Închide meniul">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile search --}}
            <form action="{{ route('catalog.index') }}" method="GET" class="relative mb-6">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Caută jucării..."
                       class="w-full pl-10 pr-4 py-3 bg-kinder-brown-50 border border-transparent rounded-xl text-sm text-kinder-brown-700 placeholder:text-kinder-brown-400 focus:bg-white focus:border-kinder-300 focus:outline-none transition-all duration-200">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-kinder-brown-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </form>

            {{-- Navigation links --}}
            <div class="space-y-1">
                <a href="{{ route('home') }}" class="nav-link block {{ request()->routeIs('home') ? 'active' : '' }}">Acasă</a>
                <a href="{{ route('catalog.index') }}" class="nav-link block {{ request()->routeIs('catalog.*') && !request('on_sale') && !request('sort') ? 'active' : '' }}">Catalog</a>
                <a href="{{ route('catalog.index', ['sort' => 'newest']) }}" class="nav-link block {{ request('sort') === 'newest' ? 'active' : '' }}">Noutăți</a>
                <a href="{{ route('catalog.index', ['on_sale' => 1]) }}" class="nav-link block {{ request('on_sale') ? 'active' : '' }}">Reduceri</a>
                <a href="{{ route('catalog.index') }}" class="nav-link block">Branduri</a>
            </div>

            <hr class="my-5 border-kinder-brown-100">

            {{-- Shortcuts --}}
            <div class="space-y-1">
                <a href="{{ route('wishlist.index') }}" class="nav-link block">
                    Favorite
                    @if($wishlistCount > 0)
                        <span class="ml-auto inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-kinder-500 rounded-full">{{ $wishlistCount }}</span>
                    @endif
                </a>
                <a href="{{ route('cart.index') }}" class="nav-link block">
                    Coșul meu
                    @if($cartCount > 0)
                        <span class="ml-auto inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-kinder-500 rounded-full">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>

            <hr class="my-5 border-kinder-brown-100">

            {{-- Auth links --}}
            <div class="space-y-1">
                @auth
                    <div class="px-3 py-3 mb-3 bg-kinder-brown-50 rounded-xl">
                        <p class="text-sm font-display font-semibold text-kinder-brown-800 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-kinder-brown-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.index') }}" class="nav-link block">Profilul meu</a>
                    <a href="{{ route('profile.index') }}" class="nav-link block">Comenzile mele</a>
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="nav-link block">Panou Admin</a>
                    @endif
                    <hr class="my-3 border-kinder-brown-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link block w-full text-left">Deconectare</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link block">Autentificare</a>
                    <a href="{{ route('register') }}" class="nav-link block">Înregistrare</a>
                @endauth
            </div>

            {{-- Contact info (mobile) --}}
            <hr class="my-5 border-kinder-brown-100">
            <div class="space-y-3 text-xs text-kinder-brown-400 px-1">
                <p class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-kinder-brown-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Leova, Moldova
                </p>
                <p class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-kinder-brown-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    078 184 044
                </p>
                <p class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-kinder-brown-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    hello@kinder.md
                </p>
            </div>
        </div>
    </nav>

    {{-- ================================================================== --}}
    {{-- FLASH MESSAGES                                                     --}}
    {{-- ================================================================== --}}
    <x-alert />

    {{-- ================================================================== --}}
    {{-- MAIN CONTENT                                                       --}}
    {{-- ================================================================== --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ================================================================== --}}
    {{-- FOOTER                                                             --}}
    {{-- ================================================================== --}}
    <footer class="bg-kinder-brown-900 text-kinder-brown-300 mt-auto">

        {{-- Newsletter signup row --}}
        <div class="border-b border-kinder-brown-800 animate-on-scroll">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 xl:px-8 py-10 md:py-12">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="text-center md:text-left">
                        <h3 class="font-display font-semibold text-white text-lg">Fii la curent cu noutățile</h3>
                        <p class="text-sm text-kinder-brown-400 mt-1">Abonează-te și primește oferte speciale și noutăți direct în inbox.</p>
                    </div>
                    <form class="flex w-full md:w-auto gap-2">
                        <input type="email" placeholder="Adresa ta de email"
                               class="w-full md:w-72 px-4 py-3 bg-kinder-brown-800 border border-kinder-brown-700 rounded-xl text-sm text-white placeholder:text-kinder-brown-500 focus:border-kinder-400 focus:ring-1 focus:ring-kinder-400/30 focus:outline-none transition-all duration-200">
                        <button type="submit" class="shrink-0 px-6 py-3 bg-kinder-500 hover:bg-kinder-600 text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                            Abonează-te
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Main footer columns --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 xl:px-8 py-16 md:py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12 animate-on-scroll">

                {{-- Brand column --}}
                <div class="lg:col-span-1">
                    <x-kinder-logo size="md" />
                    <p class="mt-5 text-sm leading-relaxed text-kinder-brown-400">
                        Magazin pentru copii din Leova. Aducem bucurie copiilor cu jucării, jocuri și cadouri atent selectate pentru fiecare vârstă.
                    </p>
                    <div class="flex items-center gap-2.5 mt-6">
                        <a href="#" class="w-10 h-10 rounded-xl bg-kinder-brown-800 flex items-center justify-center text-kinder-brown-400 hover:bg-kinder-500 hover:text-white transition-all duration-200" aria-label="Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-kinder-brown-800 flex items-center justify-center text-kinder-brown-400 hover:bg-kinder-500 hover:text-white transition-all duration-200" aria-label="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-kinder-brown-800 flex items-center justify-center text-kinder-brown-400 hover:bg-kinder-500 hover:text-white transition-all duration-200" aria-label="TikTok">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Quick links --}}
                <div class="animate-on-scroll">
                    <h4 class="font-display font-semibold text-white mb-5 text-sm uppercase tracking-wider">Navigare</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors duration-200">Acasă</a></li>
                        <li><a href="{{ route('catalog.index') }}" class="hover:text-white transition-colors duration-200">Catalog</a></li>
                        <li><a href="{{ route('catalog.index', ['sort' => 'newest']) }}" class="hover:text-white transition-colors duration-200">Noutăți</a></li>
                        <li><a href="{{ route('catalog.index', ['on_sale' => 1]) }}" class="hover:text-white transition-colors duration-200">Reduceri</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition-colors duration-200">Coșul meu</a></li>
                        <li><a href="{{ route('wishlist.index') }}" class="hover:text-white transition-colors duration-200">Favorite</a></li>
                    </ul>
                </div>

                {{-- Customer service --}}
                <div class="animate-on-scroll">
                    <h4 class="font-display font-semibold text-white mb-5 text-sm uppercase tracking-wider">Servicii</h4>
                    <ul class="space-y-3 text-sm">
                        <li><span class="text-kinder-brown-400">Livrare și Expediere</span></li>
                        <li><span class="text-kinder-brown-400">Returnări și Schimburi</span></li>
                        <li><span class="text-kinder-brown-400">Contactează-ne</span></li>
                        <li><span class="text-kinder-brown-400">Întrebări frecvente</span></li>
                        <li><span class="text-kinder-brown-400">Termeni și condiții</span></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="animate-on-scroll">
                    <h4 class="font-display font-semibold text-white mb-5 text-sm uppercase tracking-wider">Contact</h4>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-kinder-brown-800 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-kinder-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span>Leova, Moldova<br><span class="text-kinder-brown-500 text-xs">str. Independenței</span></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-kinder-brown-800 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-kinder-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <span>078 184 044<br><span class="text-kinder-brown-500 text-xs">Luni - Duminică, 8:00 - 18:00</span></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-kinder-brown-800 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-kinder-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span>hello@kinder.md</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="mt-14 pt-8 border-t border-kinder-brown-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-kinder-brown-500 animate-on-scroll">
                <span>&copy; {{ date('Y') }} Kinder — Magazin pentru Copii, Leova. Toate drepturile rezervate.</span>
                <div class="flex items-center gap-3">
                    <span class="text-xs">Plăți acceptate:</span>
                    <div class="flex items-center gap-1.5">
                        <span class="px-2 py-0.5 bg-kinder-brown-800 rounded text-xs font-semibold text-kinder-brown-400">VISA</span>
                        <span class="px-2 py-0.5 bg-kinder-brown-800 rounded text-xs font-semibold text-kinder-brown-400">MC</span>
                        <span class="px-2 py-0.5 bg-kinder-brown-800 rounded text-xs font-semibold text-kinder-brown-400">Cash</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Close user dropdown when clicking outside --}}
    <script>
        document.addEventListener('click', function(e) {
            document.querySelectorAll('.user-dropdown').forEach(function(dropdown) {
                if (!dropdown.parentElement.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
