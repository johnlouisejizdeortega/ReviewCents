@props(['delay' => 0, 'as' => 'div'])

<{{ $as }}
    x-data="{ shown: false }"
    x-intersect.once="shown = true"
    :class="shown && 'reveal-in'"
    style="transition-delay: {{ (int) $delay }}ms"
    {{ $attributes->merge(['class' => 'reveal']) }}
>
    {{ $slot }}
</{{ $as }}>
