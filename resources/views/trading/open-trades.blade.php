@extends('layouts.app')

@section('title', 'Open Trades - FXEngine')
@section('page-title', 'Open Trades')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Open Trades</h2>
            <p class="text-sm text-gray-600 mt-1">Manage your active trading positions</p>
        </div>
        <div class="flex items-center space-x-3">
            <button id="refreshTrades" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
            <a href="{{ route('trading.manual-entry') }}" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Trade
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Open</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalOpen">0</p>
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
                    <p class="text-sm text-gray-600">Unrealized P/L</p>
                    <p class="text-2xl font-bold mt-1" id="unrealizedPL">$0.00</p>
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
                    <p class="text-sm text-gray-600">Total Exposure</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalExposure">$0.00</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Margin Used</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="marginUsed">$0.00</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Open Trades Table -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Active Positions</h3>
            <input type="text" id="searchTrades" placeholder="Search trades..." class="form-input w-48 text-sm">
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Instrument</th>
                        <th>Type</th>
                        <th>Units</th>
                        <th>Entry Price</th>
                        <th>Current Price</th>
                        <th>Stop Loss</th>
                        <th>Take Profit</th>
                        <th>P/L</th>
                        <th>P/L %</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="openTradesTable">
                    <tr>
                        <td colspan="10" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p>Loading open trades...</p>
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
    let openTrades = [];

    async function loadOpenTrades() {
        try {
            const response = await fetch(`${API_BASE_URL}/trade/open`);
            const result = await response.json();

            if (result.success) {
                openTrades = result.data?.trades || [];
                updateSummary();
                renderTrades();
            } else {
                document.getElementById('openTradesTable').innerHTML = `
                    <tr>
                        <td colspan="10" class="text-center py-8 text-gray-500">
                            <p>No open trades found</p>
                        </td>
                    </tr>
                `;
            }
        } catch (error) {
            console.error('Error loading open trades:', error);
        }
    }

    function updateSummary() {
        const total = openTrades.length;
        const unrealizedPL = openTrades.reduce((sum, t) => sum + parseFloat(t.unrealizedPL || 0), 0);
        const totalExposure = openTrades.reduce((sum, t) => sum + Math.abs(parseFloat(t.currentUnits || 0) * parseFloat(t.price || 0)), 0);
        const marginUsed = openTrades.reduce((sum, t) => sum + parseFloat(t.marginUsed || 0), 0);

        document.getElementById('totalOpen').textContent = total;
        document.getElementById('unrealizedPL').textContent = '$' + unrealizedPL.toFixed(2);
        document.getElementById('unrealizedPL').className = unrealizedPL >= 0 
            ? 'text-2xl font-bold text-green-600 mt-1' 
            : 'text-2xl font-bold text-red-600 mt-1';
        document.getElementById('totalExposure').textContent = '$' + totalExposure.toFixed(2);
        document.getElementById('marginUsed').textContent = '$' + marginUsed.toFixed(2);
    }

    function renderTrades(filteredTrades = null) {
        const tbody = document.getElementById('openTradesTable');
        const trades = filteredTrades || openTrades;

        if (trades.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="10" class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>No open trades</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = trades.map(trade => {
            const pl = parseFloat(trade.unrealizedPL || 0);
            const plPercent = trade.openPrice ? ((pl / (Math.abs(trade.currentUnits || 0) * trade.openPrice)) * 100) : 0;
            const plClass = pl >= 0 ? 'text-green-600' : 'text-red-600';
            const type = trade.currentUnits > 0 ? 'BUY' : 'SELL';
            const typeColor = type === 'BUY' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';

            return `
                <tr class="hover:bg-gray-50">
                    <td class="font-medium">${trade.instrument?.replace('_', '/') || 'N/A'}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs font-medium ${typeColor}">${type}</span>
                    </td>
                    <td class="font-mono text-sm">${Math.abs(trade.currentUnits || 0)}</td>
                    <td class="font-mono text-sm">${parseFloat(trade.openPrice || 0).toFixed(5)}</td>
                    <td class="font-mono text-sm font-semibold">${parseFloat(trade.currentPrice || trade.price || 0).toFixed(5)}</td>
                    <td class="font-mono text-sm text-red-600">${trade.stopLossOrder?.price ? parseFloat(trade.stopLossOrder.price).toFixed(5) : '--'}</td>
                    <td class="font-mono text-sm text-green-600">${trade.takeProfitOrder?.price ? parseFloat(trade.takeProfitOrder.price).toFixed(5) : '--'}</td>
                    <td class="font-semibold ${plClass}">${pl >= 0 ? '+' : ''}$${pl.toFixed(2)}</td>
                    <td class="font-semibold ${plClass}">${plPercent >= 0 ? '+' : ''}${plPercent.toFixed(2)}%</td>
                    <td>
                        <button onclick="closeTrade('${trade.id}')" class="text-sm px-3 py-1 rounded-lg font-medium transition-colors bg-red-100 text-red-700 hover:bg-red-200">
                            Close
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    async function closeTrade(tradeId) {
        if (!confirm('Are you sure you want to close this trade?')) {
            return;
        }

        try {
            const response = await fetch(`${API_BASE_URL}/trade/close/${tradeId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });

            const result = await response.json();

            if (result.success) {
                alert('Trade closed successfully!');
                loadOpenTrades();
            } else {
                alert('Error closing trade: ' + result.message);
            }
        } catch (error) {
            alert('Error closing trade: ' + error.message);
        }
    }

    window.closeTrade = closeTrade;

    document.getElementById('refreshTrades').addEventListener('click', loadOpenTrades);
    document.getElementById('searchTrades').addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        const filtered = openTrades.filter(t => 
            (t.instrument || '').toLowerCase().includes(search)
        );
        renderTrades(filtered);
    });

    loadOpenTrades();
    setInterval(loadOpenTrades, 10000); // Refresh every 10 seconds
});
</script>
@endpush
@endsection
