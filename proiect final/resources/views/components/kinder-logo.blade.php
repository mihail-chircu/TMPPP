@props(['size' => 'md'])

@php
    $sizes = [
        'sm' => ['class' => 'h-8', 'src' => 'kinder-logo-sm.png'],
        'md' => ['class' => 'h-10', 'src' => 'kinder-logo-md.png'],
        'lg' => ['class' => 'h-14', 'src' => 'kinder-logo-lg.png'],
        'xl' => ['class' => 'h-20', 'src' => 'kinder-logo-xl.png'],
    ];
    $current = $sizes[$size] ?? $sizes['md'];
@endphp

<img
    src="{{ asset('images/' . $current['src']) }}"
    alt="Kinder - magazin pentru copii"
    class="{{ $current['class'] }} w-auto object-contain {{ $attributes->get('class', '') }}"
>
