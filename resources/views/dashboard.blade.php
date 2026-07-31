<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }} 👋</h1>
        <p class="text-sm text-gray-500 mt-1">Here's your learning progress and results.</p>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">
        {{-- Stat tiles --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ([
                ['label' => 'Steps completed', 'value' => $stats['stepsCompleted'], 'icon' => '✅'],
                ['label' => 'Tests passed', 'value' => $stats['quizzesPassed'], 'icon' => '📝'],
                ['label' => 'Reviews written', 'value' => $stats['reviews'], 'icon' => '⭐'],
                ['label' => 'Showcase projects', 'value' => $stats['projects'], 'icon' => '🚀'],
            ] as $tile)
                <x-card class="p-5">
                    <div class="text-2xl">{{ $tile['icon'] }}</div>
                    <div class="mt-2 text-2xl font-bold">{{ $tile['value'] }}</div>
                    <div class="text-xs text-gray-500">{{ $tile['label'] }}</div>
                </x-card>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Roadmaps in progress --}}
            <section>
                <h2 class="text-lg font-bold mb-3">Your roadmaps</h2>
                @forelse ($roadmaps as $item)
                    <x-card :href="route('roadmaps.show', $item['roadmap'])" class="p-4 mb-3">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold">{{ $item['roadmap']->title }}</h3>
                            <span class="text-sm text-gray-500">{{ $item['completion'] }}%</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                            <div class="h-full bg-indigo-600" style="width: {{ $item['completion'] }}%"></div>
                        </div>
                    </x-card>
                @empty
                    <x-card class="p-6 text-center text-sm text-gray-500">
                        You haven't started a roadmap yet.
                        <a href="{{ route('roadmaps.index') }}" class="text-indigo-600 font-medium hover:underline">Browse roadmaps →</a>
                    </x-card>
                @endforelse
            </section>

            {{-- Test results --}}
            <section>
                <h2 class="text-lg font-bold mb-3">Test results</h2>
                @forelse ($attempts as $attempt)
                    <x-card class="p-4 mb-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold">{{ $attempt->quiz->roadmap->title ?? 'Quiz' }}</h3>
                                <p class="text-xs text-gray-400">{{ $attempt->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <div class="font-bold {{ $attempt->passed ? 'text-green-600' : 'text-amber-600' }}">{{ $attempt->score }}%</div>
                                @if ($attempt->passed)<x-badge color="green">Passed</x-badge>@else<x-badge color="amber">Retry</x-badge>@endif
                            </div>
                        </div>
                    </x-card>
                @empty
                    <x-card class="p-6 text-center text-sm text-gray-500">No tests taken yet.</x-card>
                @endforelse
            </section>
        </div>

        {{-- Assigned tasks --}}
        <section>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-bold">Tasks from your mentor</h2>
                <a href="{{ route('assignments.index') }}" class="text-sm text-indigo-600 hover:underline">View all →</a>
            </div>
            @forelse ($assignments->take(3) as $assignment)
                <x-card class="p-4 mb-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold">{{ $assignment->title }}</h3>
                        <x-badge :color="['assigned'=>'amber','submitted'=>'blue','reviewed'=>'green'][$assignment->status]">{{ ucfirst($assignment->status) }}</x-badge>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $assignment->description }}</p>
                    @if ($assignment->status === 'reviewed')
                        <div class="mt-2 flex items-center gap-2 text-sm"><x-stars :rating="$assignment->rating" /> <span class="text-gray-500">{{ $assignment->feedback }}</span></div>
                    @endif
                </x-card>
            @empty
                <x-card class="p-6 text-center text-sm text-gray-500">No tasks assigned yet.</x-card>
            @endforelse
        </section>
    </div>
</x-app-layout>
