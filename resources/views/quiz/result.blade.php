<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <x-card class="p-8 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full border-2 {{ $attempt->passed ? 'border-gray-900 dark:border-white' : 'border-gray-300 dark:border-gray-600' }}">
                <x-dynamic-component :component="'lucide-' . ($attempt->passed ? 'check' : 'rotate-ccw')" class="size-7 text-gray-900 dark:text-white" />
            </div>
            <h1 class="mt-3 text-2xl font-bold">{{ $attempt->passed ? 'You passed!' : 'Almost there!' }}</h1>
            <p class="mt-1 text-gray-500">{{ $roadmap->title }} — Final Test</p>
            <div class="mt-6 inline-flex flex-col items-center">
                <div class="text-5xl font-bold {{ $attempt->passed ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">{{ $attempt->score }}%</div>
                <p class="mt-1 text-sm text-gray-500">{{ $attempt->correct_count }} / {{ $attempt->total_count }} correct · pass mark {{ $quiz->passing_score }}%</p>
            </div>
            <div class="mt-6 flex flex-col sm:flex-row gap-2 justify-center">
                <a href="{{ route('quiz.show', $roadmap) }}" class="px-5 py-2.5 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">Retake test</a>
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
                        <span class="mt-0.5 font-bold {{ $item['is_correct'] ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">{{ $item['is_correct'] ? '✓' : '✗' }}</span>
                        <div class="flex-1">
                            <p class="font-medium">{{ $item['question']->question }}</p>
                            <div class="mt-2 space-y-1">
                                @foreach ($item['question']->options as $option)
                                    @php
                                        $isCorrect = $option->id === $item['correct_option_id'];
                                        $isChosen = $option->id === $item['chosen'];
                                    @endphp
                                    <div class="text-sm flex items-center gap-2 {{ $isCorrect ? 'text-gray-900 dark:text-white font-medium' : ($isChosen ? 'text-gray-500 dark:text-gray-400' : 'text-gray-500') }}">
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
