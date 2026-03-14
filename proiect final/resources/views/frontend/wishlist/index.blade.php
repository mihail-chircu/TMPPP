@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <x-breadcrumb :items="[['label' => 'Favorite']]" />

        <div class="flex items-end justify-between gap-4 mb-10">
            <div>
                <h1 class="section-title">Favoritele Mele</h1>
                @if ($products->count())
                    <p class="text-sm text-kinder-brown-400 mt-2">{{ $products->count() }} {{ $products->count() == 1 ? 'produs salvat' : 'produse salvate' }}</p>
                @endif
            </div>
            @if ($products->count())
                <a href="{{ route('catalog.index') }}" class="btn-secondary text-sm">
                    Adaugă mai multe →
                </a>
            @endif
        </div>

        @if ($products->isEmpty())
            <x-empty-state
                icon="heart"
                title="Lista de favorite este goală"
                message="Adaugă jucăriile preferate apăsând pe iconița ♡. Le vei găsi aici oricând!"
                :actionUrl="route('catalog.index')"
                actionLabel="Explorează catalogul"
            />
        @else
            <x-product-grid :products="$products" :cols="4" :showWishlistBadge="true" />
        @endif

    </div>

@endsection
