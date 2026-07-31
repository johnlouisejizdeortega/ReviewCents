<x-app-layout>
    {{-- Hero --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 text-center text-white">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-medium backdrop-blur">
                ₵ Learn · Review · Level up
            </span>
            <h1 class="mt-5 text-3xl sm:text-5xl font-bold tracking-tight">
                Learn web dev &amp; design,<br class="hidden sm:block"> the mobile-first way.
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-indigo-100">
                Follow curated roadmaps, review the best learning resources, take end-of-learning tests,
                tackle challenges, and show off your progress — all from your phone.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                @guest
                    <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-6 py-3 rounded-xl bg-white text-indigo-700 font-semibold hover:bg-indigo-50 transition">Start learning free</a>
                    <a href="{{ route('roadmaps.index') }}" class="inline-flex justify-center items-center px-6 py-3 rounded-xl bg-indigo-500/40 text-white font-semibold ring-1 ring-white/30 hover:bg-indigo-500/60 transition">Browse roadmaps</a>
                @else
                    <a href="{{ route('dashboard') }}" class="inline-flex justify-center items-center px-6 py-3 rounded-xl bg-white text-indigo-700 font-semibold hover:bg-indigo-50 transition">Go to dashboard</a>
                    <a href="{{ route('roadmaps.index') }}" class="inline-flex justify-center items-center px-6 py-3 rounded-xl bg-indigo-500/40 text-white font-semibold ring-1 ring-white/30 hover:bg-indigo-500/60 transition">Browse roadmaps</a>
                @endguest
            </div>
            <div class="mt-10 grid grid-cols-3 gap-4 max-w-md mx-auto">
                <div><div class="text-2xl font-bold">{{ $stats['roadmaps'] }}</div><div class="text-xs text-indigo-200">Roadmaps</div></div>
                <div><div class="text-2xl font-bold">{{ $stats['resources'] }}</div><div class="text-xs text-indigo-200">Resources</div></div>
                <div><div class="text-2xl font-bold">{{ $stats['challenges'] }}</div><div class="text-xs text-indigo-200">Challenges</div></div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-14">
        {{-- Featured roadmaps --}}
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold">Learning roadmaps</h2>
                <a href="{{ route('roadmaps.index') }}" class="text-sm font-medium text-indigo-600 hover:underline">View all →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($roadmaps as $roadmap)
                    <x-card :href="route('roadmaps.show', $roadmap)" class="p-5">
                        <x-badge color="indigo">{{ $roadmap->category->icon }} {{ $roadmap->category->name }}</x-badge>
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
                <a href="{{ route('resources.index') }}" class="text-sm font-medium text-indigo-600 hover:underline">View all →</a>
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
                <a href="{{ route('challenges.index') }}" class="text-sm font-medium text-indigo-600 hover:underline">View all →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($challenges as $challenge)
                    <x-card :href="route('challenges.show', $challenge)" class="p-5">
                        <div class="flex gap-2">
                            <x-badge :color="$challenge->type === 'mission' ? 'blue' : 'indigo'">{{ ucfirst($challenge->type) }}</x-badge>
                            <x-badge :color="['easy'=>'green','medium'=>'amber','hard'=>'red'][$challenge->difficulty]">{{ ucfirst($challenge->difficulty) }}</x-badge>
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
                    <a href="{{ route('showcase.index') }}" class="text-sm font-medium text-indigo-600 hover:underline">View showcase →</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach ($projects as $project)
                        <x-card :href="route('showcase.show', $project)" class="overflow-hidden">
                            <div class="aspect-video bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-950 dark:to-purple-950 flex items-center justify-center">
                                @if ($project->imageUrl())
                                    <img src="{{ $project->imageUrl() }}" alt="" class="h-full w-full object-cover">
                                @else
                                    <span class="text-4xl">🎨</span>
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
