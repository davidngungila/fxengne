@extends('layouts.app')

@section('title', 'User Management - FXEngine')
@section('page-title', 'User Management')

@section('content')
<div class="space-y-6">
    <!-- Header with Statistics -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">User Management</h2>
            <p class="text-sm text-gray-600 mt-1">Comprehensive user administration and analytics</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.export', request()->query()) }}" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </a>
        </div>
    </div>

    <!-- Statistics Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_users'] }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Traders</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['total_traders'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['active_traders'] }} active</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Verified Users</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1">{{ $stats['verified_users'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format(($stats['verified_users'] / max($stats['total_users'], 1)) * 100, 1) }}% of total</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">2FA Enabled</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $stats['2fa_enabled'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format(($stats['2fa_enabled'] / max($stats['total_users'], 1)) * 100, 1) }}% adoption</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="card">
        <form method="GET" action="{{ route('admin.users') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="form-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or Email" class="form-input">
                </div>
                <div>
                    <label class="form-label">Role</label>
                    <select name="role" class="form-input">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="trader" {{ request('role') === 'trader' ? 'selected' : '' }}>Trader</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="">All Status</option>
                        <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Unverified</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">2FA Status</label>
                    <select name="2fa" class="form-input">
                        <option value="">All</option>
                        <option value="enabled" {{ request('2fa') === 'enabled' ? 'selected' : '' }}>Enabled</option>
                        <option value="disabled" {{ request('2fa') === 'disabled' ? 'selected' : '' }}>Disabled</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Clear Filters</a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" class="form-checkbox">
                        </th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>2FA</th>
                        <th>Trading Stats</th>
                        <th>ML Models</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td>
                            <input type="checkbox" class="form-checkbox user-checkbox" value="{{ $user->id }}" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        </td>
                        <td>
                            <div class="flex items-center space-x-3">
                                @if($user->profile_image)
                                <img src="{{ $user->getProfileImageUrl() }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ $user->getAvatarInitial() }}</span>
                                </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        </td>
                        <td>
                            <select 
                                class="form-select text-sm role-select" 
                                data-user-id="{{ $user->id }}"
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}
                            >
                                <option value="trader" {{ $user->role === 'trader' ? 'selected' : '' }}>Trader</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </td>
                        <td>
                            @if($user->email_verified_at)
                                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Verified</span>
                            @else
                                <span class="px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Unverified</span>
                            @endif
                        </td>
                        <td>
                            @if($user->hasTwoFactorEnabled())
                                <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">Enabled</span>
                            @else
                                <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">Disabled</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($userStats[$user->id]))
                            <div class="text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Trades:</span>
                                    <span class="font-semibold">{{ $userStats[$user->id]['total_trades'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Profit:</span>
                                    <span class="font-semibold {{ $userStats[$user->id]['total_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        ${{ number_format($userStats[$user->id]['total_profit'], 2) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Win Rate:</span>
                                    <span class="font-semibold">{{ number_format($userStats[$user->id]['win_rate'], 1) }}%</span>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-400 text-xs">No data</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-sm">
                                <span class="font-semibold text-gray-900">{{ $user->ml_models_count ?? 0 }}</span>
                                <span class="text-gray-500">models</span>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm text-gray-600">
                                {{ $user->created_at->format('M d, Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <button 
                                    onclick="showUserDetails({{ $user->id }})"
                                    class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                                    title="View Details"
                                >
                                    View
                                </button>
                                @if($user->id !== auth()->id())
                                <button 
                                    class="text-red-600 hover:text-red-700 text-sm font-medium delete-user" 
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->name }}"
                                    title="Delete User"
                                >
                                    Delete
                                </button>
                                @else
                                <span class="text-gray-400 text-sm">Current</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-8 text-gray-500">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<!-- User Details Modal -->
<div id="userDetailsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 sticky top-0 bg-white">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900" id="modalUserName">User Details</h3>
                <button id="closeUserModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="px-6 py-4" id="userDetailsContent">
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                <p class="text-gray-600 mt-2">Loading user details...</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const CSRF_TOKEN = '{{ csrf_token() }}';

    // Select all checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.user-checkbox:not(:disabled)').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Handle role change
    document.querySelectorAll('.role-select').forEach(select => {
        select.addEventListener('change', async function() {
            const userId = this.dataset.userId;
            const newRole = this.value;
            const originalRole = this.options[this.selectedIndex === 0 ? 1 : 0].value;

            try {
                const response = await fetch(`/admin/users/${userId}/role`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ role: newRole })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showNotification('User role updated successfully', 'success');
                } else {
                    this.value = originalRole;
                    showNotification(data.message || 'Failed to update user role', 'error');
                }
            } catch (error) {
                this.value = originalRole;
                showNotification('Error updating user role', 'error');
            }
        });
    });

    // Handle user deletion
    document.querySelectorAll('.delete-user').forEach(button => {
        button.addEventListener('click', async function() {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;

            if (!confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) {
                return;
            }

            try {
                const response = await fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showNotification('User deleted successfully', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message || 'Failed to delete user', 'error');
                }
            } catch (error) {
                showNotification('Error deleting user', 'error');
            }
        });
    });

    // Show user details
    window.showUserDetails = async function(userId) {
        const modal = document.getElementById('userDetailsModal');
        const content = document.getElementById('userDetailsContent');
        const userName = document.getElementById('modalUserName');
        
        modal.classList.remove('hidden');
        content.innerHTML = `
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                <p class="text-gray-600 mt-2">Loading user details...</p>
            </div>
        `;

        try {
            const response = await fetch(`/admin/users/${userId}/details`);
            const data = await response.json();

            if (response.ok && data.user) {
                userName.textContent = data.user.name + ' - Details';
                content.innerHTML = renderUserDetails(data);
            } else {
                content.innerHTML = '<p class="text-red-600">Failed to load user details</p>';
            }
        } catch (error) {
            content.innerHTML = '<p class="text-red-600">Error loading user details</p>';
        }
    };

    // Render user details
    function renderUserDetails(data) {
        const user = data.user;
        const trading = data.trading_stats;
        const ml = data.ml_models;
        
        return `
            <div class="space-y-6">
                <!-- User Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Name</label>
                            <p class="text-lg font-semibold text-gray-900">${user.name}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Email</label>
                            <p class="text-lg text-gray-900">${user.email}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Role</label>
                            <p class="text-lg">
                                <span class="px-3 py-1 rounded-full text-sm font-medium ${user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'}">
                                    ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Email Verified</label>
                            <p class="text-lg">
                                ${user.email_verified_at 
                                    ? '<span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Verified</span>' 
                                    : '<span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">Unverified</span>'}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">2FA Status</label>
                            <p class="text-lg">
                                ${user.two_factor_secret && user.two_factor_confirmed_at
                                    ? '<span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">Enabled</span>'
                                    : '<span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">Disabled</span>'}
                            </p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Member Since</label>
                            <p class="text-lg text-gray-900">${new Date(user.created_at).toLocaleDateString()}</p>
                            <p class="text-sm text-gray-500">${new Date(user.created_at).toLocaleString()}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Last Updated</label>
                            <p class="text-lg text-gray-900">${new Date(user.updated_at).toLocaleDateString()}</p>
                            <p class="text-sm text-gray-500">${new Date(user.updated_at).toLocaleString()}</p>
                        </div>
                    </div>
                </div>

                <!-- Trading Statistics -->
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Trading Statistics</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Total Trades</p>
                            <p class="text-2xl font-bold text-blue-600">${trading.total_trades}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Open Trades</p>
                            <p class="text-2xl font-bold text-green-600">${trading.open_trades}</p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Closed Trades</p>
                            <p class="text-2xl font-bold text-purple-600">${trading.closed_trades}</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Win Rate</p>
                            <p class="text-2xl font-bold text-yellow-600">${trading.win_rate.toFixed(1)}%</p>
                        </div>
                        <div class="bg-indigo-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Total Profit</p>
                            <p class="text-2xl font-bold ${trading.total_profit >= 0 ? 'text-green-600' : 'text-red-600'}">
                                $${trading.total_profit.toFixed(2)}
                            </p>
                        </div>
                        <div class="bg-pink-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Unrealized P/L</p>
                            <p class="text-2xl font-bold ${trading.unrealized_pl >= 0 ? 'text-green-600' : 'text-red-600'}">
                                $${trading.unrealized_pl.toFixed(2)}
                            </p>
                        </div>
                        <div class="bg-teal-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Average Profit</p>
                            <p class="text-2xl font-bold text-teal-600">$${trading.average_profit.toFixed(2)}</p>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Largest Win</p>
                            <p class="text-2xl font-bold text-green-600">$${trading.largest_win.toFixed(2)}</p>
                        </div>
                    </div>
                </div>

                <!-- ML Models -->
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">ML Models</h4>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Total Models</p>
                            <p class="text-2xl font-bold text-gray-900">${ml.total}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Active Models</p>
                            <p class="text-2xl font-bold text-green-600">${ml.active}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Trained Models</p>
                            <p class="text-2xl font-bold text-blue-600">${ml.trained}</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Trades -->
                ${data.recent_trades && data.recent_trades.length > 0 ? `
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Recent Trades</h4>
                    <div class="overflow-x-auto">
                        <table class="table text-sm">
                            <thead>
                                <tr>
                                    <th>Instrument</th>
                                    <th>Type</th>
                                    <th>State</th>
                                    <th>Entry Price</th>
                                    <th>P/L</th>
                                    <th>Opened</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.recent_trades.map(trade => `
                                    <tr>
                                        <td>${trade.instrument}</td>
                                        <td><span class="px-2 py-1 rounded text-xs ${trade.type === 'BUY' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${trade.type}</span></td>
                                        <td><span class="px-2 py-1 rounded text-xs ${trade.state === 'OPEN' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}">${trade.state}</span></td>
                                        <td>$${parseFloat(trade.entry_price).toFixed(2)}</td>
                                        <td class="${trade.state === 'OPEN' ? (parseFloat(trade.unrealized_pl) >= 0 ? 'text-green-600' : 'text-red-600') : (parseFloat(trade.realized_pl) >= 0 ? 'text-green-600' : 'text-red-600')}">
                                            $${trade.state === 'OPEN' ? parseFloat(trade.unrealized_pl).toFixed(2) : parseFloat(trade.realized_pl).toFixed(2)}
                                        </td>
                                        <td>${new Date(trade.opened_at).toLocaleString()}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
                ` : ''}
            </div>
        `;
    }

    // Close modal
    document.getElementById('closeUserModal')?.addEventListener('click', function() {
        document.getElementById('userDetailsModal').classList.add('hidden');
    });

    // Close modal on outside click
    document.getElementById('userDetailsModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Notification function
    function showNotification(message, type = 'success') {
        // Simple notification - can be enhanced with a toast library
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});
</script>
@endpush
@endsection
