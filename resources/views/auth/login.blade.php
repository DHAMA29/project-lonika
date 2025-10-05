<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" 
          class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 space-y-6">
        @csrf

        <!-- Heading -->
        <!-- <div class="text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Selamat Datang 👋</h2>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Silakan masuk ke akun Anda</p>
        </div> -->

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-semibold text-gray-700" />
            <x-text-input id="email" 
                          class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                          type="email" name="email" :value="old('email')" 
                          placeholder="you@example.com"
                          required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password with Show/Hide -->
        <div x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" class="font-semibold text-gray-700" />
            
            <div class="relative mt-1">
                <input id="password"
                       :type="show ? 'text' : 'password'"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pr-10" />

                <!-- Toggle button -->
                <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 transition">
                    <!-- Eye Icon -->
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 
                                 8.268 2.943 9.542 7-1.274 4.057-5.065 
                                 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Eye Off Icon -->
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 
                                 19c-4.477 0-8.268-2.943-9.542-7a9.978 
                                 9.978 0 012.042-3.592M3 3l18 18" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                       name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <!-- Submit -->
        <div>
            <x-primary-button class="w-full justify-center py-2.5 sm:py-3 rounded-lg text-base font-semibold">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Footer -->
    <!-- <div class="mt-6 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} Lonika. All rights reserved.
    </div> -->
</x-guest-layout>
