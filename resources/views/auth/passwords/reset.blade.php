<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Reset Password - Arrahnu Auction</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Fallback CSS */
            </style>
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen">
        <!-- Navigation -->
        <header class="w-full border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/80 dark:bg-[#161615]/80 backdrop-blur-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <nav class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-[#f53003] dark:text-[#FF4433]">
                            Arrahnu Auction
                        </a>
                    </div>
                    
                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ url('/') }}#auctions" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Auctions
                        </a>
                        <a href="{{ url('/') }}#how-it-works" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            How It Works
                        </a>
                        <a href="{{ url('/') }}#about" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            About
                        </a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center gap-4">
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-2 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm font-medium transition-colors">
                            Log in
                        </a>
                        <a
                            href="{{ route('register') }}"
                            class="inline-block px-5 py-2 bg-[#f53003] dark:bg-[#FF4433] text-white hover:bg-[#d42a02] dark:hover:bg-[#e63322] rounded-sm text-sm font-medium transition-colors">
                            Get Started
                        </a>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center px-6 lg:px-8 py-12">
            <div class="w-full max-w-md">
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-[#f53003] dark:bg-[#FF4433] rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            Reset Password
                        </h1>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">
                            Enter your new password below to reset your account password.
                        </p>
                    </div>

                    <!-- Reset Password Form -->
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf

                        <!-- Hidden Token -->
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Email Address
                            </label>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                autocomplete="email" 
                                required 
                                value="{{ $email ?? old('email') }}"
                                readonly
                                class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#f8f8f7] dark:bg-[#2a2a28] text-[#706f6c] dark:text-[#A1A09A] cursor-not-allowed"
                            >
                            @error('email')
                                <p class="mt-2 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                New Password
                            </label>
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                autocomplete="new-password" 
                                required 
                                class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433] focus:border-transparent transition-colors"
                                placeholder="Enter your new password"
                            >
                            @error('password')
                                <p class="mt-2 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Confirm New Password
                            </label>
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                autocomplete="new-password" 
                                required 
                                class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433] focus:border-transparent transition-colors"
                                placeholder="Confirm your new password"
                            >
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button 
                                type="submit"
                                class="w-full px-5 py-3 bg-[#f53003] dark:bg-[#FF4433] text-white hover:bg-[#d42a02] dark:hover:bg-[#e63322] rounded-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433] focus:ring-offset-2"
                            >
                                Reset Password
                            </button>
                        </div>
                    </form>

                    <!-- Footer Links -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            Remember your password? 
                            <a href="{{ route('login') }}" class="font-medium text-[#f53003] dark:text-[#FF4433] hover:underline">
                                Back to Sign In
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="mt-6 p-4 bg-[#fff2f2] dark:bg-[#1D0002] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                    <h3 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Security Notice</h3>
                    <ul class="text-xs text-[#706f6c] dark:text-[#A1A09A] space-y-1">
                        <li>• Password must be at least 8 characters long</li>
                        <li>• Use a combination of letters, numbers, and symbols</li>
                        <li>• Don't reuse passwords from other accounts</li>
                        <li>• This reset link will expire in 60 minutes</li>
                    </ul>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-[#1b1b18] dark:bg-[#0a0a0a] text-white py-8">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
                <p class="text-[#A1A09A]">&copy; {{ date('Y') }} Arrahnu Auction. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
