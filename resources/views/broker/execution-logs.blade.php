@extends('layouts.app')

@section('title', 'Execution Logs - FxEngne')
@section('page-title', 'Execution Logs')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Execution Logs</h2>
            <p class="text-sm text-gray-600 mt-1">Monitor all trade execution activities and API calls</p>
        </div>
        <div class="flex items-center space-x-3">
            <select id="logLimit" class="form-input text-sm">
                <option value="50">Last 50</option>
                <option value="100" selected>Last 100</option>
                <option value="200">Last 200</option>
                <option value="500">Last 500</option>
            </select>
            <button id="refreshLogs" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
            <button id="clearLogs" class="btn btn-danger">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Clear Logs
            </button>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Executions</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalExecutions">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Successful</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="successfulExecutions">0</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Failed</p>
                    <p class="text-2xl font-bold text-red-600 mt-1" id="failedExecutions">0</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Success Rate</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1" id="successRate">0%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="flex items-center space-x-4">
            <select id="filterStatus" class="form-input text-sm">
                <option value="all">All Status</option>
                <option value="success">Success</option>
                <option value="failed">Failed</option>
                <option value="pending">Pending</option>
            </select>
            <select id="filterType" class="form-input text-sm">
                <option value="all">All Types</option>
                <option value="order">Order Execution</option>
                <option value="api">API Call</option>
                <option value="error">Error</option>
            </select>
            <input type="text" id="searchLogs" placeholder="Search logs..." class="form-input text-sm flex-1">
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                        <th>Instrument</th>
                        <th>Details</th>
                        <th>Response Time</th>
                    </tr>
                </thead>
                <tbody id="logsTableBody">
                    <!-- Logs will be populated by JavaScript -->
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p>Loading execution logs...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';
    let logs = [];

    // Load execution logs
    async function loadLogs() {
        const limit = document.getElementById('logLimit').value;
        
        try {
            const response = await fetch(`${API_BASE_URL}/broker/execution-logs?limit=${limit}`);
            const result = await response.json();

            if (result.success) {
                logs = result.data || [];
                updateStatistics();
                renderLogs();
            }
        } catch (error) {
            console.error('Error loading logs:', error);
        }
    }

    // Update statistics
    function updateStatistics() {
        const total = logs.length;
        const successful = logs.filter(l => l.status === 'success').length;
        const failed = logs.filter(l => l.status === 'failed' || l.status === 'error').length;
        const successRate = total > 0 ? ((successful / total) * 100).toFixed(1) : 0;

        document.getElementById('totalExecutions').textContent = total;
        document.getElementById('successfulExecutions').textContent = successful;
        document.getElementById('failedExecutions').textContent = failed;
        document.getElementById('successRate').textContent = successRate + '%';
    }

    // Render logs
    function renderLogs(filteredLogs = null) {
        const tbody = document.getElementById('logsTableBody');
        const displayLogs = filteredLogs || logs;

        if (displayLogs.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-500">
                        <p>No execution logs found.</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = displayLogs.map(log => {
            const statusColors = {
                'success': 'bg-green-100 text-green-800',
                'failed': 'bg-red-100 text-red-800',
                'error': 'bg-red-100 text-red-800',
                'pending': 'bg-yellow-100 text-yellow-800'
            };

            const typeColors = {
                'order': 'bg-blue-100 text-blue-800',
                'api': 'bg-purple-100 text-purple-800',
                'error': 'bg-red-100 text-red-800'
            };

            const timestamp = log.timestamp ? new Date(log.timestamp).toLocaleString() : 'N/A';
            const status = log.status || 'unknown';
            const type = log.type || 'api';

            return `
                <tr class="hover:bg-gray-50">
                    <td class="text-sm text-gray-600">${timestamp}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs font-medium ${typeColors[type] || 'bg-gray-100 text-gray-800'}">
                            ${type.toUpperCase()}
                        </span>
                    </td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs font-medium ${statusColors[status] || 'bg-gray-100 text-gray-800'}">
                            ${status.toUpperCase()}
                        </span>
                    </td>
                    <td class="font-medium text-sm">${log.action || 'N/A'}</td>
                    <td class="text-sm">${log.instrument || 'N/A'}</td>
                    <td class="text-sm text-gray-600 max-w-xs truncate" title="${log.message || log.details || ''}">
                        ${log.message || log.details || 'N/A'}
                    </td>
                    <td class="text-sm text-gray-600">${log.response_time ? log.response_time + 'ms' : 'N/A'}</td>
                </tr>
            `;
        }).join('');
    }

    // Apply filters
    function applyFilters() {
        const status = document.getElementById('filterStatus').value;
        const type = document.getElementById('filterType').value;
        const search = document.getElementById('searchLogs').value.toLowerCase();

        let filtered = logs;

        if (status !== 'all') {
            filtered = filtered.filter(l => l.status === status);
        }
        if (type !== 'all') {
            filtered = filtered.filter(l => l.type === type);
        }
        if (search) {
            filtered = filtered.filter(l => 
                (l.message || '').toLowerCase().includes(search) ||
                (l.action || '').toLowerCase().includes(search) ||
                (l.instrument || '').toLowerCase().includes(search)
            );
        }

        renderLogs(filtered);
    }

    // Event listeners
    document.getElementById('refreshLogs').addEventListener('click', loadLogs);
    document.getElementById('logLimit').addEventListener('change', loadLogs);
    document.getElementById('filterStatus').addEventListener('change', applyFilters);
    document.getElementById('filterType').addEventListener('change', applyFilters);
    document.getElementById('searchLogs').addEventListener('input', applyFilters);
    document.getElementById('clearLogs').addEventListener('click', function() {
        if (confirm('Are you sure you want to clear all execution logs?')) {
            localStorage.removeItem('execution_logs');
            logs = [];
            updateStatistics();
            renderLogs();
        }
    });

    // Initial load
    loadLogs();

    // Auto-refresh every 30 seconds
    setInterval(loadLogs, 30000);
});
</script>
@endpush
@endsection

