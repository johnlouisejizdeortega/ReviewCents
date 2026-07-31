<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Admin panel</h1></x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('admin._nav')
        <h2 class="text-lg font-bold mb-4">Users</h2>
        <x-card class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach ($users as $user)
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->avatarUrl() }}" class="h-9 w-9 rounded-full object-cover" alt="">
                        <div>
                            <div class="font-medium">{{ $user->name }} <span class="text-xs text-gray-400">@{{ $user->username }}</span></div>
                            <div class="text-xs text-gray-500">{{ $user->reviews_count }} reviews · {{ $user->projects_count }} projects · {{ $user->assignments_count }} tasks</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-badge :color="$user->role === 'admin' ? 'amber' : 'gray'">{{ ucfirst($user->role) }}</x-badge>
                        <a href="{{ route('admin.assignments.create', ['user' => $user->id]) }}" class="text-sm text-gray-900 dark:text-white hover:underline">Assign task</a>
                    </div>
                </div>
            @endforeach
        </x-card>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</x-app-layout>
