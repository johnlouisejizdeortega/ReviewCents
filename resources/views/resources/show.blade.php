<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <a href="{{ route('resources.index') }}" class="text-sm text-gray-900 dark:text-white hover:underline">← Back to resources</a>

        <div class="mt-4 flex flex-wrap items-center gap-2">
            <x-badge color="indigo">{{ $resource->category->name }}</x-badge>
            <x-badge>{{ ucfirst($resource->type) }}</x-badge>
        </div>
        <h1 class="mt-3 text-2xl sm:text-3xl font-bold">{{ $resource->title }}</h1>
        <div class="mt-2 flex items-center gap-3">
            <x-stars :rating="$resource->avg_rating" :count="$resource->reviews_count" />
        </div>
        <p class="mt-4 text-gray-700 dark:text-gray-300">{{ $resource->description }}</p>
        @if ($resource->url)
            <a href="{{ $resource->url }}" target="_blank" rel="noopener"
               class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">
                Visit resource ↗
            </a>
        @endif

        {{-- Write a review --}}
        <div class="mt-8">
            <h2 class="text-lg font-bold mb-3">{{ $userReview ? 'Your review' : 'Write a review' }}</h2>
            @auth
                <x-card class="p-5">
                    <form method="POST" action="{{ route('reviews.store', $resource) }}" x-data="{ rating: {{ $userReview->rating ?? 0 }} }">
                        @csrf
                        <div class="flex items-center gap-1 mb-3">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" @click="rating = {{ $i }}" class="focus:outline-none">
                                    <svg class="h-8 w-8" :class="rating >= {{ $i }} ? 'text-gray-900 dark:text-white' : 'text-gray-300 dark:text-gray-600'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.17c.969 0 1.371 1.24.588 1.81l-3.376 2.454a1 1 0 00-.363 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.375-2.454a1 1 0 00-1.176 0l-3.375 2.454c-.784.57-1.838-.196-1.539-1.118l1.287-3.966a1 1 0 00-.363-1.118L2.98 9.393c-.783-.57-.38-1.81.588-1.81h4.17a1 1 0 00.95-.69l1.286-3.966z" /></svg>
                                </button>
                            @endfor
                            <input type="hidden" name="rating" :value="rating">
                        </div>
                        <x-input-error :messages="$errors->get('rating')" class="mb-2" />
                        <textarea name="body" rows="3" placeholder="Share what you thought…"
                                  class="block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white">{{ $userReview->body ?? '' }}</textarea>
                        <div class="mt-3 flex items-center gap-2">
                            <button class="px-4 py-2 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm font-semibold hover:bg-gray-700 dark:hover:bg-gray-200">{{ $userReview ? 'Update review' : 'Post review' }}</button>
                            @if ($userReview)
                                <button form="delete-review" class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Delete</button>
                            @endif
                        </div>
                    </form>
                    @if ($userReview)
                        <form id="delete-review" method="POST" action="{{ route('reviews.destroy', $userReview) }}">
                            @csrf @method('DELETE')
                        </form>
                    @endif
                </x-card>
            @else
                <x-card class="p-5 text-sm text-gray-600 dark:text-gray-300">
                    <a href="{{ route('login') }}" class="text-gray-900 dark:text-white font-medium hover:underline">Log in</a> to write a review.
                </x-card>
            @endauth
        </div>

        {{-- Reviews list --}}
        <div class="mt-8">
            <h2 class="text-lg font-bold mb-3">Community reviews ({{ $resource->reviews->count() }})</h2>
            <div class="space-y-3">
                @forelse ($resource->reviews->sortByDesc('created_at') as $review)
                    <x-card class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <img src="{{ $review->user->avatarUrl() }}" class="h-8 w-8 rounded-full object-cover" alt="">
                                <span class="text-sm font-medium">{{ $review->user->name }}</span>
                            </div>
                            <x-stars :rating="$review->rating" />
                        </div>
                        @if ($review->body)
                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">{{ $review->body }}</p>
                        @endif
                    </x-card>
                @empty
                    <x-empty-state title="No reviews yet" message="Be the first to review this resource." />
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
