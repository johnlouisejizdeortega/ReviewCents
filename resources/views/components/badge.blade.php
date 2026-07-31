@props(['color' => 'gray'])

@php
    // Monochrome badges: "solid" (dark) for emphasis, "soft" (hairline) for the rest.
    $solid = ['indigo', 'green', 'blue', 'black'];
    $cls = in_array($color, $solid)
        ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
        : 'text-gray-600 dark:text-gray-300 ring-1 ring-inset ring-gray-200 dark:ring-gray-700';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium tracking-wide $cls"]) }}>
    {{ $slot }}
</span>
