<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Messages</h1></x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        {{-- Conversations --}}
        <section>
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Conversations</h2>
            <div class="space-y-2">
                @forelse ($conversations as $conversation)
                    @php $last = $conversation->messages->sortByDesc('created_at')->first(); @endphp
                    <x-card :href="route('chat.show', $conversation)" class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center font-semibold text-indigo-700 dark:text-indigo-300">
                                {{ strtoupper(substr($conversation->titleFor(auth()->user()), 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold truncate">{{ $conversation->titleFor(auth()->user()) }}</h3>
                                <p class="text-xs text-gray-500 truncate">{{ $last?->body ?? 'No messages yet' }}</p>
                            </div>
                        </div>
                    </x-card>
                @empty
                    <x-empty-state title="No conversations yet" message="Start chatting with someone below." />
                @endforelse
            </div>
        </section>

        {{-- Start a new chat --}}
        <section>
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Start a new chat</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach ($people as $person)
                    <x-card class="p-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <img src="{{ $person->avatarUrl() }}" class="h-8 w-8 rounded-full object-cover" alt="">
                            <div>
                                <div class="text-sm font-medium">{{ $person->name }}</div>
                                <div class="text-xs text-gray-400">{{ ucfirst($person->role) }}</div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('chat.start', $person) }}">
                            @csrf
                            <button class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700">Chat</button>
                        </form>
                    </x-card>
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>
