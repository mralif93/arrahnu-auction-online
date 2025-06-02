@extends('layouts.app')

@section('title', 'Two-Factor Authentication')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-[#161615] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-brand rounded-full flex items-center justify-center mb-6">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                Verify Your Identity
            </h2>
            <p class="mt-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                We've sent a 6-digit verification code to<br>
                <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $user->email }}</span>
            </p>
        </div>

        <!-- 2FA Form -->
        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-lg p-8">
            <form method="POST" action="{{ route('2fa.verify') }}" class="space-y-6">
                @csrf

                <!-- Verification Code Input -->
                <div>
                    <label for="code" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Verification Code
                    </label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           maxlength="6" 
                           pattern="[0-9]{6}"
                           placeholder="000000"
                           class="w-full px-4 py-3 text-center text-2xl font-mono tracking-widest border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#1a1a19] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:ring-2 focus:ring-brand focus:border-transparent transition-colors @error('code') border-red-500 @enderror"
                           required
                           autocomplete="one-time-code"
                           autofocus>
                    @error('code')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Timer Display -->
                @if($remainingTime > 0)
                    <div class="text-center">
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Code expires in <span id="countdown">{{ gmdate('i:s', $remainingTime) }}</span>
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-brand hover:bg-brand-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Verify Code
                </button>
            </form>

            <!-- Action Links -->
            <div class="mt-6 space-y-3">
                <!-- Resend Code -->
                <div class="text-center">
                    <form method="POST" action="{{ route('2fa.resend') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="text-sm text-brand hover:text-brand-hover font-medium transition-colors">
                            Didn't receive the code? Send again
                        </button>
                    </form>
                </div>

                <!-- Cancel Login -->
                <div class="text-center">
                    <a href="{{ route('2fa.cancel') }}" 
                       class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                        Cancel and return to login
                    </a>
                </div>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                        Security Notice
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Never share your verification code with anyone</li>
                            <li>This code will expire in 5 minutes</li>
                            <li>You have 3 attempts to enter the correct code</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($remainingTime > 0)
<script>
    // Countdown timer
    let timeLeft = {{ $remainingTime }};
    const countdownElement = document.getElementById('countdown');
    
    const timer = setInterval(function() {
        if (timeLeft <= 0) {
            clearInterval(timer);
            countdownElement.textContent = 'Expired';
            countdownElement.parentElement.classList.remove('bg-blue-100', 'text-blue-800');
            countdownElement.parentElement.classList.add('bg-red-100', 'text-red-800');
            return;
        }
        
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        timeLeft--;
    }, 1000);
</script>
@endif

<script>
    // Auto-format code input
    document.getElementById('code').addEventListener('input', function(e) {
        // Remove any non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Auto-submit when 6 digits are entered
        if (this.value.length === 6) {
            this.form.submit();
        }
    });
    
    // Auto-focus and select all on page load
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('code');
        codeInput.focus();
        codeInput.select();
    });
</script>
@endsection
