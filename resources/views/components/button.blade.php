@props([
    'variant' => 'solid',
    'size' => 'md',
    'href' => null,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 font-semibold rounded-xl transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-900 dark:focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-gray-950 active:translate-y-px disabled:opacity-50 disabled:pointer-events-none';

    $variants = [
        'solid' => 'bg-gray-900 text-white hover:bg-gray-700 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200',
        'outline' => 'border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 hover:border-gray-900 dark:hover:border-white hover:bg-gray-50 dark:hover:bg-gray-900',
        'ghost' => 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800',
        // Fixed light-on-dark variants for the always-dark hero (mode-independent).
        'invert' => 'bg-white text-gray-900 hover:bg-gray-200',
        'invert-outline' => 'border border-white/30 text-white hover:bg-white/10 hover:border-white/60',
    ];

    $sizes = [
        'sm' => 'text-xs px-3 py-1.5',
        'md' => 'text-sm px-4 py-2.5',
        'lg' => 'text-base px-6 py-3',
    ];

    $classes = trim("$base {$variants[$variant]} {$sizes[$size]}");
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>{{ $slot }}</button>
@endif
