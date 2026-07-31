<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Admin panel</h1></x-slot>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('admin._nav')
        <h2 class="text-lg font-bold mb-4">{{ $challenge->exists ? 'Edit' : 'New' }} challenge</h2>
        <x-card class="p-6">
            <form method="POST" action="{{ $challenge->exists ? route('admin.challenges.update', $challenge) : route('admin.challenges.store') }}" class="space-y-4">
                @csrf
                @if ($challenge->exists) @method('PUT') @endif
                <div>
                    <x-input-label for="title" value="Title" />
                    <x-text-input id="title" name="title" class="mt-1 block w-full" :value="old('title', $challenge->title)" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-1" />
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="type" value="Type" />
                        <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach (['challenge','mission'] as $t)
                                <option value="{{ $t }}" @selected(old('type', $challenge->type) === $t)>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="difficulty" value="Difficulty" />
                        <select id="difficulty" name="difficulty" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach (['easy','medium','hard'] as $d)
                                <option value="{{ $d }}" @selected(old('difficulty', $challenge->difficulty) === $d)>{{ ucfirst($d) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="points" value="Points" />
                        <x-text-input id="points" name="points" type="number" class="mt-1 block w-full" :value="old('points', $challenge->points ?? 10)" required />
                    </div>
                </div>
                <div>
                    <x-input-label for="category_id" value="Category (optional)" />
                    <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">—</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $challenge->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description', $challenge->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>
                <div class="flex gap-2">
                    <button class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Save</button>
                    <a href="{{ route('admin.challenges.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
