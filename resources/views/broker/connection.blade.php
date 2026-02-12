@extends('layouts.app')

@section('title', 'Broker Connection - FxEngne')
@section('page-title', 'Broker Connection')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Broker Connection</h2>
            <p class="text-sm text-gray-600 mt-1">Manage and monitor your broker API connection</p>
        </div>
        <button id="testConnection" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Test Connection
        </button>
    </div>

    <!-- Connection Status -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Connection Status</h3>
                <div id="connectionIndicator" class="w-4 h-4 rounded-full {{ $connectionStatus['connected'] ?? false ? 'bg-green-500 animate-pulse' : 'bg-red-500' }}"></div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span class="font-semibold {{ $connectionStatus['connected'] ?? false ? 'text-green-600' : 'text-red-600' }}" id="connectionStatus">
                        {{ $connectionStatus['connected'] ?? false ? 'Connected' : 'Disconnected' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Broker:</span>
                    <span class="font-semibold text-gray-900">OANDA</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Environment:</span>
                    <span class="font-semibold text-gray-900" id="environment">{{ config('services.oanda.environment', 'practice') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Last Check:</span>
                    <span class="text-sm text-gray-600" id="lastCheck">{{ $connectionStatus['last_check'] ?? 'Never' }}</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
            <div id="accountInfo" class="space-y-3">
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <p>Click "Test Connection" to load account information</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Connection Details -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Connection Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-900 mb-3">API Configuration</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">API Key:</span>
                        <span class="font-mono text-xs {{ $oandaEnabled ? 'text-green-600' : 'text-red-600' }}">
                            {{ $oandaEnabled ? '••••••••••••••••' : 'Not Configured' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Account ID:</span>
                        <span class="font-mono text-xs {{ $oandaEnabled ? 'text-green-600' : 'text-red-600' }}">
                            {{ $oandaEnabled ? 'Configured' : 'Not Configured' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Base URL:</span>
                        <span class="font-mono text-xs text-gray-900" id="baseUrl">
                            {{ config('services.oanda.environment') === 'live' ? 'api-fxtrade.oanda.com' : 'api-fxpractice.oanda.com' }}
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-3">Connection Health</h4>
                <div class="space-y-3">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm text-gray-600">Response Time</span>
                            <span class="text-sm font-semibold text-gray-900" id="responseTime">--</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" id="responseTimeBar" style="width: 0%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm text-gray-600">Success Rate</span>
                            <span class="text-sm font-semibold text-gray-900" id="successRate">--</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" id="successRateBar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('broker.api-settings') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">API Settings</h4>
                        <p class="text-sm text-gray-600">Configure API credentials</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('broker.execution-logs') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Execution Logs</h4>
                        <p class="text-sm text-gray-600">View trade execution history</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('broker.vps-status') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">VPS Status</h4>
                        <p class="text-sm text-gray-600">Monitor server status</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';

    // Test connection
    document.getElementById('testConnection').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Testing...';

        const startTime = Date.now();

        try {
            const response = await fetch(`${API_BASE_URL}/broker/test-connection`);
            const result = await response.json();

            const responseTime = Date.now() - startTime;

            if (result.success) {
                // Update connection status
                document.getElementById('connectionIndicator').className = 'w-4 h-4 rounded-full bg-green-500 animate-pulse';
                document.getElementById('connectionStatus').textContent = 'Connected';
                document.getElementById('connectionStatus').className = 'font-semibold text-green-600';
                document.getElementById('lastCheck').textContent = new Date().toLocaleString();

                // Update account info
                const accountInfo = document.getElementById('accountInfo');
                accountInfo.innerHTML = `
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Account ID:</span>
                            <span class="font-semibold text-gray-900">${result.data.account_id || 'N/A'}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Currency:</span>
                            <span class="font-semibold text-gray-900">${result.data.currency || 'N/A'}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Balance:</span>
                            <span class="font-semibold text-green-600">${parseFloat(result.data.balance || 0).toLocaleString()} ${result.data.currency || ''}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Margin Available:</span>
                            <span class="font-semibold text-gray-900">${parseFloat(result.data.margin_available || 0).toLocaleString()}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Open Trades:</span>
                            <span class="font-semibold text-gray-900">${result.data.open_trade_count || 0}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Open Positions:</span>
                            <span class="font-semibold text-gray-900">${result.data.open_position_count || 0}</span>
                        </div>
                    </div>
                `;

                // Update response time
                document.getElementById('responseTime').textContent = responseTime + 'ms';
                const responseTimePercent = Math.min(100, (responseTime / 1000) * 100);
                document.getElementById('responseTimeBar').style.width = responseTimePercent + '%';
                document.getElementById('responseTimeBar').className = responseTime < 500 ? 'bg-green-600 h-2 rounded-full' : responseTime < 1000 ? 'bg-yellow-600 h-2 rounded-full' : 'bg-red-600 h-2 rounded-full';

                // Update success rate
                document.getElementById('successRate').textContent = '100%';
                document.getElementById('successRateBar').style.width = '100%';

                alert('Connection successful!');
            } else {
                document.getElementById('connectionIndicator').className = 'w-4 h-4 rounded-full bg-red-500';
                document.getElementById('connectionStatus').textContent = 'Failed';
                document.getElementById('connectionStatus').className = 'font-semibold text-red-600';
                alert('Connection failed: ' + result.message);
            }
        } catch (error) {
            document.getElementById('connectionIndicator').className = 'w-4 h-4 rounded-full bg-red-500';
            document.getElementById('connectionStatus').textContent = 'Error';
            document.getElementById('connectionStatus').className = 'font-semibold text-red-600';
            alert('Connection error: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Test Connection';
        }
    });
});
</script>
@endpush
@endsection

