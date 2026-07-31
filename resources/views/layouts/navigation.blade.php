@php
    $navLinks = [
        ['route' => 'home', 'label' => 'Home', 'pattern' => 'home'],
        ['route' => 'roadmaps.index', 'label' => 'Roadmaps', 'pattern' => 'roadmaps.*'],
        ['route' => 'resources.index', 'label' => 'Resources', 'pattern' => 'resources.*'],
        ['route' => 'challenges.index', 'label' => 'Challenges', 'pattern' => 'challenges.*'],
        ['route' => 'showcase.index', 'label' => 'Showcase', 'pattern' => 'showcase.*'],
    ];
@endphp
<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-white/80 dark:bg-gray-950/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            {{-- Logo --}}
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-base" style="view-transition-name: site-logo">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-xs font-bold tracking-tight">RC</span>
                    <span class="tracking-tightish">Review<span class="text-gray-400">Cents</span></span>
                </a>

                {{-- Desktop nav --}}
                <div class="hidden md:flex md:items-center md:gap-1">
                    @foreach ($navLinks as $link)
                        <a href="{{ route($link['route']) }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs($link['pattern']) ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                    @auth
                        <a href="{{ route('chat.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('chat.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            Chat
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-2">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border border-gray-300 dark:border-gray-700 hover:border-gray-900 dark:hover:border-white transition-colors">
                            <x-lucide-shield class="size-3.5" /> Admin
                        </a>
                    @endif
                    <div class="hidden md:block">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-2 px-2 py-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                    <img src="{{ auth()->user()->avatarUrl() }}" alt="" class="h-8 w-8 rounded-full object-cover">
                                    <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                                    <x-lucide-chevron-down class="size-4 text-gray-400" />
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('dashboard')">Dashboard</x-dropdown-link>
                                <x-dropdown-link :href="route('assignments.index')">My Tasks</x-dropdown-link>
                                <x-dropdown-link :href="route('projects.index')">My Projects</x-dropdown-link>
                                <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        Log Out
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">Log in</a>
                    <x-button :href="route('register')" size="sm">Get started</x-button>
                @endauth

                {{-- Mobile hamburger --}}
                <button @click="open = !open" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <x-lucide-menu x-show="!open" class="size-6" />
                    <x-lucide-x x-show="open" x-cloak class="size-6" />
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" x-transition x-cloak class="md:hidden border-t border-gray-200 dark:border-gray-800">
        <div class="px-2 py-3 space-y-1">
            @foreach ($navLinks as $link)
                <a href="{{ route($link['route']) }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs($link['pattern']) ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">{{ $link['label'] }}</a>
            @endforeach
            @auth
                <a href="{{ route('chat.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Chat</a>
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Dashboard</a>
                <a href="{{ route('assignments.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">My Tasks</a>
                <a href="{{ route('projects.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">My Projects</a>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Profile</a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800">Admin panel</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Log Out</a>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Log in</a>
            @endauth
        </div>
    </div>
</nav>
