@props(['title' => 'Nothing here yet', 'message' => null, 'icon' => 'inbox'])

<div {{ $attributes->merge(['class' => 'text-center py-14 px-4']) }}>
    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl border border-gray-200 dark:border-gray-800 text-gray-400">
        <x-dynamic-component :component="'lucide-' . $icon" class="size-6" />
    </div>
    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
    @if ($message)
        <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">{{ $message }}</p>
    @endif
    @if (isset($action))
        <div class="mt-5">{{ $action }}</div>
    @endif
</div>
