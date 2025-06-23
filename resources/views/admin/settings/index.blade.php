@extends('layouts.admin')

@section('title', 'System Settings')

@section('header-content')
    <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">System Settings</h1>
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Manage system configuration and security settings</p>
@endsection

@section('header-actions')
    <div class="flex items-center space-x-3">
        <button onclick="resetToDefaults()" class="px-4 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg hover:border-brand/30 transition-colors">
            Reset to Defaults
        </button>
        <button onclick="saveSettings()" class="px-4 py-2 text-sm font-medium text-white bg-brand hover:bg-brand/90 rounded-lg transition-colors">
            Save Changes
        </button>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Success/Error Messages -->
    <div id="messageContainer" class="hidden">
        <div id="successMessage" class="hidden p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span id="successText" class="text-green-800 dark:text-green-200"></span>
            </div>
        </div>
        <div id="errorMessage" class="hidden p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span id="errorText" class="text-red-800 dark:text-red-200"></span>
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Security Settings</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Configure authentication and security features</p>
            </div>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-brand mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span class="text-sm font-medium text-brand">Security</span>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Session and Security Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sessionLifetime" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Session Lifetime (minutes)
                    </label>
                    <input type="number" id="sessionLifetime" name="session_lifetime" 
                           value="{{ $settings['security']['session_lifetime'] }}" 
                           min="60" max="43200"
                           class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand/20 focus:border-brand transition-colors">
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">
                        Current: {{ floor($settings['security']['session_lifetime'] / 60) }} hours {{ $settings['security']['session_lifetime'] % 60 }} minutes
                    </p>
                </div>
            </div>

            <!-- Login Security -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="maxLoginAttempts" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Max Login Attempts
                    </label>
                    <input type="number" id="maxLoginAttempts" name="max_login_attempts" 
                           value="{{ $settings['security']['max_login_attempts'] }}" 
                           min="3" max="10"
                           class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand/20 focus:border-brand transition-colors">
                </div>

                <div>
                    <label for="lockoutDuration" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Lockout Duration (seconds)
                    </label>
                    <input type="number" id="lockoutDuration" name="lockout_duration" 
                           value="{{ $settings['security']['lockout_duration'] }}" 
                           min="60" max="3600"
                           class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand/20 focus:border-brand transition-colors">
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">
                        Current: {{ floor($settings['security']['lockout_duration'] / 60) }} minutes
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">System Information</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Current system configuration and status</p>
            </div>
            <button onclick="refreshSystemStatus()" class="px-3 py-2 text-sm font-medium text-brand hover:text-brand/80 border border-brand/20 hover:border-brand/40 rounded-lg transition-colors">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="systemStatus">
            <!-- System status will be loaded here -->
        </div>
    </div>

    <!-- Settings Metadata -->
    <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-6">
        <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Settings Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-[#706f6c] dark:text-[#A1A09A]">Last Updated:</span>
                <span class="text-[#1b1b18] dark:text-[#EDEDEC] ml-2">{{ \Carbon\Carbon::parse($settings['meta']['last_updated'])->format('M j, Y g:i A') }}</span>
            </div>
            <div>
                <span class="text-[#706f6c] dark:text-[#A1A09A]">Updated By:</span>
                <span class="text-[#1b1b18] dark:text-[#EDEDEC] ml-2">{{ $settings['meta']['updated_by'] }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-[#161615] rounded-lg p-6 flex items-center">
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-brand" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-[#1b1b18] dark:text-[#EDEDEC]">Processing...</span>
    </div>
</div>

@push('scripts')
<script>
// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Show/Hide Loading
function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

// Show Messages
function showMessage(type, message) {
    const container = document.getElementById('messageContainer');
    const successDiv = document.getElementById('successMessage');
    const errorDiv = document.getElementById('errorMessage');
    
    // Hide all messages first
    successDiv.classList.add('hidden');
    errorDiv.classList.add('hidden');
    
    if (type === 'success') {
        document.getElementById('successText').textContent = message;
        successDiv.classList.remove('hidden');
    } else {
        document.getElementById('errorText').textContent = message;
        errorDiv.classList.remove('hidden');
    }
    
    container.classList.remove('hidden');
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        container.classList.add('hidden');
    }, 5000);
}




// Save Settings
async function saveSettings() {
    showLoading();
    
    const formData = {
        session_lifetime: parseInt(document.getElementById('sessionLifetime').value),
        max_login_attempts: parseInt(document.getElementById('maxLoginAttempts').value),
        lockout_duration: parseInt(document.getElementById('lockoutDuration').value)
    };
    
    try {
        const response = await fetch('{{ route("admin.settings.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage('success', data.message);
            // Optionally reload page to reflect changes
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showMessage('error', data.message || 'Failed to save settings');
        }
    } catch (error) {
        showMessage('error', 'Network error occurred');
    } finally {
        hideLoading();
    }
}

// Reset to Defaults
async function resetToDefaults() {
    if (!confirm('Are you sure you want to reset all settings to default values? This action cannot be undone.')) {
        return;
    }
    
    showLoading();
    
    try {
        const response = await fetch('{{ route("admin.settings.reset") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage('success', data.message);
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showMessage('error', data.message || 'Failed to reset settings');
        }
    } catch (error) {
        showMessage('error', 'Network error occurred');
    } finally {
        hideLoading();
    }
}

// Refresh System Status
async function refreshSystemStatus() {
    try {
        const response = await fetch('{{ route("admin.settings.system-status") }}');
        const data = await response.json();
        
        if (data.success) {
            renderSystemStatus(data.data);
        }
    } catch (error) {
        console.error('Failed to refresh system status:', error);
    }
}

// Render System Status
function renderSystemStatus(status) {
    const container = document.getElementById('systemStatus');
    
    const statusItems = [
        { label: 'PHP Version', value: status.php_version, type: 'info' },
        { label: 'Laravel Version', value: status.laravel_version, type: 'info' },
        { label: 'Environment', value: status.environment, type: status.environment === 'production' ? 'success' : 'warning' },
        { label: 'Debug Mode', value: status.debug_mode ? 'Enabled' : 'Disabled', type: status.debug_mode ? 'warning' : 'success' },
        { label: 'Cache Driver', value: status.cache_driver, type: 'info' },
        { label: 'Session Driver', value: status.session_driver, type: 'info' },
        { label: 'Mail Driver', value: status.mail_driver, type: 'info' },
        { label: 'Database', value: status.database_connection, type: 'info' },
        { label: 'Storage Writable', value: status.storage_writable ? 'Yes' : 'No', type: status.storage_writable ? 'success' : 'error' }
    ];
    
    container.innerHTML = statusItems.map(item => `
        <div class="p-3 bg-[#f8f8f7] dark:bg-[#1a1a19] rounded-lg">
            <div class="text-xs text-[#706f6c] dark:text-[#A1A09A] mb-1">${item.label}</div>
            <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">${item.value}</div>
        </div>
    `).join('');
}

// Load system status on page load
document.addEventListener('DOMContentLoaded', function() {
    refreshSystemStatus();
});
</script>
@endpush
@endsection 