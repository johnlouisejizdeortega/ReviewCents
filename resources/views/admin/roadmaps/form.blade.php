<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Admin panel</h1></x-slot>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('admin._nav')
        <h2 class="text-lg font-bold mb-4">{{ $roadmap->exists ? 'Edit' : 'New' }} roadmap</h2>
        <x-card class="p-6">
            <form method="POST" action="{{ $roadmap->exists ? route('admin.roadmaps.update', $roadmap) : route('admin.roadmaps.store') }}"
                  x-data="{ steps: {{ Illuminate\Support\Js::from(old('steps', $roadmap->exists ? $roadmap->steps->map(fn($s) => ['title' => $s->title, 'description' => $s->description, 'resource_id' => $s->resource_id])->values() : [['title'=>'','description'=>'','resource_id'=>'']])) }} }">
                @csrf
                @if ($roadmap->exists) @method('PUT') @endif

                <div class="space-y-4">
                    <div>
                        <x-input-label for="title" value="Title" />
                        <x-text-input id="title" name="title" class="mt-1 block w-full" :value="old('title', $roadmap->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="category_id" value="Category" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id', $roadmap->category_id) == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="level" value="Level" />
                            <select id="level" name="level" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
                                @foreach (['beginner','intermediate','advanced'] as $lvl)
                                    <option value="{{ $lvl }}" @selected(old('level', $roadmap->level) === $lvl)>{{ ucfirst($lvl) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white" required>{{ old('description', $roadmap->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>

                    {{-- Steps --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <x-input-label value="Steps" />
                            <button type="button" @click="steps.push({title:'',description:'',resource_id:''})" class="text-sm text-gray-900 dark:text-white hover:underline">+ Add step</button>
                        </div>
                        <template x-for="(step, i) in steps" :key="i">
                            <div class="mb-2 rounded-lg border border-gray-200 dark:border-gray-700 p-3 space-y-2">
                                <div class="flex gap-2">
                                    <input type="text" :name="`steps[${i}][title]`" x-model="step.title" placeholder="Step title" class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
                                    <button type="button" @click="steps.splice(i,1)" class="text-gray-500 text-sm px-2">✕</button>
                                </div>
                                <input type="text" :name="`steps[${i}][description]`" x-model="step.description" placeholder="Short description" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
                                <select :name="`steps[${i}][resource_id]`" x-model="step.resource_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
                                    <option value="">— link a resource (optional) —</option>
                                    @foreach ($resources as $res)
                                        <option value="{{ $res->id }}">{{ $res->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </template>
                    </div>

                    <div class="flex gap-2">
                        <button class="px-5 py-2.5 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">Save</button>
                        <a href="{{ route('admin.roadmaps.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">Cancel</a>
                    </div>
                    @if ($roadmap->exists)
                        <p class="text-xs text-gray-400">Tip: after saving, add the end-of-learning <a href="{{ route('admin.roadmaps.quiz.edit', $roadmap) }}" class="text-gray-900 dark:text-white hover:underline">test</a> (min. 3 questions).</p>
                    @endif
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
