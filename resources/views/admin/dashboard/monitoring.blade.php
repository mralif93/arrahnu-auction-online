@extends('layouts.admin')

@section('title', 'Monitoring')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Monitoring</h1>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">Real-time system monitoring and analytics</p>
        </div>
        <div class="flex items-center space-x-3">
            <!-- Auto Refresh Toggle -->
            <label class="flex items-center">
                <input type="checkbox" id="autoRefresh" class="sr-only">
                <div class="relative">
                    <div class="block bg-[#e3e3e0] dark:bg-[#3E3E3A] w-14 h-8 rounded-full"></div>
                    <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                </div>
                <span class="ml-3 text-sm text-[#706f6c] dark:text-[#A1A09A]">Auto Refresh</span>
            </label>
            <!-- Last Updated -->
            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]" id="lastUpdated">
                Last updated: <span id="lastUpdatedTime">--</span>
            </span>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-brand"></div>
            <span class="text-[#1b1b18] dark:text-[#EDEDEC]">Loading dashboard data...</span>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="overviewCards">
        <!-- Loading placeholders -->
        <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 animate-pulse">
            <div class="flex items-center">
                <div class="p-2 bg-gray-200 dark:bg-gray-700 rounded-lg w-10 h-10"></div>
                <div class="ml-4 flex-1">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-20 mb-2"></div>
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-16 mb-1"></div>
                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-24"></div>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 animate-pulse">
            <div class="flex items-center">
                <div class="p-2 bg-gray-200 dark:bg-gray-700 rounded-lg w-10 h-10"></div>
                <div class="ml-4 flex-1">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-20 mb-2"></div>
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-16 mb-1"></div>
                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-24"></div>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 animate-pulse">
            <div class="flex items-center">
                <div class="p-2 bg-gray-200 dark:bg-gray-700 rounded-lg w-10 h-10"></div>
                <div class="ml-4 flex-1">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-20 mb-2"></div>
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-16 mb-1"></div>
                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-24"></div>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 animate-pulse">
            <div class="flex items-center">
                <div class="p-2 bg-gray-200 dark:bg-gray-700 rounded-lg w-10 h-10"></div>
                <div class="ml-4 flex-1">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-20 mb-2"></div>
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-16 mb-1"></div>
                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-24"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health Status -->
    <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
        <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">System Health</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="systemHealth">
            <!-- System health indicators will be populated by JavaScript -->
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Analytics Chart -->
        <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">User Growth</h2>
                <select id="userPeriod" class="text-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md px-3 py-1 bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC]">
                    <option value="7">Last 7 days</option>
                    <option value="30" selected>Last 30 days</option>
                    <option value="90">Last 90 days</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>

        <!-- Auction Analytics Chart -->
        <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Auction Performance</h2>
                <select id="auctionPeriod" class="text-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md px-3 py-1 bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC]">
                    <option value="7">Last 7 days</option>
                    <option value="30" selected>Last 30 days</option>
                    <option value="90">Last 90 days</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="auctionPerformanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Status Distribution Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Status Distribution -->
        <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">User Status Distribution</h2>
            <div class="h-64">
                <canvas id="userStatusChart"></canvas>
            </div>
        </div>

        <!-- Auction Status Distribution -->
        <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Auction Status Distribution</h2>
            <div class="h-64">
                <canvas id="auctionStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Activity Feed and Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Activity Feed -->
        <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Recent Activity</h2>
                <select id="activityType" class="text-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md px-3 py-1 bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC]">
                    <option value="all">All Activities</option>
                    <option value="users">User Activities</option>
                    <option value="auctions">Auction Activities</option>
                    <option value="system">System Activities</option>
                </select>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto" id="activityFeed">
                <!-- Activity items will be populated by JavaScript -->
            </div>
        </div>

        <!-- System Alerts -->
        <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
            <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">System Alerts</h2>
            <div class="space-y-3 max-h-96 overflow-y-auto" id="systemAlerts">
                <!-- Alert items will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- System Metrics -->
    <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
        <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">System Performance Metrics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="systemMetrics">
            <!-- System metrics will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Monitoring Dashboard JavaScript
class DashboardMonitoring {
    constructor() {
        this.autoRefresh = false;
        this.refreshInterval = null;
        this.charts = {};
        this.init();
    }

    init() {
        console.log('Monitoring Dashboard initialized');
        this.setupEventListeners();

        // Add a timeout to prevent infinite loading
        setTimeout(() => {
            if (document.getElementById('loadingOverlay').classList.contains('hidden') === false) {
                this.hideLoading();
                this.showError('Dashboard loading timed out. Please refresh the page.');
            }
        }, 10000); // 10 second timeout

        this.loadAllData();
        this.updateLastUpdatedTime();
    }

    setupEventListeners() {
        // Auto refresh toggle
        const autoRefreshToggle = document.getElementById('autoRefresh');
        autoRefreshToggle.addEventListener('change', (e) => {
            this.autoRefresh = e.target.checked;
            if (this.autoRefresh) {
                this.startAutoRefresh();
                e.target.parentElement.querySelector('.dot').style.transform = 'translateX(24px)';
                e.target.parentElement.querySelector('.block').style.backgroundColor = '#FE5000';
            } else {
                this.stopAutoRefresh();
                e.target.parentElement.querySelector('.dot').style.transform = 'translateX(0px)';
                e.target.parentElement.querySelector('.block').style.backgroundColor = '#e3e3e0';
            }
        });

        // Period change listeners
        document.getElementById('userPeriod').addEventListener('change', () => {
            this.loadUserAnalytics();
        });

        document.getElementById('auctionPeriod').addEventListener('change', () => {
            this.loadAuctionAnalytics();
        });

        document.getElementById('activityType').addEventListener('change', () => {
            this.loadActivityFeed();
        });
    }

    showLoading() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
    }

    hideLoading() {
        document.getElementById('loadingOverlay').classList.add('hidden');
    }

    async loadAllData() {
        this.showLoading();
        try {
            await Promise.all([
                this.loadOverview(),
                this.loadUserAnalytics(),
                this.loadAuctionAnalytics(),
                this.loadSystemMetrics(),
                this.loadActivityFeed(),
                this.loadAlerts()
            ]);
        } catch (error) {
            console.error('Error loading dashboard data:', error);
            this.showError('Failed to load dashboard data. Please refresh the page.');
        } finally {
            this.hideLoading();
            this.updateLastUpdatedTime();
        }
    }

    showError(message) {
        // Create error notification
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                ${message}
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        document.body.appendChild(errorDiv);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (errorDiv.parentElement) {
                errorDiv.remove();
            }
        }, 5000);
    }

    async loadOverview() {
        try {
            const response = await fetch('{{ route("admin.dashboard.monitoring.overview") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                if (response.status === 401 || response.status === 403) {
                    this.showError('Authentication required. Please refresh the page and login again.');
                    return;
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                this.renderOverviewCards(data.data.overview);
                this.renderSystemHealth(data.data.overview.system_health);
            } else {
                console.error('Overview API error:', data.message);
                this.showError('Failed to load overview data: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error loading overview:', error);
            this.showError('Failed to load overview data. Please check your connection.');
        }
    }

    renderOverviewCards(overview) {
        const container = document.getElementById('overviewCards');
        const summary = overview.summary;
        
        container.innerHTML = `
            <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Total Users</p>
                        <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">${summary.total_users}</p>
                        <p class="text-xs text-green-600">${summary.users_growth_percentage > 0 ? '+' : ''}${summary.users_growth_percentage}% this month</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Active Users</p>
                        <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">${summary.active_users}</p>
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">${summary.pending_users} pending approval</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Total Auctions</p>
                        <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">${summary.total_auctions}</p>
                        <p class="text-xs text-green-600">${summary.auctions_growth_percentage > 0 ? '+' : ''}${summary.auctions_growth_percentage}% this month</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Active Auctions</p>
                        <p class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">${summary.active_auctions}</p>
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Currently running</p>
                    </div>
                </div>
            </div>
        `;
    }

    renderSystemHealth(health) {
        const container = document.getElementById('systemHealth');
        
        const getStatusColor = (status) => {
            return status === 'healthy' ? 'text-green-600' : 'text-red-600';
        };

        const getStatusIcon = (status) => {
            return status === 'healthy' 
                ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
        };
        
        container.innerHTML = `
            <div class="flex items-center justify-between p-4 bg-[#f8f8f7] dark:bg-[#1a1a19] rounded-lg">
                <div class="flex items-center">
                    <div class="${getStatusColor(health.database.status)} mr-3">
                        ${getStatusIcon(health.database.status)}
                    </div>
                    <div>
                        <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Database</p>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">${health.database.response_time_ms}ms</p>
                    </div>
                </div>
                <span class="text-sm font-medium ${getStatusColor(health.database.status)} capitalize">${health.database.status}</span>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-[#f8f8f7] dark:bg-[#1a1a19] rounded-lg">
                <div class="flex items-center">
                    <div class="${getStatusColor(health.cache.status)} mr-3">
                        ${getStatusIcon(health.cache.status)}
                    </div>
                    <div>
                        <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Cache</p>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">${health.cache.response_time_ms}ms</p>
                    </div>
                </div>
                <span class="text-sm font-medium ${getStatusColor(health.cache.status)} capitalize">${health.cache.status}</span>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-[#f8f8f7] dark:bg-[#1a1a19] rounded-lg">
                <div class="flex items-center">
                    <div class="${getStatusColor(health.storage.status)} mr-3">
                        ${getStatusIcon(health.storage.status)}
                    </div>
                    <div>
                        <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Storage</p>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">File System</p>
                    </div>
                </div>
                <span class="text-sm font-medium ${getStatusColor(health.storage.status)} capitalize">${health.storage.status}</span>
            </div>
        `;
    }

    startAutoRefresh() {
        this.refreshInterval = setInterval(() => {
            this.loadAllData();
        }, 30000); // Refresh every 30 seconds
    }

    stopAutoRefresh() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
            this.refreshInterval = null;
        }
    }

    updateLastUpdatedTime() {
        const now = new Date();
        document.getElementById('lastUpdatedTime').textContent = now.toLocaleTimeString();
    }

    async loadUserAnalytics() {
        try {
            const period = document.getElementById('userPeriod').value;
            const response = await fetch(`{{ route("admin.dashboard.monitoring.user-analytics") }}?period=${period}`);
            const data = await response.json();

            if (data.success) {
                this.renderUserGrowthChart(data.data.analytics.user_growth);
                this.renderUserStatusChart(data.data.analytics.user_status_distribution);
            }
        } catch (error) {
            console.error('Error loading user analytics:', error);
        }
    }

    async loadAuctionAnalytics() {
        try {
            const period = document.getElementById('auctionPeriod').value;
            const response = await fetch(`{{ route("admin.dashboard.monitoring.auction-analytics") }}?period=${period}`);
            const data = await response.json();

            if (data.success) {
                this.renderAuctionPerformanceChart(data.data.analytics.auction_performance);
                this.renderAuctionStatusChart(data.data.analytics.auction_status_distribution);
            }
        } catch (error) {
            console.error('Error loading auction analytics:', error);
        }
    }

    async loadSystemMetrics() {
        try {
            const response = await fetch('{{ route("admin.dashboard.monitoring.system-metrics") }}');
            const data = await response.json();

            if (data.success) {
                this.renderSystemMetrics(data.data.metrics);
            }
        } catch (error) {
            console.error('Error loading system metrics:', error);
        }
    }

    async loadActivityFeed() {
        try {
            const type = document.getElementById('activityType').value;
            const response = await fetch(`{{ route("admin.dashboard.monitoring.activity-feed") }}?type=${type}&limit=10`);
            const data = await response.json();

            if (data.success) {
                this.renderActivityFeed(data.data.activities);
            }
        } catch (error) {
            console.error('Error loading activity feed:', error);
        }
    }

    async loadAlerts() {
        try {
            const response = await fetch('{{ route("admin.dashboard.monitoring.alerts") }}');
            const data = await response.json();

            if (data.success) {
                this.renderAlerts(data.data.alerts);
            }
        } catch (error) {
            console.error('Error loading alerts:', error);
        }
    }

    renderUserGrowthChart(data) {
        const ctx = document.getElementById('userGrowthChart').getContext('2d');

        if (this.charts.userGrowth) {
            this.charts.userGrowth.destroy();
        }

        this.charts.userGrowth = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'New Users',
                    data: data.data,
                    borderColor: '#FE5000',
                    backgroundColor: 'rgba(254, 80, 0, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    renderUserStatusChart(data) {
        const ctx = document.getElementById('userStatusChart').getContext('2d');

        if (this.charts.userStatus) {
            this.charts.userStatus.destroy();
        }

        this.charts.userStatus = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels.map(label => label.replace('_', ' ').toUpperCase()),
                datasets: [{
                    data: data.data,
                    backgroundColor: Object.values(data.colors),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    renderAuctionPerformanceChart(data) {
        const ctx = document.getElementById('auctionPerformanceChart').getContext('2d');

        if (this.charts.auctionPerformance) {
            this.charts.auctionPerformance.destroy();
        }

        this.charts.auctionPerformance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: data.datasets.map(dataset => ({
                    label: dataset.label,
                    data: dataset.data,
                    borderColor: dataset.color,
                    backgroundColor: dataset.color + '20',
                    tension: 0.4
                }))
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    renderAuctionStatusChart(data) {
        const ctx = document.getElementById('auctionStatusChart').getContext('2d');

        if (this.charts.auctionStatus) {
            this.charts.auctionStatus.destroy();
        }

        this.charts.auctionStatus = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels.map(label => label.replace('_', ' ').toUpperCase()),
                datasets: [{
                    data: data.data,
                    backgroundColor: Object.values(data.colors),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    renderSystemMetrics(metrics) {
        const container = document.getElementById('systemMetrics');

        container.innerHTML = `
            <div class="text-center">
                <div class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">${metrics.performance.memory.usage_percentage}%</div>
                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Memory Usage</div>
                <div class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">${metrics.performance.memory.current} / ${metrics.performance.memory.limit}</div>
            </div>

            <div class="text-center">
                <div class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">${metrics.database.response_time_ms}ms</div>
                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Database Response</div>
                <div class="text-xs text-green-600 mt-1">${metrics.database.connection_status}</div>
            </div>

            <div class="text-center">
                <div class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">${metrics.storage.usage_percentage}%</div>
                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Storage Usage</div>
                <div class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">${metrics.storage.used_space} / ${metrics.storage.total_space}</div>
            </div>

            <div class="text-center">
                <div class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">${metrics.uptime.uptime_percentage}%</div>
                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">System Uptime</div>
                <div class="text-xs text-green-600 mt-1">${metrics.uptime.status}</div>
            </div>
        `;
    }

    renderActivityFeed(activities) {
        const container = document.getElementById('activityFeed');

        if (activities.length === 0) {
            container.innerHTML = '<p class="text-center text-[#706f6c] dark:text-[#A1A09A] py-4">No recent activities</p>';
            return;
        }

        container.innerHTML = activities.map(activity => `
            <div class="flex items-start space-x-3 p-3 bg-[#f8f8f7] dark:bg-[#1a1a19] rounded-lg">
                <div class="flex-shrink-0 w-8 h-8 bg-${activity.color}-100 dark:bg-${activity.color}-900/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-${activity.color}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">${activity.title}</p>
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">${activity.description}</p>
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">${new Date(activity.timestamp).toLocaleString()}</p>
                </div>
            </div>
        `).join('');
    }

    renderAlerts(alerts) {
        const container = document.getElementById('systemAlerts');
        const allAlerts = [
            ...alerts.critical.map(alert => ({...alert, severity: 'critical'})),
            ...alerts.warnings.map(alert => ({...alert, severity: 'warning'})),
            ...alerts.info.map(alert => ({...alert, severity: 'info'})),
            ...alerts.system.map(alert => ({...alert, severity: 'system'}))
        ];

        if (allAlerts.length === 0) {
            container.innerHTML = '<p class="text-center text-[#706f6c] dark:text-[#A1A09A] py-4">No active alerts</p>';
            return;
        }

        const getSeverityColor = (severity) => {
            switch (severity) {
                case 'critical': return 'red';
                case 'warning': return 'yellow';
                case 'info': return 'blue';
                default: return 'gray';
            }
        };

        container.innerHTML = allAlerts.map(alert => `
            <div class="flex items-start space-x-3 p-3 bg-[#f8f8f7] dark:bg-[#1a1a19] rounded-lg border-l-4 border-${getSeverityColor(alert.severity)}-500">
                <div class="flex-shrink-0 w-6 h-6 bg-${getSeverityColor(alert.severity)}-100 dark:bg-${getSeverityColor(alert.severity)}-900/20 rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3 text-${getSeverityColor(alert.severity)}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">${alert.title}</p>
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">${alert.message}</p>
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">${new Date(alert.timestamp).toLocaleString()}</p>
                </div>
            </div>
        `).join('');
    }
}

// Initialize dashboard when page loads
document.addEventListener('DOMContentLoaded', function() {
    window.dashboardMonitoring = new DashboardMonitoring();
});
</script>
@endsection
