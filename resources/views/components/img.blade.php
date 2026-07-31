@props(['src', 'alt' => '', 'class' => ''])

<div x-data="{ loaded: false }" class="relative h-full w-full overflow-hidden">
    <div x-show="!loaded" class="absolute inset-0 animate-pulse bg-gray-200 dark:bg-gray-800"></div>
    <img src="{{ $src }}" alt="{{ $alt }}" loading="lazy"
         x-init="if ($el.complete && $el.naturalWidth > 0) loaded = true"
         @load="loaded = true"
         :class="loaded ? 'opacity-100' : 'opacity-0'"
         {{ $attributes->merge(['class' => 'transition-opacity duration-500 ' . $class]) }}>
</div>
