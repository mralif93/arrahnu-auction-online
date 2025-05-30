<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Color System Test - Arrahnu Auction</title>

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
                        <a href="{{ url('/') }}" class="text-xl font-bold text-brand">
                            Arrahnu Auction
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ url('/') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Home
                        </a>
                        <a href="{{ route('how-it-works') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            How It Works
                        </a>
                        <a href="{{ route('about') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
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
                            class="inline-block px-5 py-2 bg-brand bg-brand-hover text-white rounded-sm text-sm font-medium transition-colors">
                            Get Started
                        </a>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content px-6 lg:px-8 py-12">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl lg:text-5xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">
                        🎨 Global Color System
                    </h1>
                    <p class="text-xl text-[#706f6c] dark:text-[#A1A09A] max-w-3xl mx-auto">
                        Testing the new global color parameter system with standardized brand color <span class="text-brand font-bold">#FE5000</span>
                    </p>
                </div>

                <!-- Color Palette -->
                <section class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Brand Color Palette</h2>

                    <!-- Primary Colors -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center">
                            <div class="w-24 h-24 mx-auto mb-4 rounded-lg shadow-lg" style="background-color: var(--color-primary);"></div>
                            <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Primary</h3>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">#FE5000</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">var(--color-primary)</p>
                        </div>
                        <div class="text-center">
                            <div class="w-24 h-24 mx-auto mb-4 rounded-lg shadow-lg" style="background-color: var(--color-primary-hover);"></div>
                            <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Primary Hover</h3>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">#E5470A</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">var(--color-primary-hover)</p>
                        </div>
                        <div class="text-center">
                            <div class="w-24 h-24 mx-auto mb-4 rounded-lg shadow-lg" style="background-color: var(--color-primary-dark);"></div>
                            <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Primary Dark</h3>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">#CC3F00</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">var(--color-primary-dark)</p>
                        </div>
                    </div>

                    <!-- Brand Color Scale -->
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Brand Color Scale</h3>
                    <div class="grid grid-cols-5 md:grid-cols-10 gap-2 mb-6">
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-2 rounded shadow" style="background-color: var(--color-brand-50);"></div>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">50</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-2 rounded shadow" style="background-color: var(--color-brand-100);"></div>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">100</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-2 rounded shadow" style="background-color: var(--color-brand-200);"></div>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">200</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-2 rounded shadow" style="background-color: var(--color-brand-300);"></div>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">300</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-2 rounded shadow" style="background-color: var(--color-brand-400);"></div>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">400</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-2 rounded shadow border-2 border-gray-800" style="background-color: var(--color-brand-500);"></div>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] font-bold">500</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-2 rounded shadow" style="background-color: var(--color-brand-600);"></div>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">600</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-2 rounded shadow" style="background-color: var(--color-brand-700);"></div>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">700</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-2 rounded shadow" style="background-color: var(--color-brand-800);"></div>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">800</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-2 rounded shadow" style="background-color: var(--color-brand-900);"></div>
                            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">900</p>
                        </div>
                    </div>
                </section>

                <!-- Usage Examples -->
                <section class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Usage Examples</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Text Colors -->
                        <div>
                            <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">Text Colors</h3>
                            <div class="space-y-2">
                                <p class="text-brand">Brand text color</p>
                                <p class="text-brand-hover">Brand hover color</p>
                                <p style="color: var(--color-primary);">CSS Variable</p>
                            </div>
                        </div>

                        <!-- Background Colors -->
                        <div>
                            <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">Background Colors</h3>
                            <div class="space-y-2">
                                <div class="bg-brand text-white p-2 rounded">Brand background</div>
                                <div class="bg-brand-hover text-white p-2 rounded">Brand hover background</div>
                                <div class="p-2 rounded text-white" style="background-color: var(--color-primary);">CSS Variable</div>
                            </div>
                        </div>

                        <!-- Border Colors -->
                        <div>
                            <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">Border Colors</h3>
                            <div class="space-y-2">
                                <div class="border-2 border-brand p-2 rounded">Brand border</div>
                                <div class="border-2 border-brand-hover p-2 rounded">Brand hover border</div>
                                <div class="border-2 p-2 rounded" style="border-color: var(--color-primary);">CSS Variable</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Implementation Guide -->
                <section class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-8">
                    <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Implementation Guide</h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- CSS Variables -->
                        <div>
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">CSS Variables</h3>
                            <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg text-sm font-mono">
                                <p class="mb-2">/* Primary brand colors */</p>
                                <p class="mb-2">var(--color-primary) <span class="text-brand">/* #FE5000 */</span></p>
                                <p class="mb-2">var(--color-primary-hover) <span class="text-brand">/* #E5470A */</span></p>
                                <p class="mb-2">var(--color-primary-dark) <span class="text-brand">/* #CC3F00 */</span></p>
                                <p class="mt-4 mb-2">/* Brand color scale */</p>
                                <p class="mb-2">var(--color-brand-500) <span class="text-brand">/* #FE5000 */</span></p>
                                <p>var(--color-brand-600) <span class="text-brand">/* #E5470A */</span></p>
                            </div>
                        </div>

                        <!-- Utility Classes -->
                        <div>
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Utility Classes</h3>
                            <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg text-sm font-mono">
                                <p class="mb-2">/* Text colors */</p>
                                <p class="mb-2">.text-brand</p>
                                <p class="mb-2">.text-brand-hover:hover</p>
                                <p class="mt-4 mb-2">/* Background colors */</p>
                                <p class="mb-2">.bg-brand</p>
                                <p class="mb-2">.bg-brand-hover:hover</p>
                                <p class="mt-4 mb-2">/* Border colors */</p>
                                <p class="mb-2">.border-brand</p>
                                <p>.border-brand-hover:hover</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <h4 class="font-semibold text-green-800 dark:text-green-200 mb-2">✅ Benefits</h4>
                        <ul class="text-sm text-green-700 dark:text-green-300 space-y-1">
                            <li>• Consistent brand colors across all pages</li>
                            <li>• Easy to update globally by changing one value</li>
                            <li>• Better maintainability and scalability</li>
                            <li>• Automatic dark mode support</li>
                            <li>• Performance optimized with CSS variables</li>
                        </ul>
                    </div>
                </section>
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
