<button x-data="themeToggle" @click="toggle()" type="button" aria-label="Toggle theme"
        {{ $attributes->merge(['class' => 'inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors']) }}>
    <x-lucide-sun x-show="dark" x-cloak class="size-5" />
    <x-lucide-moon x-show="!dark" x-cloak class="size-5" />
</button>
