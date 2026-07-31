<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ReviewCents') }}</title>

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
    </head>
    <body class="h-full font-sans text-gray-900 dark:text-gray-100 antialiased bg-gray-50 dark:bg-gray-950">
        <div class="min-h-full flex flex-col justify-center items-center px-4 py-12">
            <a href="/" class="flex items-center gap-2 font-bold text-lg mb-6">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-xs font-bold">RC</span>
                <span>Review<span class="text-gray-400">Cents</span></span>
            </a>

            <div class="w-full sm:max-w-md px-6 py-8 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-soft">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
