@extends('layouts.admin')

@section('title', 'Account Collaterals - ' . $account->account_name)

@section('header-content')
    <div class="flex items-center space-x-2 mb-2">
        <a href="{{ route('admin.accounts.index') }}" class="text-sm text-brand hover:text-brand-hover transition-colors">
            ← Back to Accounts
        </a>
    </div>
    <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $account->account_name }}</h1>
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Account: {{ $account->account_number }} • {{ $collaterals->count() }} collateral items</p>
@endsection

@section('header-actions')
    <div class="text-right">
        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Account Status</div>
        @if($account->status === 'active')
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3"/>
                </svg>
                Active
            </span>
        @else
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3"/>
                </svg>
                {{ ucfirst($account->status) }}
            </span>
        @endif
    </div>
@endsection

@section('content')

                    <!-- Account Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <!-- Total Items -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Items</p>
                                    <p class="text-3xl font-bold text-brand">{{ $collaterals->count() }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Collateral items</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Active Items -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Active Items</p>
                                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $collaterals->where('status', 'active')->count() }}</p>
                                    <p class="text-sm text-green-600 dark:text-green-400">Currently held</p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Total Value -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Value</p>
                                    <p class="text-3xl font-bold text-brand">${{ number_format($collaterals->where('status', 'active')->sum('estimated_value'), 0) }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Estimated value</p>
                                </div>
                                <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Loan Amount -->
                        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Loan Amount</p>
                                    <p class="text-3xl font-bold text-[#706f6c] dark:text-[#A1A09A]">${{ number_format($collaterals->where('status', 'active')->sum('loan_amount'), 0) }}</p>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Outstanding loans</p>
                                </div>
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Collaterals List -->
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Collateral Items</h3>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Items held as collateral for this account</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <!-- Add Collateral Button -->
                                <button onclick="openAddCollateralModal()" class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-hover text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Collateral
                                </button>
                            </div>
                        </div>

                        @if($collaterals->isNotEmpty())
                            <div class="overflow-x-auto border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                                <table class="min-w-full divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                                    <thead class="bg-[#f8f8f7] dark:bg-[#1a1a19]">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                Item
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                Category
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                Value
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                Condition
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                Status
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                Dates
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-[#161615] divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                                        @foreach($collaterals as $collateral)
                                            <tr class="hover:bg-[#f8f8f7] dark:hover:bg-[#1a1a19] transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 bg-brand/10 rounded-lg flex items-center justify-center mr-4">
                                                            <span class="text-brand font-medium text-sm">
                                                                {{ strtoupper(substr($collateral->item_category, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                                {{ $collateral->item_name }}
                                                            </div>
                                                            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                                {{ $collateral->collateral_number }}
                                                            </div>
                                                            @if($collateral->brand)
                                                                <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                                    {{ $collateral->brand }}{{ $collateral->model ? ' ' . $collateral->model : '' }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($collateral->item_category === 'jewelry') bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-200
                                                        @elseif($collateral->item_category === 'art') bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200
                                                        @elseif($collateral->item_category === 'antiques') bg-amber-100 dark:bg-amber-900/20 text-amber-800 dark:text-amber-200
                                                        @elseif($collateral->item_category === 'electronics') bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200
                                                        @else bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 @endif">
                                                        {{ ucfirst($collateral->item_category) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                    <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                        ${{ number_format($collateral->estimated_value, 2) }}
                                                    </div>
                                                    <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                        Loan: ${{ number_format($collateral->loan_amount, 2) }}
                                                    </div>
                                                    @if($collateral->appraisal_value)
                                                        <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                            Appraisal: ${{ number_format($collateral->appraisal_value, 2) }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($collateral->condition === 'excellent') bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200
                                                        @elseif($collateral->condition === 'good') bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200
                                                        @elseif($collateral->condition === 'fair') bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200
                                                        @else bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 @endif">
                                                        {{ ucfirst($collateral->condition) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                    @if($collateral->status === 'active')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3"/>
                                                            </svg>
                                                            Active
                                                        </span>
                                                    @elseif($collateral->status === 'redeemed')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3"/>
                                                            </svg>
                                                            Redeemed
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3"/>
                                                            </svg>
                                                            {{ ucfirst($collateral->status) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap border-r border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                    <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                                        Received: {{ $collateral->received_date->format('M j, Y') }}
                                                    </div>
                                                    @if($collateral->due_date)
                                                        <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                            Due: {{ $collateral->due_date->format('M j, Y') }}
                                                        </div>
                                                    @endif
                                                    @if($collateral->redeemed_date)
                                                        <div class="text-sm text-green-600 dark:text-green-400">
                                                            Redeemed: {{ $collateral->redeemed_date->format('M j, Y') }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <div class="flex items-center space-x-2">
                                                        <!-- View Collateral -->
                                                        <button onclick="viewCollateral({{ $collateral->id }})"
                                                                class="px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/40 transition-colors">
                                                            View
                                                        </button>

                                                        <!-- Toggle Status -->
                                                        <form method="POST" action="{{ route('admin.collaterals.toggle-status', $collateral) }}" class="inline">
                                                            @csrf
                                                            @if($collateral->status === 'active')
                                                                <button type="submit"
                                                                        class="px-3 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-xs font-medium rounded-full hover:bg-green-200 dark:hover:bg-green-900/40 transition-colors"
                                                                        onclick="return confirm('Mark collateral {{ $collateral->collateral_number }} as redeemed?')">
                                                                    Redeem
                                                                </button>
                                                            @else
                                                                <button type="submit"
                                                                        class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-xs font-medium rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-900/40 transition-colors"
                                                                        onclick="return confirm('Reactivate collateral {{ $collateral->collateral_number }}?')">
                                                                    Reactivate
                                                                </button>
                                                            @endif
                                                        </form>

                                                        <!-- Delete Collateral -->
                                                        <form method="POST" action="{{ route('admin.collaterals.delete', $collateral) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-xs font-medium rounded-full hover:bg-red-200 dark:hover:bg-red-900/40 transition-colors"
                                                                    onclick="return confirm('Are you sure you want to delete collateral {{ $collateral->collateral_number }}? This action cannot be undone.')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No collateral items</h3>
                                <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">This account doesn't have any collateral items yet.</p>
                                <div class="mt-6">
                                    <button onclick="openAddCollateralModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand hover:bg-brand-hover">
                                        Add First Collateral
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
@endsection

@push('scripts')
<script>
    // Placeholder functions for modals and actions
    function openAddCollateralModal() {
        alert('Add Collateral Modal for Account {{ $account->account_number }} - To be implemented');
    }

    function viewCollateral(collateralId) {
        alert('View Collateral Details - Collateral ID: ' + collateralId);
    }
</script>
@endpush
