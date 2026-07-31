<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold">My tasks &amp; missions</h1>
        <p class="text-sm text-gray-500 mt-1">Custom tasks assigned to you by mentors, with their ratings and feedback.</p>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-4">
        @forelse ($assignments as $assignment)
            <x-card class="p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <x-badge :color="$assignment->type === 'mission' ? 'blue' : 'indigo'">{{ ucfirst($assignment->type) }}</x-badge>
                            <x-badge :color="['assigned'=>'amber','submitted'=>'blue','reviewed'=>'green'][$assignment->status]">{{ ucfirst($assignment->status) }}</x-badge>
                        </div>
                        <h3 class="mt-2 font-semibold text-lg">{{ $assignment->title }}</h3>
                    </div>
                    @if ($assignment->due_date)
                        <span class="text-xs text-gray-400 whitespace-nowrap">Due {{ $assignment->due_date->format('M j') }}</span>
                    @endif
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $assignment->description }}</p>
                <p class="mt-2 text-xs text-gray-400">Assigned by {{ $assignment->admin->name }}</p>

                @if ($assignment->status === 'reviewed')
                    <div class="mt-4 rounded-lg bg-green-50 dark:bg-green-950/40 p-4">
                        <div class="flex items-center gap-2"><span class="text-sm font-medium">Rating:</span> <x-stars :rating="$assignment->rating" /></div>
                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Feedback:</span> {{ $assignment->feedback }}</p>
                        @if ($assignment->submission)<p class="mt-2 text-xs text-gray-500">Your submission: {{ $assignment->submission }}</p>@endif
                    </div>
                @elseif ($assignment->status === 'submitted')
                    <div class="mt-3 rounded-lg bg-blue-50 dark:bg-blue-950/40 p-3 text-sm text-blue-800 dark:text-blue-200">
                        Submitted — waiting for your mentor's feedback.
                        <p class="mt-1 text-xs text-gray-500">Your submission: {{ $assignment->submission }}</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('assignments.submit', $assignment) }}" class="mt-4 space-y-2">
                        @csrf
                        <textarea name="submission" rows="3" required placeholder="Paste a link or describe your work…"
                                  class="block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        <x-input-error :messages="$errors->get('submission')" />
                        <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Submit work</button>
                    </form>
                @endif
            </x-card>
        @empty
            <x-empty-state title="No tasks yet" message="When a mentor assigns you a custom task, it'll show up here." />
        @endforelse
    </div>
</x-app-layout>
