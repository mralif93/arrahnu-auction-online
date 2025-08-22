@extends('layouts.app')

@section('title', 'Forgot Password - Arrahnu Auction')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="w-full max-w-md space-y-8">
        <div class="bg-white dark:bg-[#161615] shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-brand rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    Forgot Password?
                </h1>
                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                    No worries! Enter your email and we'll send you a reset link.
                </p>
            </div>

            <!-- Success Message -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-green-800 dark:text-green-200 font-medium">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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

                <!-- Submit Button -->
                <div>
                    <button
                        type="submit"
                        class="w-full px-6 py-3 bg-brand hover:bg-brand-hover text-white font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 shadow-sm hover:shadow-md"
                    >
                        Send Reset Link
                    </button>
                </div>
            </form>

            <!-- Footer Links -->
            <div class="mt-8 text-center">
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    Remember your password?
                    <a href="{{ route('login') }}" class="font-medium text-brand hover:text-brand-hover transition-colors">
                        Back to Sign In
                    </a>
                </p>
            </div>
        </div>

        <!-- Help Section -->
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-6">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-amber-900 dark:text-amber-100 mb-2">Need Help?</h3>
                    <p class="text-sm text-amber-800 dark:text-amber-200 mb-2">
                        If you don't receive the email within a few minutes, please check your spam folder.
                    </p>
                    <p class="text-sm text-amber-800 dark:text-amber-200">
                        Still having trouble? <a href="#" class="font-medium text-brand hover:text-brand-hover transition-colors">Contact Support</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
