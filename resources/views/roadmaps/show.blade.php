<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <a href="{{ route('roadmaps.index') }}" class="text-sm text-indigo-600 hover:underline">← Back to roadmaps</a>

        <div class="mt-4 flex flex-wrap items-center gap-2">
            <x-badge color="indigo">{{ $roadmap->category->icon }} {{ $roadmap->category->name }}</x-badge>
            <x-badge :color="['beginner'=>'green','intermediate'=>'amber','advanced'=>'red'][$roadmap->level]">{{ ucfirst($roadmap->level) }}</x-badge>
        </div>
        <h1 class="mt-3 text-2xl sm:text-3xl font-bold">{{ $roadmap->title }}</h1>
        <p class="mt-3 text-gray-700 dark:text-gray-300">{{ $roadmap->description }}</p>

        @auth
            <div class="mt-5">
                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="font-medium">Your progress</span>
                    <span class="text-gray-500">{{ $completion }}%</span>
                </div>
                <div class="h-2 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                    <div class="h-full bg-indigo-600 transition-all" style="width: {{ $completion }}%"></div>
                </div>
            </div>
        @endauth

        {{-- Steps --}}
        <div class="mt-8 space-y-3">
            @foreach ($roadmap->steps as $step)
                @php $done = auth()->check() && $step->isCompletedBy(auth()->user()); @endphp
                <x-card class="p-4">
                    <div class="flex items-start gap-3">
                        @auth
                            <form method="POST" action="{{ route('progress.toggle', $step) }}">
                                @csrf
                                <button type="submit" class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 {{ $done ? 'bg-indigo-600 border-indigo-600 text-white' : 'border-gray-300 dark:border-gray-600 text-transparent hover:border-indigo-400' }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </button>
                            </form>
                        @else
                            <span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">{{ $step->position }}</span>
                        @endauth
                        <div class="flex-1">
                            <h3 class="font-semibold {{ $done ? 'line-through text-gray-400' : '' }}">{{ $step->title }}</h3>
                            @if ($step->description)
                                <p class="mt-1 text-sm text-gray-500">{{ $step->description }}</p>
                            @endif
                            @if ($step->resource)
                                <a href="{{ route('resources.show', $step->resource) }}" class="mt-2 inline-flex text-xs font-medium text-indigo-600 hover:underline">📚 {{ $step->resource->title }}</a>
                            @endif
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        {{-- End-of-learning test --}}
        @if ($roadmap->quiz)
            <div class="mt-8">
                <x-card class="p-6 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-950/40 dark:to-purple-950/40">
                    <div class="flex items-start gap-4">
                        <span class="text-3xl">📝</span>
                        <div class="flex-1">
                            <h2 class="text-lg font-bold">Final test: {{ $roadmap->quiz->title }}</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                {{ $roadmap->quiz->questions->count() }} questions · pass mark {{ $roadmap->quiz->passing_score }}%
                            </p>
                            @if ($bestAttempt)
                                <p class="mt-2 text-sm">
                                    Best score:
                                    <span class="font-semibold {{ $bestAttempt->passed ? 'text-green-600' : 'text-amber-600' }}">{{ $bestAttempt->score }}%</span>
                                    @if ($bestAttempt->passed)
                                        <x-badge color="green">Passed ✓</x-badge>
                                    @endif
                                </p>
                            @endif
                            <div class="mt-4">
                                @auth
                                    <a href="{{ route('quiz.show', $roadmap) }}" class="inline-flex px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">
                                        {{ $bestAttempt ? 'Retake the test' : 'Take the test' }}
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Log in to take the test</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        @endif
    </div>
</x-app-layout>
