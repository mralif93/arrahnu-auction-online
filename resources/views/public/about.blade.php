@extends('layouts.app')

@section('title', 'About Us - Arrahnu Auction')

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl lg:text-5xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">
                About Arrahnu Auction
            </h1>
            <p class="text-xl text-[#706f6c] dark:text-[#A1A09A] max-w-3xl mx-auto">
                We're passionate about connecting collectors with authentic treasures and creating a trusted marketplace for rare and valuable items.
            </p>
        </div>

        <!-- Our Story -->
        <section class="bg-white dark:bg-[#161615] shadow-lg rounded-lg p-8 mb-16 border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">
                        Our Story
                    </h2>
                    <div class="space-y-4 text-[#706f6c] dark:text-[#A1A09A]">
                        <p>
                            Founded in 2024, Arrahnu Auction emerged from a simple belief: every collector deserves access to authentic, high-quality items with complete transparency and trust.
                        </p>
                        <p>
                            Our founders, passionate collectors themselves, experienced firsthand the challenges of finding reliable auction platforms. They envisioned a marketplace where authenticity is guaranteed, transactions are secure, and the community thrives on mutual respect and shared passion.
                        </p>
                        <p>
                            Today, we're proud to serve thousands of collectors, dealers, and enthusiasts worldwide, facilitating millions of ringgit in transactions while maintaining our commitment to authenticity and excellence.
                        </p>
                    </div>
                </div>
                <div class="bg-[#fff2f2] dark:bg-[#1D0002] rounded-lg p-8">
                    <div class="text-center">
                        <div class="w-24 h-24 bg-brand rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                            Built by Collectors, for Collectors
                        </h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">
                            We understand the passion, the hunt, and the joy of discovering that perfect piece for your collection.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Mission -->
        <section class="mb-16">
            <h2 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-8 text-center">
                Our Mission & Values
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Authenticity -->
                <div class="bg-white dark:bg-[#161615] shadow-lg rounded-lg p-6 text-center border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="w-16 h-16 bg-brand rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                        Authenticity First
                    </h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">
                        Every item is thoroughly vetted by our team of experts to ensure authenticity and quality.
                    </p>
                </div>

                <!-- Trust -->
                <div class="bg-white dark:bg-[#161615] shadow-lg rounded-lg p-6 text-center border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="w-16 h-16 bg-brand rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                        Trust & Security
                    </h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">
                        Secure transactions, protected payments, and a trusted community of verified users.
                    </p>
                </div>

                <!-- Excellence -->
                <div class="bg-white dark:bg-[#161615] shadow-lg rounded-lg p-6 text-center border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="w-16 h-16 bg-brand rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                        Excellence
                    </h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">
                        Continuous improvement in our platform, services, and user experience.
                    </p>
                </div>
            </div>
        </section>

        <!-- Statistics -->
        <section class="bg-brand rounded-lg p-8 mb-16">
            <h2 class="text-3xl font-bold text-white mb-8 text-center">
                Our Impact
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                <div>
                    <div class="text-3xl lg:text-4xl font-bold mb-2">10,000+</div>
                    <div class="text-sm lg:text-base opacity-90">Active Bidders</div>
                </div>
                <div>
                    <div class="text-3xl lg:text-4xl font-bold mb-2">500+</div>
                    <div class="text-sm lg:text-base opacity-90">Monthly Auctions</div>
                </div>
                <div>
                    <div class="text-3xl lg:text-4xl font-bold mb-2">RM 2M+</div>
                    <div class="text-sm lg:text-base opacity-90">Items Sold</div>
                </div>
                <div>
                    <div class="text-3xl lg:text-4xl font-bold mb-2">99.8%</div>
                    <div class="text-sm lg:text-base opacity-90">Satisfaction Rate</div>
                </div>
            </div>
        </section>

        <!-- Team -->
        <section class="mb-16">
            <h2 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-8 text-center">
                Our Team
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Team Member 1 -->
                <div class="bg-white dark:bg-[#161615] shadow-lg rounded-lg p-6 text-center border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="w-24 h-24 bg-brand rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-white">AK</span>
                    </div>
                    <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Ahmad Kamal
                    </h3>
                    <p class="text-brand font-medium mb-3">
                        Founder & CEO
                    </p>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        20+ years in Islamic finance and collectibles, former specialist with expertise in Sharia-compliant transactions.
                    </p>
                </div>

                <!-- Team Member 2 -->
                <div class="bg-white dark:bg-[#161615] shadow-lg rounded-lg p-6 text-center border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="w-24 h-24 bg-brand rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-white">SM</span>
                    </div>
                    <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Siti Mariam
                    </h3>
                    <p class="text-brand font-medium mb-3">
                        Head of Authentication
                    </p>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Expert appraiser with certifications in precious metals, jewelry, and Islamic artifacts authentication.
                    </p>
                </div>

                <!-- Team Member 3 -->
                <div class="bg-white dark:bg-[#161615] shadow-lg rounded-lg p-6 text-center border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="w-24 h-24 bg-brand rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-white">MR</span>
                    </div>
                    <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Muhammad Rahman
                    </h3>
                    <p class="text-brand font-medium mb-3">
                        CTO
                    </p>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Technology leader with 15+ years building secure, scalable platforms for Islamic financial services.
                    </p>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <section class="bg-white dark:bg-[#161615] shadow-lg rounded-lg p-8 text-center border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h2 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">
                Get in Touch
            </h2>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] mb-8 max-w-2xl mx-auto">
                Have questions about our platform, need help with authentication, or want to learn more about selling? We're here to help.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Email</h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">support@arrahnu-auction.com</p>
                </div>
                <div>
                    <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Phone</h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">+60 3-1234 5678</p>
                </div>
                <div>
                    <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Hours</h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">24/7 Support Available</p>
                </div>
            </div>
            @guest
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-brand hover:bg-brand-hover text-white font-semibold rounded-lg transition-colors shadow-lg">
                    Join Our Community
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            @endguest
        </section>
    </div>
@endsection
