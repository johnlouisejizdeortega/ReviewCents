<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold">Challenges &amp; missions</h1>
        <p class="text-sm text-gray-500 mt-1">Practice by building. Submit your work and get feedback from mentors.</p>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('challenges.index') }}" class="px-3 py-1.5 rounded-full text-sm font-medium {{ empty($filters['type']) ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900' : 'bg-gray-100 dark:bg-gray-800' }}">All</a>
            <a href="{{ route('challenges.index', ['type' => 'challenge']) }}" class="px-3 py-1.5 rounded-full text-sm font-medium {{ ($filters['type'] ?? '') === 'challenge' ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900' : 'bg-gray-100 dark:bg-gray-800' }}">Challenges</a>
            <a href="{{ route('challenges.index', ['type' => 'mission']) }}" class="px-3 py-1.5 rounded-full text-sm font-medium {{ ($filters['type'] ?? '') === 'mission' ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900' : 'bg-gray-100 dark:bg-gray-800' }}">Missions</a>
        </div>

        @if ($challenges->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($challenges as $challenge)
                    <x-reveal :delay="$loop->index % 6 * 60" class="h-full">
                        <x-card :href="route('challenges.show', $challenge)" class="p-5 h-full">
                            <div class="flex gap-2">
                                <x-badge :color="$challenge->type === 'mission' ? 'blue' : 'indigo'">{{ ucfirst($challenge->type) }}</x-badge>
                                <x-badge>{{ ucfirst($challenge->difficulty) }}</x-badge>
                            </div>
                            <h3 class="mt-3 font-semibold tracking-tightish">{{ $challenge->title }}</h3>
                            <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $challenge->description }}</p>
                            <div class="mt-3 flex items-center justify-between text-xs font-mono text-gray-400">
                                <span>{{ $challenge->points }} pts</span>
                                <span>{{ $challenge->submissions_count }} submissions</span>
                            </div>
                        </x-card>
                    </x-reveal>
                @endforeach
            </div>
            <div class="mt-6">{{ $challenges->links() }}</div>
        @else
            <x-empty-state title="No challenges yet" />
        @endif
    </div>
</x-app-layout>
