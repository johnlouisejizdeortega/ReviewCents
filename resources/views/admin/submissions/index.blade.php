<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Admin panel</h1></x-slot>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('admin._nav')
        <h2 class="text-lg font-bold mb-4">Challenge submissions</h2>

        <div class="space-y-3">
            @forelse ($submissions as $submission)
                <x-card class="p-5" x-data="{ open: {{ $submission->status === 'pending' ? 'true' : 'false' }} }">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold">{{ $submission->challenge->title }}</h3>
                            <p class="text-xs text-gray-500">by {{ $submission->user->name }} · {{ $submission->created_at->diffForHumans() }}</p>
                        </div>
                        <x-badge :color="$submission->status === 'reviewed' ? 'green' : 'amber'">{{ ucfirst($submission->status) }}</x-badge>
                    </div>
                    @if ($submission->submission_url)
                        <a href="{{ $submission->submission_url }}" target="_blank" class="mt-2 inline-flex text-sm text-indigo-600 hover:underline">{{ $submission->submission_url }} ↗</a>
                    @endif
                    @if ($submission->notes)<p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $submission->notes }}</p>@endif

                    @if ($submission->status === 'reviewed')
                        <div class="mt-3 flex items-center gap-2 text-sm">
                            <x-stars :rating="$submission->rating" /> <span class="text-gray-500">{{ $submission->feedback }}</span>
                        </div>
                    @else
                        <form x-show="open" x-cloak method="POST" action="{{ route('admin.submissions.review', $submission) }}" class="mt-3 space-y-2">
                            @csrf @method('PATCH')
                            <div>
                                <label class="block text-sm font-medium mb-1">Rating</label>
                                <select name="rating" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @for ($i = 1; $i <= 5; $i++)<option value="{{ $i }}">{{ $i }} ★</option>@endfor
                                </select>
                            </div>
                            <textarea name="feedback" rows="2" placeholder="Feedback…" required class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Rate &amp; send feedback</button>
                        </form>
                    @endif
                </x-card>
            @empty
                <x-empty-state title="No submissions yet" />
            @endforelse
        </div>
    </div>
</x-app-layout>
