<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Admin panel</h1></x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('admin._nav')
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Categories</h2>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">+ New</a>
        </div>
        <x-card class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse ($categories as $cat)
                <div class="flex items-center justify-between p-4">
                    <div>
                        <div class="font-medium">{{ $cat->icon }} {{ $cat->name }}</div>
                        <div class="text-xs text-gray-500">{{ $cat->resources_count }} resources · {{ $cat->roadmaps_count }} roadmaps</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="text-sm text-indigo-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete category?')">
                            @csrf @method('DELETE')
                            <button class="text-sm text-red-600 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-4 text-sm text-gray-500">No categories.</div>
            @endforelse
        </x-card>
    </div>
</x-app-layout>
