<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sign In - Arrahnu Auction</title>

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
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC]">
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
                            href="{{ route('register') }}"
                            class="inline-block px-5 py-2 bg-[#f53003] dark:bg-[#FF4433] text-white hover:bg-[#d42a02] dark:hover:bg-[#e63322] rounded-sm text-sm font-medium transition-colors">
                            Get Started
                        </a>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content flex items-center justify-center px-6 lg:px-8 py-12">
            <div class="w-full max-w-md">
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            Sign In
                        </h1>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">
                            Welcome back! Please sign in to your account.
                        </p>
                    </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

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
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433] focus:border-transparent transition-colors"
                    placeholder="Enter your email"
                >
                @error('email')
                    <p class="mt-2 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                    class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433] focus:border-transparent transition-colors"
                    placeholder="Enter your password"
                >
                @error('password')
                    <p class="mt-2 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input
                        id="remember"
                        name="remember"
                        type="checkbox"
                        class="h-4 w-4 text-[#f53003] dark:text-[#FF4433] focus:ring-[#f53003] dark:focus:ring-[#FF4433] border-[#e3e3e0] dark:border-[#3E3E3A] rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Remember me
                    </label>
                </div>
                <div>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#f53003] dark:text-[#FF4433] hover:underline">
                        Forgot password?
                    </a>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button
                    type="submit"
                    class="w-full px-5 py-3 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] border border-[#1b1b18] dark:border-[#eeeeec] hover:bg-black dark:hover:bg-white hover:border-black dark:hover:border-white rounded-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433] focus:ring-offset-2"
                >
                    Sign In
                </button>
            </div>
        </form>

                    <!-- Footer Links -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="font-medium text-[#f53003] dark:text-[#FF4433] hover:underline">
                                Sign up
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Demo Credentials -->
                <div class="mt-6 p-4 bg-[#fff2f2] dark:bg-[#1D0002] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                    <h3 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Demo Credentials</h3>
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mb-1">
                        <strong>Email:</strong> test@example.com
                    </p>
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                        <strong>Password:</strong> password
                    </p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="sticky-footer bg-[#1b1b18] dark:bg-[#0a0a0a] text-white py-8">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
                <p class="text-[#A1A09A]">&copy; {{ date('Y') }} Arrahnu Auction. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
