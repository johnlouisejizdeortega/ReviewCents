<x-app-layout>
    {{-- Hero with animated terminal background --}}
    <section class="relative overflow-hidden bg-gray-950 text-white">
        {{-- Terminal background --}}
        <div class="absolute inset-0 flex items-center justify-center px-4 pointer-events-none">
            <x-terminal-hero class="w-full max-w-3xl opacity-[0.28] sm:opacity-40 translate-y-4" />
        </div>
        {{-- Scrim for legibility --}}
        <div class="absolute inset-0 bg-gradient-to-b from-gray-950/80 via-gray-950/55 to-gray-950"></div>
        <div class="absolute inset-0 bg-[radial-gradient(60%_50%_at_50%_35%,transparent,theme(colors.gray.950))]"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 text-center">
            <x-reveal>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 px-3 py-1 text-xs font-mono uppercase tracking-widest text-white/70">
                    Learn · Review · Level up
                </span>
            </x-reveal>
            <x-reveal :delay="80">
                <h1 class="mt-6 text-4xl sm:text-6xl font-bold tracking-tightish text-balance">
                    Learn to code,<br class="hidden sm:block"> the minimalist way.
                </h1>
            </x-reveal>
            <x-reveal :delay="160">
                <p class="mt-5 max-w-2xl mx-auto text-base sm:text-lg text-white/70">
                    Curated roadmaps, honest resource reviews, end-of-learning tests, real challenges,
                    and mentor feedback for developers &amp; web designers — from your phone.
                </p>
            </x-reveal>
            <x-reveal :delay="240">
                <div class="mt-9 flex flex-col sm:flex-row gap-3 justify-center">
                    @guest
                        <x-button :href="route('register')" variant="invert" size="lg">
                            Start learning free <x-lucide-arrow-right class="size-4" />
                        </x-button>
                        <x-button :href="route('roadmaps.index')" variant="invert-outline" size="lg">
                            Browse roadmaps
                        </x-button>
                    @else
                        <x-button :href="route('dashboard')" variant="invert" size="lg">
                            Go to dashboard <x-lucide-arrow-right class="size-4" />
                        </x-button>
                        <x-button :href="route('roadmaps.index')" variant="invert-outline" size="lg">
                            Browse roadmaps
                        </x-button>
                    @endguest
                </div>
            </x-reveal>
            <x-reveal :delay="320">
                <div class="mt-12 grid grid-cols-3 gap-4 max-w-md mx-auto font-mono">
                    <div><div class="text-2xl font-bold">{{ $stats['roadmaps'] }}</div><div class="text-xs text-white/50 mt-1">roadmaps</div></div>
                    <div><div class="text-2xl font-bold">{{ $stats['resources'] }}</div><div class="text-xs text-white/50 mt-1">resources</div></div>
                    <div><div class="text-2xl font-bold">{{ $stats['challenges'] }}</div><div class="text-xs text-white/50 mt-1">challenges</div></div>
                </div>
            </x-reveal>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">
        {{-- Featured roadmaps --}}
        <section>
            <x-reveal><x-section-heading eyebrow="Guided paths" title="Learning roadmaps" :href="route('roadmaps.index')" /></x-reveal>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($roadmaps as $roadmap)
                    <x-reveal :delay="$loop->index * 70" class="h-full">
                        <x-card :href="route('roadmaps.show', $roadmap)" class="p-5 h-full">
                            <x-badge>{{ $roadmap->category->name }}</x-badge>
                            <h3 class="mt-3 font-semibold tracking-tightish">{{ $roadmap->title }}</h3>
                            <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $roadmap->description }}</p>
                            <p class="mt-3 text-xs font-mono text-gray-400">{{ $roadmap->steps_count }} steps · {{ $roadmap->level }}</p>
                        </x-card>
                    </x-reveal>
                @endforeach
            </div>
        </section>

        {{-- Top resources --}}
        <section>
            <x-reveal><x-section-heading eyebrow="Rated by the community" title="Top-rated resources" :href="route('resources.index')" /></x-reveal>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($topResources as $resource)
                    <x-reveal :delay="$loop->index * 70" class="h-full">
                        <x-card :href="route('resources.show', $resource)" class="p-5 h-full">
                            <x-badge>{{ ucfirst($resource->type) }}</x-badge>
                            <h3 class="mt-3 font-semibold tracking-tightish">{{ $resource->title }}</h3>
                            <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $resource->description }}</p>
                            <div class="mt-3"><x-stars :rating="$resource->avg_rating" :count="$resource->reviews_count" /></div>
                        </x-card>
                    </x-reveal>
                @endforeach
            </div>
        </section>

        {{-- Challenges --}}
        <section>
            <x-reveal><x-section-heading eyebrow="Practice by building" title="Challenges & missions" :href="route('challenges.index')" /></x-reveal>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($challenges as $challenge)
                    <x-reveal :delay="$loop->index * 70" class="h-full">
                        <x-card :href="route('challenges.show', $challenge)" class="p-5 h-full">
                            <div class="flex gap-2">
                                <x-badge :color="$challenge->type === 'mission' ? 'blue' : 'indigo'">{{ ucfirst($challenge->type) }}</x-badge>
                                <x-badge>{{ ucfirst($challenge->difficulty) }}</x-badge>
                            </div>
                            <h3 class="mt-3 font-semibold tracking-tightish">{{ $challenge->title }}</h3>
                            <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $challenge->description }}</p>
                            <p class="mt-3 text-xs font-mono text-gray-400">{{ $challenge->points }} pts</p>
                        </x-card>
                    </x-reveal>
                @endforeach
            </div>
        </section>

        {{-- Showcase --}}
        @if ($projects->count())
            <section>
                <x-reveal><x-section-heading eyebrow="Built by learners" title="From the community" :href="route('showcase.index')" linkLabel="View showcase" /></x-reveal>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach ($projects as $project)
                        <x-reveal :delay="$loop->index * 70" class="h-full">
                            <x-card :href="route('showcase.show', $project)" class="overflow-hidden h-full">
                                <div class="aspect-video bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    @if ($project->imageUrl())
                                        <img src="{{ $project->imageUrl() }}" alt="" class="h-full w-full object-cover">
                                    @else
                                        <x-placeholder-icon size="size-10" />
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold tracking-tightish">{{ $project->title }}</h3>
                                    <p class="mt-1 text-xs text-gray-500">by {{ $project->user->name }}</p>
                                </div>
                            </x-card>
                        </x-reveal>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
