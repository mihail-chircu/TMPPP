@props([
    'items' => [],
])

@if (count($items) > 0)
<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex items-center flex-wrap gap-1.5 text-sm">
        {{-- Home link --}}
        <li>
            <a href="{{ route('home') }}" class="text-kinder-brown-400 hover:text-kinder-600 transition-colors inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="sr-only sm:not-sr-only">Acasă</span>
            </a>
        </li>

        @foreach ($items as $index => $item)
            <li class="flex items-center gap-1.5">
                {{-- Chevron separator --}}
                <svg class="w-3.5 h-3.5 text-kinder-brown-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>

                @if ($index === count($items) - 1)
                    {{-- Last item: plain text, not a link --}}
                    <span class="text-kinder-brown-700 font-semibold">
                        {{ $item['label'] }}
                    </span>
                @else
                    <a href="{{ $item['url'] }}" class="text-kinder-brown-400 hover:text-kinder-600 transition-colors">
                        {{ $item['label'] }}
                    </a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
