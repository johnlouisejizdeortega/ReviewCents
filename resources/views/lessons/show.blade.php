<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6"
         x-data="lesson({
            cards: {{ Illuminate\Support\Js::from($cards->map(fn ($c) => ['front' => $c->front, 'back' => $c->back, 'hint' => $c->hint])) }},
            start: {{ (int) $resume }},
            stepId: {{ $step->id }},
            saveUrl: '{{ route('lessons.save', $step) }}',
            roadmapUrl: '{{ route('roadmaps.show', $roadmap) }}',
            quizUrl: '{{ $roadmap->quiz ? route('quiz.show', $roadmap) : '' }}',
            csrf: '{{ csrf_token() }}'
         })">

        <a href="{{ route('roadmaps.show', $roadmap) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-900 dark:hover:text-white link-underline">
            <x-lucide-arrow-left class="size-4" /> {{ $roadmap->title }}
        </a>

        <div class="mt-3 flex items-baseline justify-between">
            <div>
                <p class="text-xs font-mono uppercase tracking-widest text-gray-400">Lesson</p>
                <h1 class="text-2xl font-bold tracking-tightish">{{ $step->title }}</h1>
            </div>
            <span class="text-sm font-mono text-gray-400" x-show="!done">
                <span x-text="i + 1"></span>/<span x-text="cards.length"></span>
            </span>
        </div>

        {{-- progress --}}
        <div class="mt-3 h-1.5 rounded-full bg-gray-200 dark:bg-gray-800 overflow-hidden" x-show="!done">
            <div class="h-full bg-gray-900 dark:bg-white transition-all duration-300" :style="`width: ${((i + 1) / cards.length) * 100}%`"></div>
        </div>

        @if ($cards->isEmpty())
            <x-card class="mt-6 p-8 text-center">
                <p class="text-gray-500">This lesson doesn't have cards yet.</p>
                <x-button :href="route('roadmaps.show', $roadmap)" variant="outline" class="mt-4">Back to roadmap</x-button>
            </x-card>
        @else
            {{-- Flashcard --}}
            <div x-show="!done" class="mt-6">
                <div class="[perspective:1400px]">
                    <div @click="flip()" role="button" tabindex="0" @keydown.enter="flip()" @keydown.space.prevent="flip()"
                         class="relative h-72 sm:h-80 w-full cursor-pointer transition-transform duration-500 [transform-style:preserve-3d]"
                         :class="flipped && '[transform:rotateY(180deg)]'">
                        {{-- Front --}}
                        <div class="absolute inset-0 [backface-visibility:hidden] rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-soft flex flex-col items-center justify-center text-center p-8">
                            <p class="text-xs font-mono uppercase tracking-widest text-gray-400 mb-3">Concept</p>
                            <p class="text-xl sm:text-2xl font-semibold tracking-tightish" x-text="cards[i].front"></p>
                            <p class="mt-4 text-xs text-gray-400 flex items-center gap-1"><x-lucide-rotate-cw class="size-3.5" /> tap to flip</p>
                            <template x-if="cards[i].hint">
                                <p class="mt-3 text-sm text-gray-500 italic" x-text="'Hint: ' + cards[i].hint"></p>
                            </template>
                        </div>
                        {{-- Back --}}
                        <div class="absolute inset-0 [backface-visibility:hidden] [transform:rotateY(180deg)] rounded-2xl border border-gray-900 dark:border-white bg-gray-900 dark:bg-white text-white dark:text-gray-900 shadow-soft flex flex-col justify-center p-8 overflow-y-auto">
                            <p class="text-xs font-mono uppercase tracking-widest opacity-60 mb-3">Explanation</p>
                            <p class="text-base sm:text-lg leading-relaxed whitespace-pre-line" x-text="cards[i].back"></p>
                        </div>
                    </div>
                </div>

                {{-- Controls --}}
                <div class="mt-5 flex items-center justify-between gap-3">
                    <x-button type="button" variant="outline" @click="prev()" x-bind:disabled="i === 0" ::class="i === 0 && 'opacity-40 pointer-events-none'">
                        <x-lucide-arrow-left class="size-4" /> Prev
                    </x-button>

                    <button type="button" @click="flip()" class="text-sm text-gray-500 hover:text-gray-900 dark:hover:text-white link-underline">Flip card</button>

                    <template x-if="i < cards.length - 1">
                        <x-button type="button" @click="next()">Next <x-lucide-arrow-right class="size-4" /></x-button>
                    </template>
                    <template x-if="i === cards.length - 1">
                        <x-button type="button" @click="finish()">Finish <x-lucide-check class="size-4" /></x-button>
                    </template>
                </div>
                <p class="mt-3 text-center text-xs text-gray-400">Your place is saved automatically — you can leave and come back.</p>
            </div>

            {{-- Completion --}}
            <div x-show="done" x-cloak class="mt-6">
                <x-card class="p-8 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full border-2 border-gray-900 dark:border-white">
                        <x-lucide-check class="size-7" />
                    </div>
                    <h2 class="mt-3 text-xl font-bold tracking-tightish">Lesson complete</h2>
                    <p class="mt-1 text-gray-500">Nice work finishing “{{ $step->title }}”.</p>
                    <div class="mt-6 flex flex-col sm:flex-row gap-2 justify-center">
                        @if ($roadmap->quiz)
                            <x-button :href="route('quiz.show', $roadmap)">Take the test <x-lucide-arrow-right class="size-4" /></x-button>
                        @endif
                        <x-button :href="route('roadmaps.show', $roadmap)" variant="outline">Back to roadmap</x-button>
                    </div>
                </x-card>
            </div>
        @endif
    </div>

    @push('head')
    <script>
        function lesson(cfg) {
            return {
                cards: cfg.cards,
                i: Math.min(cfg.start || 0, Math.max((cfg.cards.length || 1) - 1, 0)),
                flipped: false,
                done: false,
                init() {
                    // Save the reading position when the user leaves or backgrounds the tab.
                    const persist = () => this.save(false);
                    document.addEventListener('visibilitychange', () => { if (document.visibilityState === 'hidden') persist(); });
                    window.addEventListener('pagehide', persist);
                },
                flip() { this.flipped = !this.flipped; },
                next() { if (this.i < this.cards.length - 1) { this.i++; this.flipped = false; this.save(false); } },
                prev() { if (this.i > 0) { this.i--; this.flipped = false; this.save(false); } },
                finish() { this.save(true); this.done = true; },
                save(completed) {
                    try { localStorage.setItem('lesson.' + cfg.stepId, this.i); } catch (e) {}
                    try {
                        fetch(cfg.saveUrl, {
                            method: 'POST',
                            keepalive: true,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': cfg.csrf,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ last_card: this.i, completed: !!completed }),
                        });
                    } catch (e) {}
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
