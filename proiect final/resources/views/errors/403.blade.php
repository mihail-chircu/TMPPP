@extends('layouts.app')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-16">
    <div class="text-center max-w-md">
        {{-- Icon --}}
        <div class="w-24 h-24 mx-auto mb-6 bg-kinder-100 rounded-full flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-kinder-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>

        {{-- Title --}}
        <h1 class="font-display text-3xl font-bold text-kinder-brown-800 mb-3">
            403 — Acces Interzis
        </h1>

        {{-- Message --}}
        <p class="text-kinder-brown-500 mb-8">
            {{ $exception->getMessage() ?: 'Nu ai permisiunea de a accesa aceasta pagina.' }}
        </p>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Pagina principala
            </a>
            @guest
                <a href="{{ route('login') }}" class="btn-secondary">
                    Autentificare
                </a>
            @endguest
        </div>
    </div>
</div>
@endsection
