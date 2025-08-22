@extends('layouts.app')

@section('title', 'Sign In - Arrahnu Auction')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="w-full max-w-md space-y-8">
        <div class="bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-brand rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    Welcome Back
                </h1>
                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                    Sign in to your account to continue
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
                    class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all duration-200"
                    placeholder="Enter your email address"
                >
                @error('email')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
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
                    class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition-all duration-200"
                    placeholder="Enter your password"
                >
                @error('password')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input
                        id="remember"
                        name="remember"
                        type="checkbox"
                        class="h-4 w-4 text-brand focus:ring-brand border-[#e3e3e0] dark:border-[#3E3E3A] rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Remember me
                    </label>
                </div>
                <div>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-brand hover:text-brand-hover transition-colors">
                        Forgot password?
                    </a>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button
                    type="submit"
                    class="w-full px-6 py-3 bg-brand hover:bg-brand-hover text-white font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 shadow-sm hover:shadow-md"
                >
                    Sign In to Your Account
                </button>
            </div>
        </form>

            <!-- Footer Links -->
            <div class="mt-8 text-center">
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-brand hover:text-brand-hover transition-colors">
                        Create an account
                    </a>
                </p>
            </div>
        </div>

        <!-- Demo Credentials -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Demo Account</h3>
                    <div class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                        <p><span class="font-medium">Email:</span> test@example.com</p>
                        <p><span class="font-medium">Password:</span> password</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
