<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold">Learning resources</h1>
        <p class="text-sm text-gray-500 mt-1">Courses, tutorials, tools and books — rated by the community.</p>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Filters --}}
        <form method="GET" class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search…"
                   class="col-span-2 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
            <select name="category" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
                <option value="">All categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->slug }}" @selected(($filters['category'] ?? '') === $cat->slug)>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="sort" onchange="this.form.submit()" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
                <option value="top" @selected(($filters['sort'] ?? 'top') === 'top')>Top rated</option>
                <option value="new" @selected(($filters['sort'] ?? '') === 'new')>Newest</option>
            </select>
            <button class="col-span-2 sm:col-span-4 sm:w-auto sm:justify-self-start px-4 py-2 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">Apply filters</button>
        </form>

        @if ($resources->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($resources as $resource)
                    <x-card :href="route('resources.show', $resource)" class="p-5">
                        <div class="flex items-center justify-between">
                            <x-badge color="indigo">{{ $resource->category->name }}</x-badge>
                            <x-badge>{{ ucfirst($resource->type) }}</x-badge>
                        </div>
                        <h3 class="mt-3 font-semibold">{{ $resource->title }}</h3>
                        <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $resource->description }}</p>
                        <div class="mt-3"><x-stars :rating="$resource->avg_rating" :count="$resource->reviews_count" /></div>
                    </x-card>
                @endforeach
            </div>
            <div class="mt-6">{{ $resources->links() }}</div>
        @else
            <x-empty-state title="No resources found" message="Try adjusting your filters." />
        @endif
    </div>
</x-app-layout>
