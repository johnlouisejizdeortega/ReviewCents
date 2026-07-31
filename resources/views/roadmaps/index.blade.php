<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold">Learning roadmaps</h1>
        <p class="text-sm text-gray-500 mt-1">Step-by-step paths that end with a test to prove what you learned.</p>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form method="GET" class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('roadmaps.index') }}" class="px-3 py-1.5 rounded-full text-sm font-medium {{ empty($filters['category']) ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">All</a>
            @foreach ($categories as $cat)
                <a href="{{ route('roadmaps.index', ['category' => $cat->slug]) }}" class="px-3 py-1.5 rounded-full text-sm font-medium {{ ($filters['category'] ?? '') === $cat->slug ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">{{ $cat->name }}</a>
            @endforeach
        </form>

        @if ($roadmaps->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($roadmaps as $roadmap)
                    <x-reveal :delay="$loop->index % 6 * 60" class="h-full">
                        <x-card :href="route('roadmaps.show', $roadmap)" class="p-5 h-full">
                            <div class="flex items-center justify-between">
                                <x-badge>{{ $roadmap->category->name }}</x-badge>
                                <x-badge>{{ ucfirst($roadmap->level) }}</x-badge>
                            </div>
                            <h3 class="mt-3 font-semibold tracking-tightish">{{ $roadmap->title }}</h3>
                            <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $roadmap->description }}</p>
                            <p class="mt-3 text-xs font-mono text-gray-400">{{ $roadmap->steps_count }} steps · test included</p>
                            @auth
                                @php $c = $roadmap->completionFor(auth()->user()); @endphp
                                @if ($c > 0)
                                    <div class="mt-3">
                                        <div class="h-1.5 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                            <div class="h-full bg-gray-900 dark:bg-white" style="width: {{ $c }}%"></div>
                                        </div>
                                        <p class="mt-1 text-xs font-mono text-gray-400">{{ $c }}% complete</p>
                                    </div>
                                @endif
                            @endauth
                        </x-card>
                    </x-reveal>
                @endforeach
            </div>
            <div class="mt-6">{{ $roadmaps->links() }}</div>
        @else
            <x-empty-state title="No roadmaps yet" />
        @endif
    </div>
</x-app-layout>
