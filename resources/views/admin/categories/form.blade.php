<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Admin panel</h1></x-slot>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('admin._nav')
        <h2 class="text-lg font-bold mb-4">{{ $category->exists ? 'Edit' : 'New' }} category</h2>
        <x-card class="p-6">
            <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="space-y-4">
                @csrf
                @if ($category->exists) @method('PUT') @endif
                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" class="mt-1 block w-full" :value="old('name', $category->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="icon" value="Icon (emoji)" />
                    <x-text-input id="icon" name="icon" class="mt-1 block w-full" :value="old('icon', $category->icon)" placeholder="optional" />
                </div>
                <div>
                    <x-input-label for="description" value="Description" />
                    <x-text-input id="description" name="description" class="mt-1 block w-full" :value="old('description', $category->description)" />
                </div>
                <div class="flex gap-2">
                    <button class="px-5 py-2.5 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">Save</button>
                    <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
