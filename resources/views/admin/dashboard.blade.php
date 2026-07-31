<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <h1 class="text-2xl font-bold">Admin panel</h1>
            <x-badge color="amber">Admin</x-badge>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('admin._nav')

        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach ([
                ['label' => 'Users', 'value' => $stats['users']],
                ['label' => 'Resources', 'value' => $stats['resources']],
                ['label' => 'Roadmaps', 'value' => $stats['roadmaps']],
                ['label' => 'Challenges', 'value' => $stats['challenges']],
                ['label' => 'Categories', 'value' => $stats['categories']],
            ] as $tile)
                <x-card class="p-5">
                    <div class="text-2xl font-bold">{{ $tile['value'] }}</div>
                    <div class="text-xs text-gray-500">{{ $tile['label'] }}</div>
                </x-card>
            @endforeach
        </div>

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <section>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-bold">Submissions to review</h2>
                    <a href="{{ route('admin.submissions.index') }}" class="text-sm text-gray-900 dark:text-white hover:underline">All →</a>
                </div>
                @forelse ($pendingSubmissions as $sub)
                    <x-card class="p-4 mb-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-sm">{{ $sub->challenge->title }}</h3>
                                <p class="text-xs text-gray-500">by {{ $sub->user->name }}</p>
                            </div>
                            <a href="{{ route('admin.submissions.index') }}" class="text-xs font-medium text-gray-900 dark:text-white hover:underline">Review</a>
                        </div>
                    </x-card>
                @empty
                    <x-card class="p-4 text-sm text-gray-500">No pending submissions.</x-card>
                @endforelse
            </section>

            <section>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-bold">Open assignments</h2>
                    <a href="{{ route('admin.assignments.create') }}" class="text-sm text-gray-900 dark:text-white hover:underline">+ Assign</a>
                </div>
                @forelse ($openAssignments as $a)
                    <x-card class="p-4 mb-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-sm">{{ $a->title }}</h3>
                                <p class="text-xs text-gray-500">{{ $a->user->name }}</p>
                            </div>
                            <x-badge :color="$a->status === 'submitted' ? 'blue' : 'amber'">{{ ucfirst($a->status) }}</x-badge>
                        </div>
                    </x-card>
                @empty
                    <x-card class="p-4 text-sm text-gray-500">No open assignments.</x-card>
                @endforelse
            </section>
        </div>
    </div>
</x-app-layout>
