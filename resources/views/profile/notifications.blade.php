@extends('layouts.app')

@section('title', 'Notification Settings - FXEngine')
@section('page-title', 'Notification Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Notification Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Configure how you receive notifications based on real trading events</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Notification Channels -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Notification Channels</h3>
            <div class="space-y-4">
                <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <div>
                        <p class="font-medium text-gray-900">In-App Notifications</p>
                        <p class="text-sm text-gray-600">Show notifications in the application</p>
                    </div>
                    <input type="checkbox" class="form-checkbox" checked>
                </label>
                <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <div>
                        <p class="font-medium text-gray-900">Email Notifications</p>
                        <p class="text-sm text-gray-600">Receive notifications via email</p>
                    </div>
                    <input type="checkbox" id="emailNotifications" class="form-checkbox">
                </label>
                <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <div>
                        <p class="font-medium text-gray-900">Telegram Alerts</p>
                        <p class="text-sm text-gray-600">Receive alerts via Telegram</p>
                    </div>
                    <input type="checkbox" id="telegramNotifications" class="form-checkbox">
                </label>
            </div>
        </div>

        <!-- Notification Types -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Notification Types</h3>
            <div class="space-y-3">
                <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <div>
                        <p class="font-medium text-gray-900 text-sm">Trade Executed</p>
                        <p class="text-xs text-gray-600">When a new trade is opened</p>
                    </div>
                    <input type="checkbox" class="form-checkbox" checked>
                </label>
                <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <div>
                        <p class="font-medium text-gray-900 text-sm">Trade Closed</p>
                        <p class="text-xs text-gray-600">When a trade is closed (profit/loss)</p>
                    </div>
                    <input type="checkbox" class="form-checkbox" checked>
                </label>
                <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <div>
                        <p class="font-medium text-gray-900 text-sm">New Signals</p>
                        <p class="text-xs text-gray-600">When trading signals are generated</p>
                    </div>
                    <input type="checkbox" class="form-checkbox" checked>
                </label>
                <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <div>
                        <p class="font-medium text-gray-900 text-sm">Risk Alerts</p>
                        <p class="text-xs text-gray-600">Drawdown, margin, and limit warnings</p>
                    </div>
                    <input type="checkbox" class="form-checkbox" checked>
                </label>
                <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <div>
                        <p class="font-medium text-gray-900 text-sm">Daily Limits</p>
                        <p class="text-xs text-gray-600">Daily profit/loss limit alerts</p>
                    </div>
                    <input type="checkbox" class="form-checkbox" checked>
                </label>
            </div>
        </div>
    </div>

    <!-- Recent Notifications -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent Notifications</h3>
            <button id="refreshNotifications" class="btn btn-secondary text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
        <div id="recentNotifications" class="space-y-2">
            <div class="text-center py-8 text-gray-500">
                <p>Loading notifications...</p>
            </div>
        </div>
    </div>

    <!-- Notification Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <p class="text-sm text-gray-600">Total Notifications</p>
            <p class="text-2xl font-bold text-gray-900 mt-1" id="totalNotifications">0</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Unread</p>
            <p class="text-2xl font-bold text-blue-600 mt-1" id="unreadNotifications">0</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Today</p>
            <p class="text-2xl font-bold text-gray-900 mt-1" id="todayNotifications">0</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">This Week</p>
            <p class="text-2xl font-bold text-gray-900 mt-1" id="weekNotifications">0</p>
        </div>
    </div>

    <div class="flex space-x-3">
        <button class="btn btn-primary">Save Settings</button>
        <button id="markAllReadBtn" class="btn btn-secondary">Mark All as Read</button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';

    async function loadNotifications() {
        try {
            const response = await fetch(`${API_BASE_URL}/notifications?limit=20`);
            const result = await response.json();

            if (result.success) {
                document.getElementById('totalNotifications').textContent = result.data?.length || 0;
                document.getElementById('unreadNotifications').textContent = result.unread_count || 0;
                
                const today = result.data?.filter(n => {
                    const date = new Date(n.created_at);
                    return date.toDateString() === new Date().toDateString();
                }).length || 0;
                document.getElementById('todayNotifications').textContent = today;

                const weekAgo = new Date();
                weekAgo.setDate(weekAgo.getDate() - 7);
                const week = result.data?.filter(n => {
                    return new Date(n.created_at) >= weekAgo;
                }).length || 0;
                document.getElementById('weekNotifications').textContent = week;

                renderNotifications(result.data || []);
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }

    function renderNotifications(notifications) {
        const container = document.getElementById('recentNotifications');
        
        if (notifications.length === 0) {
            container.innerHTML = '<div class="text-center py-8 text-gray-500"><p>No notifications</p></div>';
            return;
        }

        container.innerHTML = notifications.slice(0, 10).map(notif => {
            const severityColors = {
                'success': 'bg-green-100 text-green-800 border-green-200',
                'danger': 'bg-red-100 text-red-800 border-red-200',
                'warning': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'info': 'bg-blue-100 text-blue-800 border-blue-200'
            };
            const color = severityColors[notif.severity] || 'bg-gray-100 text-gray-800 border-gray-200';
            const timeAgo = getTimeAgo(new Date(notif.created_at));

            return `
                <div class="p-3 border rounded-lg ${color} ${notif.read ? 'opacity-60' : ''}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-medium text-sm">${notif.title}</p>
                            <p class="text-xs mt-1 opacity-90">${notif.message}</p>
                            <p class="text-xs mt-1 opacity-75">${timeAgo}</p>
                        </div>
                        ${!notif.read ? '<span class="ml-2 px-2 py-1 rounded text-xs font-medium bg-white opacity-75">New</span>' : ''}
                    </div>
                </div>
            `;
        }).join('');
    }

    function getTimeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        if (seconds < 60) return 'Just now';
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) return minutes + 'm ago';
        const hours = Math.floor(minutes / 60);
        if (hours < 24) return hours + 'h ago';
        const days = Math.floor(hours / 24);
        return days + 'd ago';
    }

    document.getElementById('refreshNotifications').addEventListener('click', loadNotifications);
    
    document.getElementById('markAllReadBtn').addEventListener('click', async function() {
        try {
            const response = await fetch(`${API_BASE_URL}/notifications/read-all`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });

            if (response.ok) {
                loadNotifications();
            }
        } catch (error) {
            console.error('Error marking all as read:', error);
        }
    });

    loadNotifications();
    setInterval(loadNotifications, 30000); // Refresh every 30 seconds
});
</script>
@endpush
@endsection
