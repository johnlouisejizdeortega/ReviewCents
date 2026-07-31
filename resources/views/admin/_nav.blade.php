@php
    $adminLinks = [
        ['route' => 'admin.dashboard', 'label' => 'Overview', 'pattern' => 'admin.dashboard'],
        ['route' => 'admin.roadmaps.index', 'label' => 'Roadmaps', 'pattern' => 'admin.roadmaps.*'],
        ['route' => 'admin.resources.index', 'label' => 'Resources', 'pattern' => 'admin.resources.*'],
        ['route' => 'admin.challenges.index', 'label' => 'Challenges', 'pattern' => 'admin.challenges.*'],
        ['route' => 'admin.categories.index', 'label' => 'Categories', 'pattern' => 'admin.categories.*'],
        ['route' => 'admin.assignments.index', 'label' => 'Assignments', 'pattern' => 'admin.assignments.*'],
        ['route' => 'admin.submissions.index', 'label' => 'Submissions', 'pattern' => 'admin.submissions.*'],
        ['route' => 'admin.users.index', 'label' => 'Users', 'pattern' => 'admin.users.*'],
    ];
@endphp
<div class="border-b border-gray-200 dark:border-gray-800 mb-6 -mx-4 px-4 sm:mx-0 sm:px-0">
    <div class="flex gap-1 overflow-x-auto pb-px">
        @foreach ($adminLinks as $l)
            <a href="{{ route($l['route']) }}"
               class="whitespace-nowrap px-3 py-2 text-sm font-medium border-b-2 -mb-px {{ request()->routeIs($l['pattern']) ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-800 dark:hover:text-gray-200' }}">
                {{ $l['label'] }}
            </a>
        @endforeach
    </div>
</div>
