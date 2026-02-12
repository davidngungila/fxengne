@extends('layouts.app')

@section('title', 'Signals - FXEngine')
@section('page-title', 'Signals')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Trading Signals Dashboard</h2>
            <p class="text-sm text-gray-600 mt-1">Monitor and manage trading signals from multiple strategies</p>
        </div>
        <button id="generateSignals" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Generate Signals
        </button>
    </div>

    <!-- Signal Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Signals</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="activeSignalsCount">0</p>
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
                    <p class="text-sm text-gray-600">Total Signals</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalSignalsCount">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Avg Profit</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="avgProfit">$0.00</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('signals.active') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Active Signals</h3>
                    <p class="text-sm text-gray-600">View current trading signals</p>
                </div>
            </div>
        </a>

        <a href="{{ route('signals.history') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Signal History</h3>
                    <p class="text-sm text-gray-600">Review past signals</p>
                </div>
            </div>
        </a>

        <a href="{{ route('signals.alerts') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Alert Settings</h3>
                    <p class="text-sm text-gray-600">Configure notifications</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Signals -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent Signals</h3>
            <a href="{{ route('signals.active') }}" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Instrument</th>
                        <th>Strategy</th>
                        <th>Signal</th>
                        <th>Strength</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="recentSignals">
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            <p>Loading signals...</p>
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
    const CSRF_TOKEN = '{{ csrf_token() }}';

    async function loadSignals() {
        try {
            const response = await fetch(`${API_BASE_URL}/signals/active`);
            const result = await response.json();

            if (result.success) {
                const signals = result.data || [];
                document.getElementById('activeSignalsCount').textContent = signals.length;
                document.getElementById('totalSignalsCount').textContent = signals.length;
                renderRecentSignals(signals.slice(0, 5));
            }
        } catch (error) {
            console.error('Error loading signals:', error);
        }
    }

    function renderRecentSignals(signals) {
        const tbody = document.getElementById('recentSignals');
        
        if (signals.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-500"><p>No signals found</p></td></tr>';
            return;
        }

        tbody.innerHTML = signals.map(signal => {
            const strengthColor = signal.strength >= 80 ? 'bg-green-100 text-green-800' : 
                                 signal.strength >= 60 ? 'bg-yellow-100 text-yellow-800' : 
                                 'bg-gray-100 text-gray-800';
            const signalType = signal.type === 'BUY' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';

            return `
                <tr class="hover:bg-gray-50">
                    <td class="text-sm text-gray-600">${new Date(signal.time || Date.now()).toLocaleString()}</td>
                    <td class="font-medium">${signal.instrument?.replace('_', '/') || 'N/A'}</td>
                    <td class="text-sm">${signal.strategy || 'N/A'}</td>
                    <td><span class="px-2 py-1 rounded text-xs font-medium ${signalType}">${signal.type || 'N/A'}</span></td>
                    <td><span class="px-2 py-1 rounded text-xs font-medium ${strengthColor}">${signal.strength || 0}%</span></td>
                    <td class="font-mono text-sm">${parseFloat(signal.price || 0).toFixed(5)}</td>
                    <td><span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Active</span></td>
                </tr>
            `;
        }).join('');
    }

    document.getElementById('generateSignals').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating...';

        try {
            const response = await fetch(`${API_BASE_URL}/signals/generate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ strategies: [] })
            });

            const result = await response.json();
            if (result.success) {
                alert('Signals generated successfully!');
                loadSignals();
            }
        } catch (error) {
            alert('Error generating signals: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>Generate Signals';
        }
    });

    loadSignals();
    setInterval(loadSignals, 30000); // Refresh every 30 seconds
});
</script>
@endpush
@endsection
