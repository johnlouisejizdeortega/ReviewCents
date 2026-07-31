<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <a href="{{ route('showcase.index') }}" class="text-sm text-indigo-600 hover:underline">← Back to showcase</a>

        <div class="mt-4 rounded-2xl overflow-hidden bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-950 dark:to-purple-950 aspect-video flex items-center justify-center">
            @if ($project->imageUrl())
                <img src="{{ $project->imageUrl() }}" alt="" class="h-full w-full object-cover">
            @else
                <span class="text-6xl">🚀</span>
            @endif
        </div>

        <h1 class="mt-5 text-2xl sm:text-3xl font-bold">{{ $project->title }}</h1>
        <a href="{{ route('profile.show', $project->user) }}" class="mt-2 inline-flex items-center gap-2">
            <img src="{{ $project->user->avatarUrl() }}" class="h-6 w-6 rounded-full object-cover" alt="">
            <span class="text-sm text-gray-500 hover:text-indigo-600">by {{ $project->user->name }}</span>
        </a>

        @if ($project->description)
            <p class="mt-4 text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $project->description }}</p>
        @endif

        @if (count($project->tagList()))
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($project->tagList() as $tag)
                    <x-badge>{{ $tag }}</x-badge>
                @endforeach
            </div>
        @endif

        <div class="mt-6 flex flex-wrap gap-2">
            @if ($project->live_url)
                <a href="{{ $project->live_url }}" target="_blank" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Live demo ↗</a>
            @endif
            @if ($project->repo_url)
                <a href="{{ $project->repo_url }}" target="_blank" class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-sm font-semibold hover:bg-gray-200">Source code ↗</a>
            @endif
        </div>
    </div>
</x-app-layout>
