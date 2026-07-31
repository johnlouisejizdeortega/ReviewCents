<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Admin panel</h1></x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('admin._nav')
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Roadmaps</h2>
            <a href="{{ route('admin.roadmaps.create') }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">+ New</a>
        </div>
        <x-card class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse ($roadmaps as $roadmap)
                <div class="flex items-center justify-between p-4">
                    <div>
                        <div class="font-medium">{{ $roadmap->title }}</div>
                        <div class="text-xs text-gray-500">{{ $roadmap->category->name }} · {{ $roadmap->steps_count }} steps</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.roadmaps.quiz.edit', $roadmap) }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">Test</a>
                        <a href="{{ route('admin.roadmaps.edit', $roadmap) }}" class="text-sm text-gray-900 dark:text-white hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.roadmaps.destroy', $roadmap) }}" onsubmit="return confirm('Delete roadmap?')">
                            @csrf @method('DELETE')
                            <button class="text-sm text-gray-700 dark:text-gray-300 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-4 text-sm text-gray-500">No roadmaps.</div>
            @endforelse
        </x-card>
    </div>
</x-app-layout>
