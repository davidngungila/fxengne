@extends('layouts.app')

@section('title', 'Trading - FxEngne')
@section('page-title', 'Trading')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Trading Dashboard</h2>
            <p class="text-sm text-gray-600 mt-1">Manage your trades, positions, and trading activities</p>
        </div>
        <a href="{{ route('trading.manual-entry') }}" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            New Trade
        </a>
    </div>

    <!-- Trading Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Open Trades</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="openTradesCount">0</p>
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
                    <p class="text-sm text-gray-600">Total Profit</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="totalProfit">$0.00</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Win Rate</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1" id="winRate">0%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Trades</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalTrades">0</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('trading.open-trades') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Open Trades</h3>
                    <p class="text-sm text-gray-600">View and manage active positions</p>
                </div>
            </div>
        </a>

        <a href="{{ route('trading.history') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Trade History</h3>
                    <p class="text-sm text-gray-600">Review past trading activity</p>
                </div>
            </div>
        </a>

        <a href="{{ route('trading.manual-entry') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Manual Entry</h3>
                    <p class="text-sm text-gray-600">Place new trades manually</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Trades -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent Trades</h3>
            <a href="{{ route('trading.history') }}" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Instrument</th>
                        <th>Type</th>
                        <th>Units</th>
                        <th>Entry</th>
                        <th>Current</th>
                        <th>P/L</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="recentTrades">
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            <p>No recent trades</p>
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
    
    async function loadTradingData() {
        try {
            // Load open trades
            const openResponse = await fetch(`${API_BASE_URL}/trade/open`);
            const openResult = await openResponse.json();
            
            if (openResult.success) {
                const openTrades = openResult.data?.trades || [];
                document.getElementById('openTradesCount').textContent = openTrades.length;
            }

            // Load trade history
            const historyResponse = await fetch(`${API_BASE_URL}/trade/history`);
            const historyResult = await historyResponse.json();
            
            if (historyResult.success) {
                const trades = historyResult.data?.trades || [];
                document.getElementById('totalTrades').textContent = trades.length;
                
                // Calculate win rate
                const winningTrades = trades.filter(t => (t.realizedPL || 0) > 0).length;
                const winRate = trades.length > 0 ? ((winningTrades / trades.length) * 100).toFixed(1) : 0;
                document.getElementById('winRate').textContent = winRate + '%';
                
                // Calculate total profit
                const totalProfit = trades.reduce((sum, t) => sum + (parseFloat(t.realizedPL || 0)), 0);
                document.getElementById('totalProfit').textContent = '$' + totalProfit.toFixed(2);
                document.getElementById('totalProfit').className = totalProfit >= 0 
                    ? 'text-2xl font-bold text-green-600 mt-1' 
                    : 'text-2xl font-bold text-red-600 mt-1';
                
                // Display recent trades
                renderRecentTrades(trades.slice(0, 5));
            }
        } catch (error) {
            console.error('Error loading trading data:', error);
        }
    }

    function renderRecentTrades(trades) {
        const tbody = document.getElementById('recentTrades');
        
        if (trades.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center py-8 text-gray-500"><p>No recent trades</p></td></tr>';
            return;
        }

        tbody.innerHTML = trades.map(trade => {
            const pl = parseFloat(trade.realizedPL || trade.unrealizedPL || 0);
            const plClass = pl >= 0 ? 'text-green-600' : 'text-red-600';
            const status = trade.state === 'OPEN' ? 'Open' : 'Closed';
            const statusColor = trade.state === 'OPEN' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800';

            return `
                <tr class="hover:bg-gray-50">
                    <td class="text-sm text-gray-600">${new Date(trade.openTime || trade.time).toLocaleString()}</td>
                    <td class="font-medium">${trade.instrument?.replace('_', '/') || 'N/A'}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs font-medium ${trade.currentUnits > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${trade.currentUnits > 0 ? 'BUY' : 'SELL'}
                        </span>
                    </td>
                    <td class="text-sm">${Math.abs(trade.currentUnits || trade.units || 0)}</td>
                    <td class="font-mono text-sm">${parseFloat(trade.price || trade.openPrice || 0).toFixed(5)}</td>
                    <td class="font-mono text-sm">${parseFloat(trade.currentPrice || trade.price || 0).toFixed(5)}</td>
                    <td class="font-semibold ${plClass}">${pl >= 0 ? '+' : ''}$${pl.toFixed(2)}</td>
                    <td><span class="px-2 py-1 rounded text-xs font-medium ${statusColor}">${status}</span></td>
                </tr>
            `;
        }).join('');
    }

    loadTradingData();
    setInterval(loadTradingData, 30000); // Refresh every 30 seconds
});
</script>
@endpush
@endsection
