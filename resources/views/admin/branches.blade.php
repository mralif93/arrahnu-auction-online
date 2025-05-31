@extends('layouts.admin')

@section('title', 'Branch Management')

@section('header-content')
    <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Branch Management</h1>
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Welcome back, {{ Auth::user()->full_name ?? 'Administrator' }}</p>
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

                    <!-- Branch Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Total Branches -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Branches</p>
                                    <p class="text-3xl font-bold text-brand">{{ $totalBranches }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Locations worldwide</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Active Branches -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Active Branches</p>
                                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $activeBranches }}</p>
                                    <p class="text-sm text-green-600 dark:text-green-400">Currently operational</p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Inactive Branches -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Inactive Branches</p>
                                    <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $totalBranches - $activeBranches }}</p>
                                    <p class="text-sm text-red-600 dark:text-red-400">Under maintenance</p>
                                </div>
                                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Branches List -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">All Branches</h3>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Manage branch locations and assignments</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <!-- Add Branch Button -->
                                <a href="{{ route('admin.branches.create') }}" class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-hover text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Branch
                                </a>
                            </div>
                        </div>

                        <div class="overflow-x-auto border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                            <table class="min-w-full divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                                <thead class="bg-[#f8f8f7] dark:bg-[#1a1a19]">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Branch
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Address
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Contact
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                            Created & Approved
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-[#161615] divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                                    @foreach($branches as $branch)
                                        <tr class="hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-brand/10 rounded-lg flex items-center justify-center mr-4">
                                                        <span class="text-brand font-medium text-sm">
                                                            {{ strtoupper(substr($branch->name, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                            {{ $branch->name }}
                                                        </div>
                                                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                            Branch ID: {{ Str::limit($branch->id, 8) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                                    {{ $branch->address }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                @if($branch->status === 'active')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3"/>
                                                        </svg>
                                                        {{ ucfirst($branch->status) }}
                                                    </span>
                                                @elseif($branch->status === 'pending_approval')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3"/>
                                                        </svg>
                                                        Pending
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-900/20 text-gray-800 dark:text-gray-200">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3"/>
                                                        </svg>
                                                        {{ ucfirst($branch->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                                    {{ $branch->phone_number ?? 'No phone' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <div class="space-y-1">
                                                    <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                                        {{ $branch->created_at->format('M d, Y') }}
                                                    </div>
                                                    @if($branch->creator)
                                                        <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                            By: {{ $branch->creator->full_name }}
                                                        </div>
                                                    @endif
                                                    @if($branch->approvedBy)
                                                        <div class="text-xs text-green-600">
                                                            Approved by: {{ $branch->approvedBy->full_name }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex items-center space-x-2">
                                                    <!-- View Details -->
                                                    <a href="{{ route('admin.branches.show', $branch) }}"
                                                       class="px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/40 transition-colors">
                                                        View
                                                    </a>

                                                    <!-- Edit Branch -->
                                                    @if($branch->status !== 'active' && (auth()->user()->canMake() || $branch->created_by_user_id === auth()->id()))
                                                        <a href="{{ route('admin.branches.edit', $branch) }}"
                                                           class="px-3 py-1 bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-200 text-xs font-medium rounded-full hover:bg-purple-200 dark:hover:bg-purple-900/40 transition-colors">
                                                            Edit
                                                        </a>
                                                    @endif

                                                    <!-- Approval Actions -->
                                                    @if($branch->status === 'pending_approval' && auth()->user()->canApprove($branch))
                                                        <form method="POST" action="{{ route('admin.branches.approve', $branch) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-xs font-medium rounded-full hover:bg-green-200 dark:hover:bg-green-900/40 transition-colors"
                                                                    onclick="return confirm('Approve {{ $branch->name }}?')">
                                                                Approve
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.branches.reject', $branch) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-xs font-medium rounded-full hover:bg-red-200 dark:hover:bg-red-900/40 transition-colors"
                                                                    onclick="return confirm('Reject {{ $branch->name }}?')">
                                                                Reject
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- Submit for Approval -->
                                                    @if($branch->status === 'draft' && ($branch->created_by_user_id === auth()->id() || auth()->user()->isAdmin()))
                                                        <form method="POST" action="{{ route('admin.branches.submit-for-approval', $branch) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-xs font-medium rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-900/40 transition-colors"
                                                                    onclick="return confirm('Submit {{ $branch->name }} for approval?')">
                                                                Submit
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- Toggle Status (for active/inactive) -->
                                                    @if(in_array($branch->status, ['active', 'inactive']) && auth()->user()->isAdmin())
                                                        <form method="POST" action="{{ route('admin.branches.toggle-status', $branch) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-3 py-1 {{ $branch->status === 'active' ? 'bg-orange-100 dark:bg-orange-900/20 text-orange-800 dark:text-orange-200 hover:bg-orange-200 dark:hover:bg-orange-900/40' : 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-900/40' }} text-xs font-medium rounded-full transition-colors"
                                                                    onclick="return confirm('{{ $branch->status === 'active' ? 'Deactivate' : 'Activate' }} {{ $branch->name }}?')">
                                                                {{ $branch->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- Delete Branch -->
                                                    @if(auth()->user()->canMake() || $branch->created_by_user_id === auth()->id())
                                                        @if($branch->status !== 'active' || $branch->accounts()->count() === 0)
                                                            <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="px-3 py-1 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-xs font-medium rounded-full hover:bg-red-200 dark:hover:bg-red-900/40 transition-colors"
                                                                        onclick="return confirm('Are you sure you want to delete {{ $branch->name }}? This action cannot be undone.')">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($branches->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No branches</h3>
                                <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">Get started by creating a new branch location.</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.branches.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand hover:bg-brand-hover">
                                        Add Branch
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
@endsection

@push('scripts')
<script>
    // Enhanced branch management functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Add confirmation for bulk actions if implemented
        const bulkActionForms = document.querySelectorAll('form[data-bulk-action]');
        bulkActionForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const action = this.dataset.bulkAction;
                const selectedItems = document.querySelectorAll('input[name="branch_ids[]"]:checked').length;

                if (selectedItems === 0) {
                    e.preventDefault();
                    alert('Please select at least one branch.');
                    return false;
                }

                const confirmMessage = `Are you sure you want to ${action} ${selectedItems} branch(es)?`;
                if (!confirm(confirmMessage)) {
                    e.preventDefault();
                    return false;
                }
            });
        });

        // Add loading states to form submissions
        const actionForms = document.querySelectorAll('form[method="POST"]');
        actionForms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitButton = this.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-3 w-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    `;
                }
            });
        });
    });
</script>
@endpush
