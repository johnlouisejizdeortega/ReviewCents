<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Admin panel</h1></x-slot>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('admin._nav')
        <h2 class="text-lg font-bold mb-4">{{ $resource->exists ? 'Edit' : 'New' }} resource</h2>
        <x-card class="p-6">
            <form method="POST" action="{{ $resource->exists ? route('admin.resources.update', $resource) : route('admin.resources.store') }}" class="space-y-4">
                @csrf
                @if ($resource->exists) @method('PUT') @endif
                <div>
                    <x-input-label for="title" value="Title" />
                    <x-text-input id="title" name="title" class="mt-1 block w-full" :value="old('title', $resource->title)" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-1" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="category_id" value="Category" />
                        <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $resource->category_id) == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="type" value="Type" />
                        <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
                            @foreach (['course','tutorial','tool','book','bootcamp'] as $t)
                                <option value="{{ $t }}" @selected(old('type', $resource->type) === $t)>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <x-input-label for="url" value="URL" />
                    <x-text-input id="url" name="url" type="url" class="mt-1 block w-full" :value="old('url', $resource->url)" placeholder="https://…" />
                </div>
                <div>
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white" required>{{ old('description', $resource->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>
                <div class="flex gap-2">
                    <button class="px-5 py-2.5 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">Save</button>
                    <a href="{{ route('admin.resources.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
