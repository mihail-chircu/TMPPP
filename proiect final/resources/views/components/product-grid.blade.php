@props([
    'products',
    'cols' => 4,
    'showWishlistBadge' => false,
])

@php
    $gridCols = match ((int) $cols) {
        2 => 'grid-cols-2',
        3 => 'grid-cols-2 md:grid-cols-3',
        4 => 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4',
        default => 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4',
    };
@endphp

<div {{ $attributes->merge(['class' => "grid $gridCols gap-4 md:gap-6"]) }}>
    @foreach ($products as $product)
        <x-product-card :product="$product" :showWishlistBadge="$showWishlistBadge" />
    @endforeach
</div>
