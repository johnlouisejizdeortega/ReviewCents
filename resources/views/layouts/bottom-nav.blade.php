@php
    $tabs = [
        ['route' => 'home', 'label' => 'Home', 'pattern' => 'home', 'icon' => 'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3v-6h6v6h3a1 1 0 001-1V10'],
        ['route' => 'roadmaps.index', 'label' => 'Learn', 'pattern' => 'roadmaps.*', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
        ['route' => 'challenges.index', 'label' => 'Challenge', 'pattern' => 'challenges.*', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
    ];
@endphp
<nav class="sm:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-gray-900/95 backdrop-blur border-t border-gray-200 dark:border-gray-800 pb-[env(safe-area-inset-bottom)]">
    <div class="grid grid-cols-5">
        @foreach ($tabs as $tab)
            @php $active = request()->routeIs($tab['pattern']); @endphp
            <a href="{{ route($tab['route']) }}" class="flex flex-col items-center justify-center gap-1 py-2 text-[11px] {{ $active ? 'text-gray-900 dark:text-white dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}" /></svg>
                <span>{{ $tab['label'] }}</span>
            </a>
        @endforeach

        @auth
            @php $chatActive = request()->routeIs('chat.*'); @endphp
            <a href="{{ route('chat.index') }}" class="flex flex-col items-center justify-center gap-1 py-2 text-[11px] {{ $chatActive ? 'text-gray-900 dark:text-white dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4-.8L3 20l1.3-3.9A7.9 7.9 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                <span>Chat</span>
            </a>
            @php $accActive = request()->routeIs('dashboard') || request()->routeIs('profile.*'); @endphp
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 py-2 text-[11px] {{ $accActive ? 'text-gray-900 dark:text-white dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3v-6h6v6h3a1 1 0 001-1V10" /></svg>
                <span>Me</span>
            </a>
        @else
            <a href="{{ route('showcase.index') }}" class="flex flex-col items-center justify-center gap-1 py-2 text-[11px] {{ request()->routeIs('showcase.*') ? 'text-gray-900 dark:text-white dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <span>Showcase</span>
            </a>
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center gap-1 py-2 text-[11px] text-gray-500 dark:text-gray-400">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                <span>Log in</span>
            </a>
        @endauth
    </div>
</nav>
