<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold">Add project</h1></x-slot>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <x-card class="p-6">
            <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data">
                @csrf
                @include('projects.form')
                <div class="mt-6 flex gap-2">
                    <button class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Save project</button>
                    <a href="{{ route('projects.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
