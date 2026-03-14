<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', 'Admin - Kinder')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=2">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50 font-body text-kinder-brown-800 antialiased">

    <div class="flex min-h-screen">

        {{-- ============================================================== --}}
        {{-- SIDEBAR                                                        --}}
        {{-- ============================================================== --}}
        <aside class="hidden lg:flex lg:flex-col w-[260px] bg-white border-r border-kinder-100 fixed inset-y-0 left-0 z-40">

            {{-- Logo --}}
            <div class="flex items-center gap-2 h-16 px-4 border-b border-kinder-100 shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/kinder-logo-sm.png') }}" alt="Kinder" class="h-8 w-auto object-contain">
                    <span class="text-xs font-display font-semibold text-kinder-brown-400 bg-kinder-50 px-2 py-0.5 rounded-lg">
                        Admin
                    </span>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="admin-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                {{-- Products --}}
                <a href="{{ route('admin.products.index') }}"
                   class="admin-sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Products
                </a>

                {{-- Categories --}}
                <a href="{{ route('admin.categories.index') }}"
                   class="admin-sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Categories
                </a>

                {{-- Orders --}}
                <a href="{{ route('admin.orders.index') }}"
                   class="admin-sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Orders
                </a>

                {{-- Discounts --}}
                <a href="{{ route('admin.discounts.index') }}"
                   class="admin-sidebar-link {{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                    Discounts
                </a>

                {{-- Users --}}
                <a href="{{ route('admin.users.index') }}"
                   class="admin-sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Users
                </a>

                {{-- Divider --}}
                <div class="pt-4 mt-4 border-t border-kinder-100">
                    <a href="{{ route('home') }}"
                       class="admin-sidebar-link text-kinder-brown-400 hover:text-kinder-600">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Store
                    </a>
                </div>
            </nav>
        </aside>

        {{-- ============================================================== --}}
        {{-- MAIN AREA                                                      --}}
        {{-- ============================================================== --}}
        <div class="flex-1 lg:ml-[260px] flex flex-col min-h-screen">

            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-kinder-100 shadow-sm">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">

                    {{-- Mobile menu toggle --}}
                    <button type="button"
                            class="lg:hidden p-2 rounded-xl text-kinder-brown-500 hover:bg-kinder-50 transition"
                            onclick="document.getElementById('admin-sidebar-mobile').classList.toggle('hidden')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    {{-- Page Title --}}
                    <h1 class="text-lg font-display font-bold text-kinder-brown-800 hidden lg:block">
                        @yield('page_title', 'Dashboard')
                    </h1>

                    {{-- Right side --}}
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-semibold text-kinder-brown-600 hidden sm:inline">
                            {{ auth()->user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-semibold text-kinder-brown-500 hover:text-kinder-600 hover:bg-kinder-50 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Mobile Sidebar Overlay --}}
            <div id="admin-sidebar-mobile"
                 class="hidden fixed inset-0 z-50 lg:hidden">

                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm"
                     onclick="document.getElementById('admin-sidebar-mobile').classList.add('hidden')"></div>

                {{-- Sidebar Drawer --}}
                <aside class="fixed inset-y-0 left-0 w-[260px] bg-white shadow-2xl overflow-y-auto">

                    {{-- Logo --}}
                    <div class="flex items-center justify-between h-16 px-4 border-b border-kinder-100">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                            <img src="{{ asset('images/kinder-logo-sm.png') }}" alt="Kinder" class="h-8 w-auto object-contain">
                            <span class="text-xs font-display font-semibold text-kinder-brown-400 bg-kinder-50 px-2 py-0.5 rounded-lg">
                                Admin
                            </span>
                        </a>
                        <button type="button"
                                class="p-2 rounded-xl text-kinder-brown-400 hover:bg-kinder-50 transition"
                                onclick="document.getElementById('admin-sidebar-mobile').classList.add('hidden')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Nav links (same as desktop) --}}
                    <nav class="px-4 py-6 space-y-1">
                        <a href="{{ route('admin.dashboard') }}"
                           class="admin-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.products.index') }}"
                           class="admin-sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Products
                        </a>
                        <a href="{{ route('admin.categories.index') }}"
                           class="admin-sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Categories
                        </a>
                        <a href="{{ route('admin.orders.index') }}"
                           class="admin-sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            Orders
                        </a>
                        <a href="{{ route('admin.discounts.index') }}"
                           class="admin-sidebar-link {{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                            Discounts
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="admin-sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            Users
                        </a>
                        <div class="pt-4 mt-4 border-t border-kinder-100">
                            <a href="{{ route('home') }}"
                               class="admin-sidebar-link text-kinder-brown-400 hover:text-kinder-600">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to Store
                            </a>
                        </div>
                    </nav>
                </aside>
            </div>

            {{-- Flash Messages --}}
            <x-alert />

            {{-- Page Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
