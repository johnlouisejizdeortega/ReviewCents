@php
    // Each snippet is an array of code lines that type out in sequence, then loop.
    $snippets = [
        [
            '// roadmap: frontend developer',
            'const learn = (topic) =>',
            '  study(topic).then(review);',
            '',
            "learn('html & css');",
            "learn('javascript');",
            '// level up ↑',
        ],
        [
            '$ reviewcents test --final',
            'running end-of-learning test...',
            'q1 ................ pass',
            'q2 ................ pass',
            'q3 ................ pass',
            'score: 100%  — passed',
        ],
        [
            '<section class="hero">',
            '  <h1>Ship your first',
            '     project</h1>',
            '  <button>Start</button>',
            '</section>',
        ],
    ];
@endphp

<div aria-hidden="true" {{ $attributes->merge(['class' => 'pointer-events-none select-none']) }}>
    <div class="mx-auto w-full max-w-2xl rounded-2xl border border-white/10 bg-black/60 backdrop-blur-sm overflow-hidden shadow-2xl">
        {{-- Title bar --}}
        <div class="flex items-center gap-2 px-4 py-3 border-b border-white/10">
            <span class="h-3 w-3 rounded-full border border-white/30"></span>
            <span class="h-3 w-3 rounded-full border border-white/30"></span>
            <span class="h-3 w-3 rounded-full border border-white/30"></span>
            <span class="ml-3 font-mono text-xs text-white/40">~/reviewcents</span>
        </div>
        {{-- Body --}}
        <div class="p-4 sm:p-6 font-mono text-sm sm:text-base leading-relaxed text-white/80 h-64 sm:h-72 overflow-hidden"
             x-data="terminal({{ Illuminate\Support\Js::from($snippets) }})">
            <template x-for="(line, i) in lines" :key="i">
                <div class="whitespace-pre-wrap break-words min-h-[1.5em]" x-text="line || ' '"></div>
            </template>
            <div class="whitespace-pre-wrap break-words min-h-[1.5em]" x-show="!done">
                <span x-text="current"></span><span class="inline-block w-2 -mb-0.5 h-[1.1em] bg-white/70 animate-blink"></span>
            </div>
        </div>
    </div>
</div>
