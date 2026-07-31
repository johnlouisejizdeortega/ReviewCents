@props(['href' => null])

@php
    $base = 'block rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900';
    $interactive = 'transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-600 hover:-translate-y-0.5 hover:shadow-soft';
    $classes = $base . ' ' . ($href ? $interactive : 'shadow-soft');
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <div {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </div>
@endif
