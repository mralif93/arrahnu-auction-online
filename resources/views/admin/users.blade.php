@extends('layouts.admin')

@section('title', 'User Management')

@section('header-content')
    <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">User Management</h1>
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

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Users</p>
                                <p class="text-3xl font-bold text-brand">{{ $users->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Admin Users</p>
                                <p class="text-3xl font-bold text-brand">{{ $users->where('is_admin', true)->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Regular Users</p>
                                <p class="text-3xl font-bold text-brand">{{ $users->where('is_admin', false)->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">All Users</h3>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Manage user accounts and permissions</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Add User Button -->
                            <button onclick="openAddUserModal()" class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-hover text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add User
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-[#FDFDFC] dark:bg-[#0a0a0a]">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                        User
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                        Role & Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                        Phone & Verification
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                        Created & Approved
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                                @foreach($users as $user)
                                    <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-brand/10 rounded-full flex items-center justify-center">
                                                    <span class="text-brand font-medium">
                                                        {{ strtoupper(substr($user->full_name ?? $user->username, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                                        {{ $user->full_name ?? $user->username }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                {{ $user->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="space-y-1">
                                                <div class="flex items-center space-x-2">
                                                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full">
                                                        {{ ucfirst($user->role) }}
                                                    </span>
                                                    @if($user->isAdmin())
                                                        <span class="px-2 py-1 bg-brand text-white text-xs font-medium rounded-full">
                                                            Admin
                                                        </span>
                                                    @endif
                                                </div>
                                                <div>
                                                    @if($user->status === 'active')
                                                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-xs font-medium rounded-full">
                                                            {{ ucfirst($user->status) }}
                                                        </span>
                                                    @elseif($user->status === 'pending_approval')
                                                        <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-xs font-medium rounded-full">
                                                            Pending
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-900/20 text-gray-800 dark:text-gray-200 text-xs font-medium rounded-full">
                                                            {{ ucfirst($user->status) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                                {{ $user->phone_number ?? 'No phone' }}
                                            </div>
                                            <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                @if($user->phone_number)
                                                    @if($user->is_phone_verified)
                                                        <span class="text-green-600">✓ Verified</span>
                                                    @else
                                                        <span class="text-yellow-600">⚠ Unverified</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                            <div class="space-y-1">
                                                <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                                    Created: {{ $user->created_at->format('M d, Y') }}
                                                </div>
                                                @if($user->creator)
                                                    <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                        By: {{ $user->creator->full_name }}
                                                    </div>
                                                @endif
                                                @if($user->approvedBy)
                                                    <div class="text-xs text-green-600">
                                                        Approved by: {{ $user->approvedBy->full_name }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex items-center space-x-2">
                                                <!-- Edit User -->
                                                <button type="button"
                                                        class="px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/40 transition-colors"
                                                        onclick="openEditModal({{ $user->id }}, '{{ $user->full_name ?? $user->username }}', '{{ $user->email }}', {{ $user->isAdmin() ? 'true' : 'false' }})">
                                                    Edit
                                                </button>

                                                <!-- Toggle Admin Status -->
                                                @if($user->id !== Auth::id())
                                                    <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="inline">
                                                        @csrf
                                                        @if($user->isAdmin())
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-xs font-medium rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-900/40 transition-colors"
                                                                    onclick="return confirm('Remove admin privileges from {{ $user->full_name ?? $user->username }}?')">
                                                                Remove Admin
                                                            </button>
                                                        @else
                                                            <button type="submit"
                                                                    class="px-3 py-1 bg-brand/10 text-brand text-xs font-medium rounded-full hover:bg-brand/20 transition-colors"
                                                                    onclick="return confirm('Grant admin privileges to {{ $user->full_name ?? $user->username }}?')">
                                                                Make Admin
                                                            </button>
                                                        @endif
                                                    </form>
                                                @else
                                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-xs font-medium rounded-full">
                                                        Current User
                                                    </span>
                                                @endif

                                                <!-- Delete User -->
                                                @if($user->id !== Auth::id())
                                                    <form method="POST" action="{{ route('admin.users.delete', $user) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="px-3 py-1 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-xs font-medium rounded-full hover:bg-red-200 dark:hover:bg-red-900/40 transition-colors"
                                                                onclick="return confirm('Are you sure you want to delete {{ $user->full_name ?? $user->username }}? This action cannot be undone.')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Edit User</h3>
            </div>

            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')

                <div class="px-6 py-4 space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="edit_full_name" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            Full Name
                        </label>
                        <input type="text"
                               id="edit_full_name"
                               name="full_name"
                               class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent"
                               required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="edit_email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            Email
                        </label>
                        <input type="email"
                               id="edit_email"
                               name="email"
                               class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent"
                               required>
                    </div>

                    <!-- Admin Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   id="edit_is_admin"
                                   name="is_admin"
                                   value="1"
                                   class="w-4 h-4 text-brand bg-[#FDFDFC] dark:bg-[#161615] border-[#e3e3e0] dark:border-[#3E3E3A] rounded focus:ring-brand focus:ring-2">
                            <span class="ml-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC]">Admin privileges</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A] flex justify-end space-x-3">
                    <button type="button"
                            onclick="closeEditModal()"
                            class="px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm hover:border-[#1915014a] dark:hover:border-[#62605b] transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-brand bg-brand-hover text-white rounded-sm transition-colors">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openAddUserModal() {
        // Placeholder for add user modal
        alert('Add User Modal - To be implemented');
    }

    function openEditModal(userId, userName, userEmail, isAdmin) {
        // Set form action
        document.getElementById('editUserForm').action = `/admin/users/${userId}`;

        // Populate form fields
        document.getElementById('edit_full_name').value = userName;
        document.getElementById('edit_email').value = userEmail;
        document.getElementById('edit_is_admin').checked = isAdmin;

        // Show modal
        document.getElementById('editUserModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('editUserModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
        }
    });
</script>
@endpush
