<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Admin panel</h1></x-slot>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('admin._nav')
        <h2 class="text-lg font-bold">End-of-learning test</h2>
        <p class="text-sm text-gray-500 mb-4">For roadmap: <span class="font-medium">{{ $roadmap->title }}</span> — minimum 3 questions.</p>

        @php
            $initial = old('questions');
            if (!$initial) {
                if ($quiz && $quiz->questions->count()) {
                    $initial = $quiz->questions->map(function ($q) {
                        $opts = $q->options->values();
                        $correct = $opts->search(fn($o) => $o->is_correct);
                        return [
                            'question' => $q->question,
                            'options' => $opts->pluck('text')->toArray(),
                            'correct' => $correct === false ? 0 : $correct,
                        ];
                    })->toArray();
                } else {
                    $initial = [
                        ['question'=>'','options'=>['','','',''],'correct'=>0],
                        ['question'=>'','options'=>['','','',''],'correct'=>0],
                        ['question'=>'','options'=>['','','',''],'correct'=>0],
                    ];
                }
            }
        @endphp

        <x-card class="p-6">
            <form method="POST" action="{{ route('admin.roadmaps.quiz.update', $roadmap) }}"
                  x-data="{ questions: {{ Illuminate\Support\Js::from($initial) }} }">
                @csrf @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="title" value="Test title" />
                        <x-text-input id="title" name="title" class="mt-1 block w-full" :value="old('title', $quiz->title ?? $roadmap->title.' — Final Test')" required />
                    </div>
                    <div>
                        <x-input-label for="passing_score" value="Pass mark (%)" />
                        <x-text-input id="passing_score" name="passing_score" type="number" min="0" max="100" class="mt-1 block w-full" :value="old('passing_score', $quiz->passing_score ?? 60)" required />
                    </div>
                </div>

                <x-input-error :messages="$errors->get('questions')" class="mt-3" />

                <div class="mt-5 space-y-4">
                    <template x-for="(q, qi) in questions" :key="qi">
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-semibold text-gray-900 dark:text-white" x-text="`Question ${qi+1}`"></span>
                                <button type="button" @click="questions.splice(qi,1)" x-show="questions.length > 3" class="text-gray-500 text-sm">Remove</button>
                            </div>
                            <input type="text" :name="`questions[${qi}][question]`" x-model="q.question" placeholder="Question text" required
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
                            <p class="mt-2 text-xs text-gray-400">Select the radio for the correct answer.</p>
                            <div class="mt-1 space-y-1">
                                <template x-for="(opt, oi) in q.options" :key="oi">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" :name="`questions[${qi}][correct]`" :value="oi" x-model.number="q.correct" class="text-gray-900 dark:text-white focus:ring-gray-900 dark:focus:ring-white">
                                        <input type="text" :name="`questions[${qi}][options][${oi}]`" x-model="q.options[oi]" placeholder="Option" required
                                               class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <button type="button" @click="questions.push({question:'',options:['','','',''],correct:0})" class="mt-3 text-sm text-gray-900 dark:text-white hover:underline">+ Add question</button>

                <div class="mt-6 flex gap-2">
                    <button class="px-5 py-2.5 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">Save test</button>
                    <a href="{{ route('admin.roadmaps.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
