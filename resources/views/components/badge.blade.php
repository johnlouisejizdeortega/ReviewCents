@props(['color' => 'gray'])

@php
    // Monochrome badges: "solid" (dark) for emphasis, "soft" (light) for the rest.
    $solid = ['indigo', 'green', 'blue', 'black'];
    $cls = in_array($color, $solid)
        ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
        : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 ring-1 ring-inset ring-gray-200 dark:ring-gray-700';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium $cls"]) }}>
    {{ $slot }}
</span>
