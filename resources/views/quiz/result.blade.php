<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <x-card class="p-8 text-center">
            <div class="text-5xl">{{ $attempt->passed ? '🎉' : '💪' }}</div>
            <h1 class="mt-3 text-2xl font-bold">{{ $attempt->passed ? 'You passed!' : 'Almost there!' }}</h1>
            <p class="mt-1 text-gray-500">{{ $roadmap->title }} — Final Test</p>
            <div class="mt-6 inline-flex flex-col items-center">
                <div class="text-5xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-amber-600' }}">{{ $attempt->score }}%</div>
                <p class="mt-1 text-sm text-gray-500">{{ $attempt->correct_count }} / {{ $attempt->total_count }} correct · pass mark {{ $quiz->passing_score }}%</p>
            </div>
            <div class="mt-6 flex flex-col sm:flex-row gap-2 justify-center">
                <a href="{{ route('quiz.show', $roadmap) }}" class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Retake test</a>
                <a href="{{ route('roadmaps.show', $roadmap) }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">Back to roadmap</a>
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">Dashboard</a>
            </div>
        </x-card>

        {{-- Answer review --}}
        <div class="mt-8 space-y-3">
            <h2 class="text-lg font-bold">Review your answers</h2>
            @foreach ($review as $item)
                <x-card class="p-4">
                    <div class="flex items-start gap-2">
                        <span class="mt-0.5">{{ $item['is_correct'] ? '✅' : '❌' }}</span>
                        <div class="flex-1">
                            <p class="font-medium">{{ $item['question']->question }}</p>
                            <div class="mt-2 space-y-1">
                                @foreach ($item['question']->options as $option)
                                    @php
                                        $isCorrect = $option->id === $item['correct_option_id'];
                                        $isChosen = $option->id === $item['chosen'];
                                    @endphp
                                    <div class="text-sm flex items-center gap-2 {{ $isCorrect ? 'text-green-700 dark:text-green-400 font-medium' : ($isChosen ? 'text-red-600 dark:text-red-400' : 'text-gray-500') }}">
                                        <span>{{ $isCorrect ? '•' : ($isChosen ? '×' : '·') }}</span>
                                        <span>{{ $option->text }}</span>
                                        @if ($isChosen && !$isCorrect)<span class="text-xs">(your answer)</span>@endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>
    </div>
</x-app-layout>
