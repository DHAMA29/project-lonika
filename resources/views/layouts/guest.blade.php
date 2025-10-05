<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('lonika-logo2.png') }}">
        <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('lonika-logo2.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('lonika-logo2.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('lonika-logo2.png') }}">
        <link rel="shortcut icon" href="{{ asset('lonika-logo2.png') }}">

        <title>Lonika - Penyedia Alat-Alat Media</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Simple Styles -->
        <style>
            .fade-in {
                animation: fadeIn 0.6s ease-in-out;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            /* Responsive breakpoints */
            @media (max-width: 640px) {
                .login-container {
                    padding: 1rem;
                }
                .login-form {
                    max-width: 100%;
                }
            }
            
            @media (min-width: 641px) and (max-width: 1023px) {
                .login-container {
                    padding: 1.5rem;
                }
                .login-form {
                    max-width: 28rem;
                }
            }
            
            @media (min-width: 1024px) {
                .login-form {
                    max-width: 24rem;
                }
            }
            
            @media (min-width: 1280px) {
                .login-form {
                    max-width: 28rem;
                }
            }
            
            /* Ensure full height on mobile */
            @media (max-width: 1023px) {
                .mobile-full-height {
                    min-height: 100vh;
                    min-height: 100dvh;
                }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Simple Professional Login Layout -->
        <div class="min-h-screen mobile-full-height bg-white flex flex-col lg:flex-row">
            <!-- Left Side - Simple Branding (Hidden on mobile) -->
            <div class="hidden lg:flex lg:w-1/2 xl:w-2/5 bg-gray-50 relative">
                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-center items-center w-full fade-in p-6 lg:p-8 xl:p-12">
                    <!-- Logo -->
                    <div class="mb-6 lg:mb-8 text-center">
                        <img src="{{ asset('images/lonika-logo.png') }}" 
                             alt="Lonika Logo" 
                             class="h-20 lg:h-30 xl:h-38 w-auto mx-auto"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <!-- Fallback Icon -->
                        <i class="fas fa-camera text-7xl lg:text-8xl xl:text-9xl text-red-600 hidden"></i>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Login Form -->
            <div class="w-full lg:w-1/2 xl:w-3/5 flex items-center justify-center login-container mobile-full-height bg-white">
                <div class="w-full login-form space-y-4 sm:space-y-6 lg:space-y-8 fade-in">
                    <!-- Mobile Logo (visible on small screens) -->
                    <div class="text-center lg:hidden">
                        <img src="{{ asset('images/lonika-logo.png') }}" 
                             alt="Lonika Logo" 
                             class="h-20 sm:h-24 w-auto mx-auto"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <!-- Fallback Icon -->
                        <i class="fas fa-camera text-6xl sm:text-7xl text-red-600 hidden"></i>
                    </div>
                    
                    <!-- Welcome Text -->
                    <div class="text-center">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
                        <p class="text-sm sm:text-base text-gray-600">Masuk ke akun Anda</p>
                    </div>
                    
                    <!-- Form Container -->
                    <div class="space-y-4 sm:space-y-6">
                        {{ $slot }}
                    </div>
                    
                    <!-- Footer -->
                    <div class="text-center text-xs sm:text-sm text-gray-500 pt-6 sm:pt-8">
                        <p>&copy; {{ date('Y') }} Lonika. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
