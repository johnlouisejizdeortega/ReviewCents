@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-gray-900 dark:focus:border-white focus:ring-gray-900 dark:focus:ring-white rounded-md shadow-sm']) }}>
