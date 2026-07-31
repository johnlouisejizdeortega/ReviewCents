<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <x-card class="p-6">
            <div class="flex items-center gap-4">
                <img src="{{ $user->avatarUrl() }}" class="h-20 w-20 rounded-full object-cover ring-2 ring-indigo-100 dark:ring-indigo-900" alt="">
                <div>
                    <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-500">@{{ $user->username }}</p>
                    @if ($user->headline)<p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $user->headline }}</p>@endif
                </div>
            </div>
            @if ($user->bio)<p class="mt-4 text-sm text-gray-700 dark:text-gray-300">{{ $user->bio }}</p>@endif
            <div class="mt-4 flex gap-6 text-sm">
                <div><span class="font-bold">{{ $passedQuizzes->count() }}</span> <span class="text-gray-500">tests passed</span></div>
                <div><span class="font-bold">{{ $reviewsCount }}</span> <span class="text-gray-500">reviews</span></div>
                <div><span class="font-bold">{{ $user->projects->count() }}</span> <span class="text-gray-500">projects</span></div>
            </div>
            @auth
                @if (auth()->id() !== $user->id)
                    <form method="POST" action="{{ route('chat.start', $user) }}" class="mt-4">
                        @csrf
                        <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Message</button>
                    </form>
                @endif
            @endauth
        </x-card>

        {{-- Badges: passed quizzes --}}
        @if ($passedQuizzes->count())
            <div class="mt-8">
                <h2 class="text-lg font-bold mb-3">Achievements</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach ($passedQuizzes as $attempt)
                        <x-badge color="green">🏅 {{ $attempt->quiz->roadmap->title ?? 'Quiz' }}</x-badge>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Projects --}}
        <div class="mt-8">
            <h2 class="text-lg font-bold mb-3">Projects</h2>
            @if ($user->projects->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($user->projects as $project)
                        <x-card :href="route('showcase.show', $project)" class="p-4">
                            <h3 class="font-semibold">{{ $project->title }}</h3>
                            <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $project->description }}</p>
                        </x-card>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">No projects yet.</p>
            @endif
        </div>
    </div>
</x-app-layout>
