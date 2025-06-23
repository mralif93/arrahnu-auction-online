@extends('layouts.admin')

@section('title', 'Pending User Approvals')

@section('header-content')
    <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Pending User Approvals</h1>
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Review and approve user registrations</p>
@endsection

@section('header-actions')
    <div class="flex items-center space-x-4">
        <div class="text-right">
            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ now()->format('l, F j, Y') }}</div>
            <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ now()->format('g:i A') }}</div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Approval Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Pending Approvals -->
        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Pending Approval</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $statistics['pending_approval'] ?? 0 }}</p>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400">Awaiting review</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Verification -->
        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Email Verification</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $statistics['pending_verification'] ?? 0 }}</p>
                    <p class="text-sm text-blue-600 dark:text-blue-400">Need verification</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Approved Today -->
        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Approved Today</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $statistics['approved_today'] ?? 0 }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400">Today's approvals</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Approved -->
        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Approved</p>
                    <p class="text-3xl font-bold text-[#706f6c] dark:text-[#A1A09A]">{{ $statistics['total_approved'] ?? 0 }}</p>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">All time</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Users List -->
    <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
        <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Users Pending Approval</h3>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Review and approve user registrations</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Bulk Actions -->
                    <form method="POST" action="{{ route('admin.users.bulk-approve') }}" class="inline" id="bulk-approve-form">
                        @csrf
                        <input type="hidden" name="user_ids" id="bulk-user-ids">
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-50"
                                id="bulk-approve-btn" disabled
                                onclick="return confirm('Approve selected users?')">
                            Bulk Approve
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        View All Users
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#f8f8f7] dark:bg-[#1a1a19]">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-brand focus:ring-brand">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                            User Details
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                            Registration Info
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                            Verification Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-[#161615] divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                    @forelse($pendingUsers as $user)
                        <tr class="hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox rounded border-gray-300 text-brand focus:ring-brand">
                            </td>
                            
                            <!-- User Details -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-brand/10 rounded-lg flex items-center justify-center mr-4">
                                        <span class="text-brand font-medium text-sm">
                                            {{ strtoupper(substr($user->full_name ?? $user->username, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                            {{ $user->full_name ?? $user->username }}
                                        </div>
                                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                            {{ $user->email }}
                                        </div>
                                        <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                            @{{ $user->username }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Registration Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                    {{ $user->created_at->format('M d, Y g:i A') }}
                                </div>
                                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    Source: {{ ucfirst($user->registration_source ?? 'web') }}
                                </div>
                                <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </td>

                            <!-- Verification Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    @if($user->isEmailVerified())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Email Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Not Verified
                                        </span>
                                    @endif
                                    
                                    @if($user->phone_number)
                                        <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                            📱 {{ $user->phone_number }}
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-2">
                                    <!-- View Details -->
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/40 transition-colors">
                                        View
                                    </a>

                                    @if($user->isEmailVerified())
                                        <!-- Approve -->
                                        <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-xs font-medium rounded-full hover:bg-green-200 dark:hover:bg-green-900/40 transition-colors"
                                                    onclick="return confirm('Approve user {{ $user->full_name }}?')">
                                                Approve
                                            </button>
                                        </form>

                                        <!-- Reject -->
                                        <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-xs font-medium rounded-full hover:bg-red-200 dark:hover:bg-red-900/40 transition-colors"
                                                    onclick="return confirm('Reject user {{ $user->full_name }}? Please provide a reason.')">
                                                Reject
                                            </button>
                                        </form>
                                    @else
                                        <!-- Send Verification -->
                                        <form method="POST" action="{{ route('admin.users.send-verification-email', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-xs font-medium rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-900/40 transition-colors">
                                                Send Verification
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No pending approvals</h3>
                                <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">All user registrations have been processed.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pendingUsers->hasPages())
            <div class="px-6 py-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                {{ $pendingUsers->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    // Bulk selection functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
        const bulkBtn = document.getElementById('bulk-approve-btn');
        const userIdsInput = document.getElementById('bulk-user-ids');
        
        if (selectedCheckboxes.length > 0) {
            bulkBtn.disabled = false;
            const userIds = Array.from(selectedCheckboxes).map(cb => cb.value);
            userIdsInput.value = userIds.join(',');
        } else {
            bulkBtn.disabled = true;
            userIdsInput.value = '';
        }
    }
</script>
@endpush 