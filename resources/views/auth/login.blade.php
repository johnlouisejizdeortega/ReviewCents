<x-guest-layout>
    <div class="mb-6">
        <p class="text-xs font-mono uppercase tracking-widest text-gray-400">Welcome back</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tightish">Log in to ReviewCents</h1>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-gray-900 dark:focus:ring-white dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <x-button class="w-full mt-6">{{ __('Log in') }}</x-button>

        <div class="flex items-center justify-between mt-5 text-sm">
            @if (Route::has('password.request'))
                <a class="text-gray-500 hover:text-gray-900 dark:hover:text-white link-underline" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @else
                <span></span>
            @endif
            <a class="text-gray-900 dark:text-white font-medium link-underline" href="{{ route('register') }}">Create account</a>
        </div>
    </form>
</x-guest-layout>
