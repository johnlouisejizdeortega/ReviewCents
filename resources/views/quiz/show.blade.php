<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6"
         x-data="{ step: 0, total: {{ $quiz->questions->count() }}, answers: {} }">
        <a href="{{ route('roadmaps.show', $roadmap) }}" class="text-sm text-indigo-600 hover:underline">← Back to roadmap</a>
        <h1 class="mt-4 text-2xl font-bold">{{ $quiz->title }}</h1>
        <p class="mt-1 text-sm text-gray-500">Answer all {{ $quiz->questions->count() }} questions. You need {{ $quiz->passing_score }}% to pass.</p>

        {{-- progress --}}
        <div class="mt-4 h-2 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
            <div class="h-full bg-indigo-600 transition-all" :style="`width: ${((step) / total) * 100}%`"></div>
        </div>

        <form method="POST" action="{{ route('quiz.submit', $roadmap) }}" class="mt-6">
            @csrf
            @foreach ($quiz->questions as $index => $question)
                <div x-show="step === {{ $index }}" x-cloak>
                    <x-card class="p-6">
                        <p class="text-xs font-medium text-indigo-600">Question {{ $index + 1 }} of {{ $quiz->questions->count() }}</p>
                        <h2 class="mt-2 text-lg font-semibold">{{ $question->question }}</h2>
                        <div class="mt-4 space-y-2">
                            @foreach ($question->options as $option)
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:border-indigo-400 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-950">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                           @change="answers[{{ $question->id }}] = {{ $option->id }}"
                                           class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-sm">{{ $option->text }}</span>
                                </label>
                            @endforeach
                        </div>
                    </x-card>
                    <div class="mt-4 flex justify-between">
                        <button type="button" @click="step--" x-show="step > 0" class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">← Previous</button>
                        <span></span>
                        @if ($index < $quiz->questions->count() - 1)
                            <button type="button" @click="if (answers[{{ $question->id }}]) step++" class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Next →</button>
                        @else
                            <button type="submit" class="px-5 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700">Submit test</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </form>
    </div>
</x-app-layout>
