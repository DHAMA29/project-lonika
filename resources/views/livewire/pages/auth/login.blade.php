<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;
    public bool $showPassword = false;

    /**
     * Toggle password visibility
     */
    public function togglePasswordVisibility(): void
    {
        $this->showPassword = !$this->showPassword;
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-4 sm:space-y-6">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-4 sm:space-y-6">
        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
            <x-text-input wire:model="form.email" 
                         id="email" 
                         class="block w-full px-3 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" 
                         type="email" 
                         name="email" 
                         placeholder="Masukkan email Anda"
                         required 
                         autofocus 
                         autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
            <div class="relative">
                <x-text-input wire:model="form.password" 
                             id="password" 
                             class="block w-full px-3 py-2.5 sm:py-3 pr-12 text-sm sm:text-base border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150"
                             :type="$showPassword ? 'text' : 'password'"
                             name="password"
                             placeholder="Masukkan password Anda"
                             required 
                             autocomplete="current-password" />
                
                <!-- Toggle Password Visibility Button -->
                <button type="button" 
                        wire:click="togglePasswordVisibility"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 focus:outline-none">
                    <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 transition-colors duration-150" 
                         fill="none" 
                         stroke="currentColor" 
                         viewBox="0 0 24 24">
                        @if($showPassword)
                            <!-- Eye Off Icon (Hide Password) -->
                            <path stroke-linecap="round" 
                                  stroke-linejoin="round" 
                                  stroke-width="2" 
                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                        @else
                            <!-- Eye Icon (Show Password) -->
                            <path stroke-linecap="round" 
                                  stroke-linejoin="round" 
                                  stroke-width="2" 
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" 
                                  stroke-linejoin="round" 
                                  stroke-width="2" 
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        @endif
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember" class="flex items-center">
                <input wire:model="form.remember" 
                       id="remember" 
                       type="checkbox" 
                       class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500" 
                       name="remember">
                <span class="ml-3 text-sm text-gray-700">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <!-- Login Button -->
        <div class="pt-2 sm:pt-4">
            <button type="submit" 
                    class="w-full flex justify-center py-2.5 sm:py-3 px-4 border border-transparent text-sm sm:text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 shadow-sm">
                <span wire:loading.remove>{{ __('Masuk') }}</span>
                <span wire:loading class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 sm:h-5 sm:w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </div>
    </form>
</div>
