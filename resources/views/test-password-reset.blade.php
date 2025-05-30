<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Password Reset Test - Arrahnu Auction</title>

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
                        <a href="{{ url('/') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Home
                        </a>
                        <a href="{{ route('login') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Register
                        </a>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 px-6 lg:px-8 py-12">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            🔐 Password Reset System Test
                        </h1>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">
                            Test the complete password reset functionality
                        </p>
                    </div>

                    <!-- Test Accounts -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="p-6 bg-[#fff2f2] dark:bg-[#1D0002] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">Test Account #1</h3>
                            <div class="space-y-2 text-sm">
                                <p><strong>Email:</strong> test@example.com</p>
                                <p><strong>Password:</strong> password</p>
                                <p class="text-[#706f6c] dark:text-[#A1A09A]">Use this account for regular login testing</p>
                            </div>
                        </div>

                        <div class="p-6 bg-[#fff2f2] dark:bg-[#1D0002] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">Test Account #2</h3>
                            <div class="space-y-2 text-sm">
                                <p><strong>Email:</strong> reset@example.com</p>
                                <p><strong>Password:</strong> oldpassword</p>
                                <p class="text-[#706f6c] dark:text-[#A1A09A]">Use this account for password reset testing</p>
                            </div>
                        </div>
                    </div>

                    <!-- Test Steps -->
                    <div class="space-y-6">
                        <h2 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">🧪 Test Steps</h2>

                        <!-- Step 1 -->
                        <div class="p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                                Step 1: Access Forgot Password Page
                            </h3>
                            <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                Navigate to the forgot password page and test the form.
                            </p>
                            <a href="{{ route('password.request') }}" 
                               class="inline-block px-6 py-3 bg-[#f53003] dark:bg-[#FF4433] text-white hover:bg-[#d42a02] dark:hover:bg-[#e63322] rounded-sm font-medium transition-colors">
                                Go to Forgot Password
                            </a>
                        </div>

                        <!-- Step 2 -->
                        <div class="p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                                Step 2: Test Login Page Link
                            </h3>
                            <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                Check that the "Forgot password?" link appears on the login page.
                            </p>
                            <a href="{{ route('login') }}" 
                               class="inline-block px-6 py-3 bg-[#f53003] dark:bg-[#FF4433] text-white hover:bg-[#d42a02] dark:hover:bg-[#e63322] rounded-sm font-medium transition-colors">
                                Go to Login Page
                            </a>
                        </div>

                        <!-- Step 3 -->
                        <div class="p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                                Step 3: Submit Reset Request
                            </h3>
                            <div class="text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                <p class="mb-2">Submit a password reset request for: <strong>reset@example.com</strong></p>
                                <p class="text-sm">The email will be logged to <code>storage/logs/laravel.log</code> since we're using the log mail driver.</p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                                Step 4: Check Email Content
                            </h3>
                            <div class="text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                <p class="mb-2">After submitting the form, check the log file for the email content:</p>
                                <code class="block bg-[#f8f8f7] dark:bg-[#2a2a28] p-3 rounded text-sm">
                                    tail -f storage/logs/laravel.log
                                </code>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                                Step 5: Test Reset Link
                            </h3>
                            <div class="text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                <p class="mb-2">Copy the reset URL from the email and visit it to test the reset password form.</p>
                                <p class="text-sm">The URL will look like: <code>/reset-password/{token}?email=reset@example.com</code></p>
                            </div>
                        </div>

                        <!-- Step 6 -->
                        <div class="p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                                Step 6: Complete Password Reset
                            </h3>
                            <div class="text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                <p class="mb-2">Enter a new password and confirm it to complete the reset process.</p>
                                <p class="text-sm">After successful reset, you'll be redirected to the login page with a success message.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="mt-8 p-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-3">
                            ✅ Implemented Features
                        </h3>
                        <ul class="space-y-2 text-sm text-green-700 dark:text-green-300">
                            <li>• Beautiful, responsive forgot password page</li>
                            <li>• Professional reset password form</li>
                            <li>• Custom branded email template</li>
                            <li>• Secure token-based reset system</li>
                            <li>• Form validation and error handling</li>
                            <li>• Success/status messages</li>
                            <li>• Consistent design with master template</li>
                            <li>• Mobile-responsive design</li>
                            <li>• Dark mode support</li>
                            <li>• Security notices and help text</li>
                        </ul>
                    </div>
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
