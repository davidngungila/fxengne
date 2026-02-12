@extends('layouts.app')

@section('title', 'User Management - FxEngne')
@section('page-title', 'User Management')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">User Management</h2>
            <p class="text-sm text-gray-600 mt-1">Manage all users and their roles</p>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select 
                                class="form-select text-sm role-select" 
                                data-user-id="{{ $user->id }}"
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}
                            >
                                <option value="trader" {{ $user->role === 'trader' ? 'selected' : '' }}>Trader</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->email_verified_at)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Unverified</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($user->id !== auth()->id())
                            <button 
                                class="text-red-600 hover:text-red-900 delete-user" 
                                data-user-id="{{ $user->id }}"
                                data-user-name="{{ $user->name }}"
                            >
                                Delete
                            </button>
                            @else
                            <span class="text-gray-400">Current User</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No users found</td>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const CSRF_TOKEN = '{{ csrf_token() }}';

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
                    // Success
                    alert('User role updated successfully');
                } else {
                    // Revert selection
                    this.value = originalRole;
                    alert(data.message || 'Failed to update user role');
                }
            } catch (error) {
                this.value = originalRole;
                alert('Error updating user role');
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
                    alert('User deleted successfully');
                    location.reload();
                } else {
                    alert(data.message || 'Failed to delete user');
                }
            } catch (error) {
                alert('Error deleting user');
            }
        });
    });
});
</script>
@endpush
@endsection

