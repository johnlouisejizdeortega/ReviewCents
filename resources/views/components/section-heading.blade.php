@props([
    'eyebrow' => null,
    'title' => null,
    'href' => null,
    'linkLabel' => 'View all',
])

<div {{ $attributes->merge(['class' => 'flex items-end justify-between gap-4 mb-5']) }}>
    <div>
        @if ($eyebrow)
            <p class="text-xs font-mono uppercase tracking-widest text-gray-400 mb-1">{{ $eyebrow }}</p>
        @endif
        <h2 class="text-xl sm:text-2xl font-bold tracking-tightish">{{ $title ?? $slot }}</h2>
    </div>
    @if ($href)
        <a href="{{ $href }}" class="shrink-0 inline-flex items-center gap-1 text-sm font-medium text-gray-900 dark:text-white link-underline">
            {{ $linkLabel }}
            <x-lucide-arrow-right class="size-4" />
        </a>
    @endif
</div>
