@props(['rating' => 0, 'count' => null])

@php $rounded = (int) round($rating); @endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1']) }}>
    <span class="flex">
        @for ($i = 1; $i <= 5; $i++)
            <svg class="h-4 w-4 {{ $i <= $rounded ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.17c.969 0 1.371 1.24.588 1.81l-3.376 2.454a1 1 0 00-.363 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.375-2.454a1 1 0 00-1.176 0l-3.375 2.454c-.784.57-1.838-.196-1.539-1.118l1.287-3.966a1 1 0 00-.363-1.118L2.98 9.393c-.783-.57-.38-1.81.588-1.81h4.17a1 1 0 00.95-.69l1.286-3.966z" />
            </svg>
        @endfor
    </span>
    @if ($rating)
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ number_format($rating, 1) }}</span>
    @endif
    @if (!is_null($count))
        <span class="text-xs text-gray-500">({{ $count }})</span>
    @endif
</span>
