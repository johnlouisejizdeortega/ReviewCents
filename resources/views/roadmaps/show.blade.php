<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <a href="{{ route('roadmaps.index') }}" class="text-sm text-gray-900 dark:text-white hover:underline">← Back to roadmaps</a>

        <div class="mt-4 flex flex-wrap items-center gap-2">
            <x-badge color="indigo">{{ $roadmap->category->name }}</x-badge>
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
                    <div class="h-full bg-gray-900 dark:bg-white transition-all" style="width: {{ $completion }}%"></div>
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
                                <button type="submit" class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 {{ $done ? 'bg-gray-900 dark:bg-white border-gray-900 dark:border-white text-white' : 'border-gray-300 dark:border-gray-600 text-transparent hover:border-gray-400' }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </button>
                            </form>
                        @else
                            <span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-xs font-bold">{{ $step->position }}</span>
                        @endauth
                        <div class="flex-1">
                            <h3 class="font-semibold {{ $done ? 'line-through text-gray-400' : '' }}">{{ $step->title }}</h3>
                            @if ($step->description)
                                <p class="mt-1 text-sm text-gray-500">{{ $step->description }}</p>
                            @endif
                            @if ($step->resource)
                                <a href="{{ route('resources.show', $step->resource) }}" class="mt-2 inline-flex text-xs font-medium text-gray-900 dark:text-white hover:underline">{{ $step->resource->title }}</a>
                            @endif
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        {{-- End-of-learning test --}}
        @if ($roadmap->quiz)
            <div class="mt-8">
                <x-card class="p-6 bg-gray-100 dark:bg-gray-800">
                    <div class="flex items-start gap-4">
                        <svg class="h-8 w-8 shrink-0 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <div class="flex-1">
                            <h2 class="text-lg font-bold">Final test: {{ $roadmap->quiz->title }}</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                {{ $roadmap->quiz->questions->count() }} questions · pass mark {{ $roadmap->quiz->passing_score }}%
                            </p>
                            @if ($bestAttempt)
                                <p class="mt-2 text-sm">
                                    Best score:
                                    <span class="font-semibold {{ $bestAttempt->passed ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">{{ $bestAttempt->score }}%</span>
                                    @if ($bestAttempt->passed)
                                        <x-badge color="green">Passed ✓</x-badge>
                                    @endif
                                </p>
                            @endif
                            <div class="mt-4">
                                @auth
                                    <a href="{{ route('quiz.show', $roadmap) }}" class="inline-flex px-5 py-2.5 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">
                                        {{ $bestAttempt ? 'Retake the test' : 'Take the test' }}
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex px-5 py-2.5 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">Log in to take the test</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        @endif
    </div>
</x-app-layout>
