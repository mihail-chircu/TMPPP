@props([
    'icon' => 'box',
    'title' => 'Nothing here yet',
    'message' => '',
    'actionUrl' => null,
    'actionLabel' => null,
])

@php
    $svgIcon = match ($icon) {
        'box' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
        'heart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
        'cart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',
        'search' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
        'order' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
        'tag' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>',
        default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
    };
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-16 px-4 text-center']) }}>
    {{-- Icon --}}
    <div class="w-20 h-20 rounded-3xl bg-kinder-50 flex items-center justify-center mb-6">
        <svg class="w-10 h-10 text-kinder-brown-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $svgIcon !!}
        </svg>
    </div>

    {{-- Title --}}
    <h3 class="font-display text-xl font-bold text-kinder-brown-700 mb-2">{{ $title }}</h3>

    {{-- Message --}}
    @if ($message)
        <p class="text-kinder-brown-400 mb-6 max-w-md leading-relaxed">{{ $message }}</p>
    @endif

    {{-- CTA Button --}}
    @if ($actionUrl && $actionLabel)
        <a href="{{ $actionUrl }}" class="btn-primary">
            {{ $actionLabel }}
        </a>
    @endif
</div>
