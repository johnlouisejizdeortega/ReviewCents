<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Admin panel</h1></x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('admin._nav')
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Challenges &amp; missions</h2>
            <a href="{{ route('admin.challenges.create') }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">+ New</a>
        </div>
        <x-card class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse ($challenges as $challenge)
                <div class="flex items-center justify-between p-4">
                    <div>
                        <div class="font-medium">{{ $challenge->title }}</div>
                        <div class="text-xs text-gray-500">{{ ucfirst($challenge->type) }} · {{ ucfirst($challenge->difficulty) }} · {{ $challenge->submissions_count }} submissions</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.challenges.edit', $challenge) }}" class="text-sm text-indigo-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.challenges.destroy', $challenge) }}" onsubmit="return confirm('Delete challenge?')">
                            @csrf @method('DELETE')
                            <button class="text-sm text-red-600 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-4 text-sm text-gray-500">No challenges.</div>
            @endforelse
        </x-card>
    </div>
</x-app-layout>
