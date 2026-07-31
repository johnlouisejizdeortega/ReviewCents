<div class="space-y-4">
    <div>
        <x-input-label for="title" value="Title" />
        <x-text-input id="title" name="title" class="mt-1 block w-full" :value="old('title', $project->title)" required />
        <x-input-error :messages="$errors->get('title')" class="mt-1" />
    </div>
    <div>
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $project->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-1" />
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <x-input-label for="live_url" value="Live URL" />
            <x-text-input id="live_url" name="live_url" type="url" class="mt-1 block w-full" :value="old('live_url', $project->live_url)" placeholder="https://…" />
            <x-input-error :messages="$errors->get('live_url')" class="mt-1" />
        </div>
        <div>
            <x-input-label for="repo_url" value="Repository URL" />
            <x-text-input id="repo_url" name="repo_url" type="url" class="mt-1 block w-full" :value="old('repo_url', $project->repo_url)" placeholder="https://github.com/…" />
            <x-input-error :messages="$errors->get('repo_url')" class="mt-1" />
        </div>
    </div>
    <div>
        <x-input-label for="tags" value="Tags (comma separated)" />
        <x-text-input id="tags" name="tags" class="mt-1 block w-full" :value="old('tags', $project->tags)" placeholder="HTML, CSS, JavaScript" />
    </div>
    <div>
        <x-input-label for="image" value="Cover image" />
        @if ($project->imageUrl())
            <img src="{{ $project->imageUrl() }}" class="mt-1 h-32 rounded-lg object-cover" alt="">
        @endif
        <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm file:mr-3 file:rounded-md file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-indigo-700 hover:file:bg-indigo-100" />
        <x-input-error :messages="$errors->get('image')" class="mt-1" />
    </div>
</div>
