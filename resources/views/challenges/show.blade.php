<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <a href="{{ route('challenges.index') }}" class="text-sm text-gray-900 dark:text-white hover:underline">← Back to challenges</a>

        <div class="mt-4 flex flex-wrap gap-2">
            <x-badge :color="$challenge->type === 'mission' ? 'blue' : 'indigo'">{{ ucfirst($challenge->type) }}</x-badge>
            <x-badge :color="['easy'=>'green','medium'=>'amber','hard'=>'red'][$challenge->difficulty]">{{ ucfirst($challenge->difficulty) }}</x-badge>
            @if ($challenge->category)<x-badge>{{ $challenge->category->name }}</x-badge>@endif
        </div>
        <h1 class="mt-3 text-2xl sm:text-3xl font-bold">{{ $challenge->title }}</h1>
        <p class="mt-1 text-sm text-gray-400">{{ $challenge->points }} points</p>
        <div class="mt-4 prose prose-zinc dark:prose-invert max-w-none whitespace-pre-line">{{ $challenge->description }}</div>

        {{-- Existing submission --}}
        @auth
            @if ($mySubmission)
                <x-card class="mt-6 p-5">
                    <div class="flex items-center justify-between">
                        <h2 class="font-bold">Your submission</h2>
                        <x-badge :color="$mySubmission->status === 'reviewed' ? 'green' : 'amber'">{{ ucfirst($mySubmission->status) }}</x-badge>
                    </div>
                    @if ($mySubmission->submission_url)
                        <a href="{{ $mySubmission->submission_url }}" target="_blank" class="mt-2 inline-flex text-sm text-gray-900 dark:text-white hover:underline">{{ $mySubmission->submission_url }} ↗</a>
                    @endif
                    @if ($mySubmission->notes)<p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $mySubmission->notes }}</p>@endif

                    @if ($mySubmission->status === 'reviewed')
                        <div class="mt-4 rounded-lg bg-gray-100 dark:bg-gray-800 p-4">
                            <div class="flex items-center gap-2"><span class="text-sm font-medium">Mentor rating:</span> <x-stars :rating="$mySubmission->rating" /></div>
                            @if ($mySubmission->feedback)<p class="mt-2 text-sm text-gray-700 dark:text-gray-300">{{ $mySubmission->feedback }}</p>@endif
                        </div>
                    @endif
                </x-card>
            @endif

            {{-- Submit form --}}
            <x-card class="mt-6 p-5">
                <h2 class="font-bold mb-3">{{ $mySubmission ? 'Submit again' : 'Submit your work' }}</h2>
                <form method="POST" action="{{ route('challenges.submit', $challenge) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Link to your work (GitHub, live demo…)</label>
                        <input type="url" name="submission_url" placeholder="https://…" class="block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">
                        <x-input-error :messages="$errors->get('submission_url')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Notes (optional)</label>
                        <textarea name="notes" rows="3" class="block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white"></textarea>
                    </div>
                    <button class="px-5 py-2.5 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">Submit</button>
                </form>
            </x-card>
        @else
            <x-card class="mt-6 p-5 text-sm text-gray-600 dark:text-gray-300">
                <a href="{{ route('login') }}" class="text-gray-900 dark:text-white font-medium hover:underline">Log in</a> to take on this challenge.
            </x-card>
        @endauth
    </div>
</x-app-layout>
