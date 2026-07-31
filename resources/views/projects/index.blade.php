<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">My projects</h1>
                <p class="text-sm text-gray-500 mt-1">Manage the projects on your public showcase.</p>
            </div>
            <a href="{{ route('projects.create') }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">+ Add</a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-4">
        @forelse ($projects as $project)
            <x-card class="p-4">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-14 w-14 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 flex items-center justify-center shrink-0">
                            @if ($project->imageUrl())<img src="{{ $project->imageUrl() }}" class="h-full w-full object-cover" alt="">@else<x-placeholder-icon size="h-6 w-6" />@endif
                        </div>
                        <div>
                            <h3 class="font-semibold">{{ $project->title }}</h3>
                            <p class="text-xs text-gray-500 line-clamp-1">{{ $project->description }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('projects.edit', $project) }}" class="text-sm text-gray-900 dark:text-white hover:underline">Edit</a>
                        <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
                            @csrf @method('DELETE')
                            <button class="text-sm text-gray-700 dark:text-gray-300 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            </x-card>
        @empty
            <x-empty-state title="No projects yet" message="Add your first project to your showcase.">
                <x-slot name="action">
                    <a href="{{ route('projects.create') }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">+ Add project</a>
                </x-slot>
            </x-empty-state>
        @endforelse
    </div>
</x-app-layout>
