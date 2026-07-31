<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'ReviewCents') }}</title>

        {{-- Apply theme before paint to avoid a flash --}}
        <script>
            (function () {
                try {
                    var t = localStorage.getItem('theme');
                    var dark = t ? t === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
                    document.documentElement.classList.toggle('dark', dark);
                } catch (e) {}
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Safety net: if Alpine fails to load for any reason, never leave the
             scroll-reveal content hidden — show everything. --}}
        <script>
            window.addEventListener('load', function () {
                setTimeout(function () {
                    if (!window.Alpine) {
                        document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('reveal-in'); });
                        document.querySelectorAll('[x-cloak]').forEach(function (el) { el.removeAttribute('x-cloak'); });
                    }
                }, 1500);
            });
        </script>

        @stack('head')
    </head>
    <body class="h-full font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 overflow-x-hidden">
        {{-- Top navigation progress bar --}}
        <div x-data="navProgress" x-show="active" x-cloak class="fixed top-0 inset-x-0 z-[60] h-0.5">
            <div class="h-full bg-gray-900 dark:bg-white transition-all duration-300 ease-out" :style="`width: ${width}%`"></div>
        </div>

        <div class="min-h-full flex flex-col w-full max-w-full">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Flash messages --}}
            @if (session('success') || session('error'))
                <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-4">
                    @if (session('success'))
                        <div class="rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            @endif

            <main class="flex-1 pb-24 sm:pb-10">
                {{ $slot }}
            </main>

            @include('layouts.bottom-nav')
        </div>
    </body>
</html>
