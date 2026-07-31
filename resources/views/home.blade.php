<x-app-layout>
    {{-- Hero --}}
    <section class="bg-gray-900 dark:bg-black border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 text-center text-white">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/20 px-3 py-1 text-xs font-medium">
                Learn · Review · Level up
            </span>
            <h1 class="mt-5 text-3xl sm:text-5xl font-bold tracking-tight break-words">
                Learn web dev &amp; design,<br class="hidden sm:block"> the mobile-first way.
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-gray-300">
                Follow curated roadmaps, review the best learning resources, take end-of-learning tests,
                tackle challenges, and show off your progress — all from your phone.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                @guest
                    <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-6 py-3 rounded-xl bg-white text-gray-900 font-semibold hover:bg-gray-200 transition">Start learning free</a>
                    <a href="{{ route('roadmaps.index') }}" class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-white/30 text-white font-semibold hover:bg-white/10 transition">Browse roadmaps</a>
                @else
                    <a href="{{ route('dashboard') }}" class="inline-flex justify-center items-center px-6 py-3 rounded-xl bg-white text-gray-900 font-semibold hover:bg-gray-200 transition">Go to dashboard</a>
                    <a href="{{ route('roadmaps.index') }}" class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-white/30 text-white font-semibold hover:bg-white/10 transition">Browse roadmaps</a>
                @endguest
            </div>
            <div class="mt-10 grid grid-cols-3 gap-4 max-w-md mx-auto">
                <div><div class="text-2xl font-bold">{{ $stats['roadmaps'] }}</div><div class="text-xs text-gray-400">Roadmaps</div></div>
                <div><div class="text-2xl font-bold">{{ $stats['resources'] }}</div><div class="text-xs text-gray-400">Resources</div></div>
                <div><div class="text-2xl font-bold">{{ $stats['challenges'] }}</div><div class="text-xs text-gray-400">Challenges</div></div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-14">
        {{-- Featured roadmaps --}}
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold">Learning roadmaps</h2>
                <a href="{{ route('roadmaps.index') }}" class="text-sm font-medium text-gray-900 dark:text-white hover:underline">View all →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($roadmaps as $roadmap)
                    <x-card :href="route('roadmaps.show', $roadmap)" class="p-5">
                        <x-badge>{{ $roadmap->category->name }}</x-badge>
                        <h3 class="mt-3 font-semibold">{{ $roadmap->title }}</h3>
                        <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $roadmap->description }}</p>
                        <p class="mt-3 text-xs text-gray-400">{{ $roadmap->steps_count }} steps · {{ ucfirst($roadmap->level) }}</p>
                    </x-card>
                @endforeach
            </div>
        </section>

        {{-- Top resources --}}
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold">Top-rated resources</h2>
                <a href="{{ route('resources.index') }}" class="text-sm font-medium text-gray-900 dark:text-white hover:underline">View all →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($topResources as $resource)
                    <x-card :href="route('resources.show', $resource)" class="p-5">
                        <x-badge>{{ ucfirst($resource->type) }}</x-badge>
                        <h3 class="mt-3 font-semibold">{{ $resource->title }}</h3>
                        <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $resource->description }}</p>
                        <div class="mt-3"><x-stars :rating="$resource->avg_rating" :count="$resource->reviews_count" /></div>
                    </x-card>
                @endforeach
            </div>
        </section>

        {{-- Challenges --}}
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold">Challenges &amp; missions</h2>
                <a href="{{ route('challenges.index') }}" class="text-sm font-medium text-gray-900 dark:text-white hover:underline">View all →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($challenges as $challenge)
                    <x-card :href="route('challenges.show', $challenge)" class="p-5">
                        <div class="flex gap-2">
                            <x-badge :color="$challenge->type === 'mission' ? 'blue' : 'indigo'">{{ ucfirst($challenge->type) }}</x-badge>
                            <x-badge>{{ ucfirst($challenge->difficulty) }}</x-badge>
                        </div>
                        <h3 class="mt-3 font-semibold">{{ $challenge->title }}</h3>
                        <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $challenge->description }}</p>
                        <p class="mt-3 text-xs text-gray-400">{{ $challenge->points }} pts</p>
                    </x-card>
                @endforeach
            </div>
        </section>

        {{-- Showcase --}}
        @if ($projects->count())
            <section>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">From the community</h2>
                    <a href="{{ route('showcase.index') }}" class="text-sm font-medium text-gray-900 dark:text-white hover:underline">View showcase →</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach ($projects as $project)
                        <x-card :href="route('showcase.show', $project)" class="overflow-hidden">
                            <div class="aspect-video bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                @if ($project->imageUrl())
                                    <img src="{{ $project->imageUrl() }}" alt="" class="h-full w-full object-cover">
                                @else
                                    <x-placeholder-icon />
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold">{{ $project->title }}</h3>
                                <p class="mt-1 text-xs text-gray-500">by {{ $project->user->name }}</p>
                            </div>
                        </x-card>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
