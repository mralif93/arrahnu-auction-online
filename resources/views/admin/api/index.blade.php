@extends('layouts.admin')

@section('title', 'API Management')

@section('header-content')
    <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">API Management</h1>
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">View and manage API endpoints</p>
@endsection

@section('content')
    <div class="space-y-6">
        <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg overflow-hidden">
            <div class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">API Status Dashboard</h2>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Last Updated: <span id="last-updated">{{ now()->format('Y-m-d H:i:s') }}</span>
                    </span>
                    <button onclick="window.location.reload();" class="inline-flex items-center px-3 py-1.5 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh Tests
                    </button>
                </div>
            </div>
        </div>

        @foreach($apiEndpoints as $section => $categories)
            <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg overflow-hidden">
                <div class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] px-6 py-4">
                    <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $section }}</h2>
                </div>

                @foreach($categories as $category => $endpoints)
                    @if(count($endpoints) > 0)
                        <div class="px-6 py-4">
                            <h3 class="text-md font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">{{ $category }}</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr>
                                            <th width="10%" class="bg-[#f8f8f7] dark:bg-[#1a1a19] px-6 py-3 text-left text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] border-y border-[#e3e3e0] dark:border-[#3E3E3A] first:border-l last:border-r">
                                                Method
                                            </th>
                                            <th width="25%" class="bg-[#f8f8f7] dark:bg-[#1a1a19] px-6 py-3 text-left text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] border-y border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                Endpoint
                                            </th>
                                            <th width="25%" class="bg-[#f8f8f7] dark:bg-[#1a1a19] px-6 py-3 text-left text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] border-y border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                Description
                                            </th>
                                            <th width="20%" class="bg-[#f8f8f7] dark:bg-[#1a1a19] px-6 py-3 text-left text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] border-y border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                Status
                                            </th>
                                            <th width="20%" class="bg-[#f8f8f7] dark:bg-[#1a1a19] px-6 py-3 text-left text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] border-y border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                Response Time
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                                        @foreach($endpoints as $endpoint)
                                            <tr class="hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center justify-center min-w-[70px] px-3 py-1 rounded-full text-xs font-medium
                                                        @if($endpoint['method'] === 'GET') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                                        @elseif($endpoint['method'] === 'POST') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                                        @elseif($endpoint['method'] === 'PUT') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                                                        @elseif($endpoint['method'] === 'DELETE') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300
                                                        @endif">
                                                        {{ $endpoint['method'] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 font-mono text-sm text-[#1b1b18] dark:text-[#EDEDEC] whitespace-nowrap overflow-hidden text-ellipsis">
                                                    {{ $endpoint['endpoint'] }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                    {{ $endpoint['description'] }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center space-x-2">
                                                        @php
                                                            $statusColor = match($endpoint['status']['health_status']) {
                                                                'healthy' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
                                                                'degraded', 'slow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
                                                                'redirect' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
                                                                'client-error', 'error' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
                                                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300'
                                                            };

                                                            $statusIcon = match($endpoint['status']['health_status']) {
                                                                'healthy' => 'check',
                                                                'degraded' => 'exclamation',
                                                                'slow' => 'clock',
                                                                'redirect' => 'share',
                                                                'client-error' => 'times',
                                                                'error' => 'exclamation-triangle',
                                                                default => 'question'
                                                            };
                                                        @endphp

                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                                            <i class="fas fa-{{ $statusIcon }} mr-1"></i>
                                                            {{ $endpoint['status']['status'] }}
                                                        </span>

                                                        <div class="flex items-center">
                                                            @if($endpoint['status']['status_code'] > 0)
                                                                <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                                    {{ $endpoint['status']['status_code'] }}
                                                                </span>
                                                            @endif
                                                            <button type="button" 
                                                                    class="ml-1 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
                                                                    data-tippy-content="{{ $endpoint['status']['message'] }}"
                                                                    data-tippy-placement="top">
                                                                <i class="fas fa-info-circle"></i>
                                                            </button>
                                                        </div>

                                                        <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                            {{ \Carbon\Carbon::parse($endpoint['status']['last_tested'])->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if($endpoint['status']['exists'])
                                                        @if($endpoint['status']['response_time'] > 0)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                @if($endpoint['status']['response_time'] < 0.3) bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                                                @elseif($endpoint['status']['response_time'] < 1.0) bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                                                                @else bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300
                                                                @endif">
                                                                {{ number_format($endpoint['status']['response_time'] * 1000, 0) }}ms
                                                            </span>
                                                        @else
                                                            <span class="text-[#706f6c] dark:text-[#A1A09A]">-</span>
                                                        @endif
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300">
                                                            Not Available
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>

    <script>
        // Initialize tooltips
        tippy('[data-tippy-content]', {
            theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
            allowHTML: true
        });

        // Auto-refresh the page every 5 minutes
        setTimeout(function() {
            window.location.reload();
        }, 5 * 60 * 1000);

        // Update the last updated time
        function updateLastUpdated() {
            const now = new Date();
            document.getElementById('last-updated').textContent = now.toLocaleString();
        }

        // Update time every minute
        setInterval(updateLastUpdated, 60000);
    </script>
@endsection 