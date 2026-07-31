@php
    $tabs = [
        ['route' => 'home', 'label' => 'Home', 'pattern' => 'home', 'icon' => 'house'],
        ['route' => 'roadmaps.index', 'label' => 'Learn', 'pattern' => 'roadmaps.*', 'icon' => 'book-open'],
        ['route' => 'challenges.index', 'label' => 'Challenge', 'pattern' => 'challenges.*', 'icon' => 'zap'],
    ];
@endphp
<nav class="sm:hidden fixed bottom-0 inset-x-0 z-40 bg-white/90 dark:bg-gray-950/90 backdrop-blur-md border-t border-gray-200 dark:border-gray-800 pb-[env(safe-area-inset-bottom)]">
    <div class="grid grid-cols-5">
        @foreach ($tabs as $tab)
            @php $active = request()->routeIs($tab['pattern']); @endphp
            <a href="{{ route($tab['route']) }}" class="flex flex-col items-center justify-center gap-1 py-2.5 text-[11px] transition-colors {{ $active ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}">
                <x-dynamic-component :component="'lucide-' . $tab['icon']" class="size-5" />
                <span>{{ $tab['label'] }}</span>
            </a>
        @endforeach

        @auth
            @php $chatActive = request()->routeIs('chat.*'); @endphp
            <a href="{{ route('chat.index') }}" class="flex flex-col items-center justify-center gap-1 py-2.5 text-[11px] transition-colors {{ $chatActive ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}">
                <x-lucide-message-circle class="size-5" />
                <span>Chat</span>
            </a>
            @php $accActive = request()->routeIs('dashboard') || request()->routeIs('profile.*'); @endphp
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 py-2.5 text-[11px] transition-colors {{ $accActive ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}">
                <x-lucide-user class="size-5" />
                <span>Me</span>
            </a>
        @else
            <a href="{{ route('showcase.index') }}" class="flex flex-col items-center justify-center gap-1 py-2.5 text-[11px] transition-colors {{ request()->routeIs('showcase.*') ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}">
                <x-lucide-layout-grid class="size-5" />
                <span>Showcase</span>
            </a>
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center gap-1 py-2.5 text-[11px] text-gray-400 dark:text-gray-500">
                <x-lucide-log-in class="size-5" />
                <span>Log in</span>
            </a>
        @endauth
    </div>
</nav>
