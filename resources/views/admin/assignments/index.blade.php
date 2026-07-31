<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Admin panel</h1></x-slot>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('admin._nav')
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Assignments</h2>
            <a href="{{ route('admin.assignments.create') }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">+ Assign</a>
        </div>

        <div class="space-y-3">
            @forelse ($assignments as $assignment)
                <x-card class="p-5" x-data="{ open: {{ $assignment->status === 'submitted' ? 'true' : 'false' }} }">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <x-badge :color="$assignment->type === 'mission' ? 'blue' : 'indigo'">{{ ucfirst($assignment->type) }}</x-badge>
                                <x-badge :color="['assigned'=>'amber','submitted'=>'blue','reviewed'=>'green'][$assignment->status]">{{ ucfirst($assignment->status) }}</x-badge>
                            </div>
                            <h3 class="mt-2 font-semibold">{{ $assignment->title }}</h3>
                            <p class="text-xs text-gray-500">for {{ $assignment->user->name }}</p>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $assignment->description }}</p>

                    @if ($assignment->submission)
                        <div class="mt-3 rounded-lg bg-gray-50 dark:bg-gray-800 p-3 text-sm">
                            <span class="font-medium">Submission:</span> {{ $assignment->submission }}
                        </div>
                    @endif

                    @if ($assignment->status === 'reviewed')
                        <div class="mt-3 flex items-center gap-2 text-sm">
                            <x-stars :rating="$assignment->rating" /> <span class="text-gray-500">{{ $assignment->feedback }}</span>
                        </div>
                    @else
                        <button @click="open = !open" class="mt-3 text-sm text-indigo-600 hover:underline">Rate &amp; give feedback</button>
                        <form x-show="open" x-cloak method="POST" action="{{ route('admin.assignments.review', $assignment) }}" class="mt-3 space-y-2">
                            @csrf @method('PATCH')
                            <div>
                                <label class="block text-sm font-medium mb-1">Rating</label>
                                <select name="rating" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @for ($i = 1; $i <= 5; $i++)<option value="{{ $i }}">{{ $i }} ★</option>@endfor
                                </select>
                            </div>
                            <textarea name="feedback" rows="2" placeholder="Feedback…" required class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Save feedback</button>
                        </form>
                    @endif
                </x-card>
            @empty
                <x-empty-state title="No assignments yet" message="Assign a custom task to a user to get started." />
            @endforelse
        </div>
    </div>
</x-app-layout>
