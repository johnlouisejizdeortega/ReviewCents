@props(['rating' => 0, 'count' => null])

@php $rounded = (int) round($rating); @endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1']) }}>
    <span class="flex items-center gap-0.5">
        @for ($i = 1; $i <= 5; $i++)
            <x-lucide-star class="size-3.5 {{ $i <= $rounded ? 'fill-current text-gray-900 dark:text-white' : 'text-gray-300 dark:text-gray-600' }}" />
        @endfor
    </span>
    @if ($rating)
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ number_format($rating, 1) }}</span>
    @endif
    @if (!is_null($count))
        <span class="text-xs text-gray-500">({{ $count }})</span>
    @endif
</span>
