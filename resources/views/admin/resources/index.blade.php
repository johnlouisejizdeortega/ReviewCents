<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Admin panel</h1></x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('admin._nav')
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Resources</h2>
            <a href="{{ route('admin.resources.create') }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">+ New</a>
        </div>
        <x-card class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse ($resources as $resource)
                <div class="flex items-center justify-between p-4">
                    <div>
                        <div class="font-medium">{{ $resource->title }}</div>
                        <div class="text-xs text-gray-500">{{ $resource->category->name }} · {{ ucfirst($resource->type) }} · ★ {{ number_format($resource->avg_rating, 1) }}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.resources.edit', $resource) }}" class="text-sm text-gray-900 dark:text-white hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.resources.destroy', $resource) }}" onsubmit="return confirm('Delete resource?')">
                            @csrf @method('DELETE')
                            <button class="text-sm text-gray-700 dark:text-gray-300 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-4 text-sm text-gray-500">No resources.</div>
            @endforelse
        </x-card>
        <div class="mt-4">{{ $resources->links() }}</div>
    </div>
</x-app-layout>
