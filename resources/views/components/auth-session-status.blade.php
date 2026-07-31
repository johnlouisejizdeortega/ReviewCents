@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-gray-900 dark:text-white']) }}>
        {{ $status }}
    </div>
@endif
