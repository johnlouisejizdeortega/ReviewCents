<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Showcase</h1>
                <p class="text-sm text-gray-500 mt-1">Projects built by the ReviewCents community.</p>
            </div>
            @auth
                <a href="{{ route('projects.create') }}" class="hidden sm:inline-flex px-4 py-2 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">+ Add project</a>
            @endauth
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if ($projects->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($projects as $project)
                    <x-reveal :delay="$loop->index % 6 * 60" class="h-full">
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
                                <div class="mt-1 flex items-center gap-2">
                                    <img src="{{ $project->user->avatarUrl() }}" class="h-5 w-5 rounded-full object-cover" alt="">
                                    <span class="text-xs text-gray-500">{{ $project->user->name }}</span>
                                </div>
                            </div>
                        </x-card>
                    </x-reveal>
                @endforeach
            </div>
            <div class="mt-6">{{ $projects->links() }}</div>
        @else
            <x-empty-state title="No projects yet" message="Be the first to showcase your work." />
        @endif
    </div>
</x-app-layout>
