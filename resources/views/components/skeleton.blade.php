@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'animate-pulse rounded-lg bg-gray-200 dark:bg-gray-800 ' . $class]) }}></div>
