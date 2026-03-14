@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-kinder-brown-50 to-white px-4 py-12">

    <div class="bg-white rounded-3xl shadow-soft-lg p-8 md:p-10 w-full max-w-md">

        <x-kinder-logo size="lg" class="mx-auto mb-8" />

        <h1 class="text-2xl font-display font-bold text-center text-kinder-brown-800 mb-2">Creează cont</h1>
        <p class="text-sm text-kinder-brown-500 text-center mb-8">Înregistrare - și începe cumpărăturile!</p>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-3.5">
                <div class="flex items-center gap-2 mb-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-red-700 text-xs font-semibold">Ceva nu a mers bine.</span>
                </div>
                <ul class="list-disc list-inside text-red-600 text-xs space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-kinder-brown-600 uppercase tracking-wider mb-1.5">Nume complet</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Numele tău complet" class="input-field @error('name') !border-red-400 @enderror" />
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-kinder-brown-600 uppercase tracking-wider mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email@exemplu.com" class="input-field @error('email') !border-red-400 @enderror" />
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-kinder-brown-600 uppercase tracking-wider mb-1.5">Parolă</label>
                <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Minimum 8 caractere" class="input-field @error('password') !border-red-400 @enderror" />
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-kinder-brown-600 uppercase tracking-wider mb-1.5">Confirmă parola</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Repetă parola" class="input-field" />
            </div>

            <button type="submit" class="btn-primary w-full py-4 text-base rounded-2xl">
                Creează Cont
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-sm text-kinder-brown-500">
                Ai deja un cont?
                <a href="{{ route('login') }}" class="font-semibold text-kinder-500 hover:text-kinder-500/80 transition-colors">Autentifică-te</a>
            </p>
        </div>

    </div>

</div>
@endsection
