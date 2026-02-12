@extends('layouts.app')

@section('title', 'Trade History - FXEngine')
@section('page-title', 'Trade History')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Trade History</h2>
            <p class="text-sm text-gray-600 mt-1">Complete history of all your trading activity</p>
        </div>
        <div class="flex items-center space-x-3">
            <select id="dateRange" class="form-input text-sm">
                <option value="all">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
            </select>
            <button id="exportHistory" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export
            </button>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Trades</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalTrades">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Winning Trades</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="winningTrades">0</p>
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
                    <p class="text-sm text-gray-600">Losing Trades</p>
                    <p class="text-2xl font-bold text-red-600 mt-1" id="losingTrades">0</p>
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
                    <p class="text-sm text-gray-600">Total Profit</p>
                    <p class="text-2xl font-bold mt-1" id="totalProfit">$0.00</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="flex items-center space-x-4">
            <select id="filterInstrument" class="form-input text-sm">
                <option value="all">All Instruments</option>
                <option value="EUR_USD">EUR/USD</option>
                <option value="GBP_USD">GBP/USD</option>
                <option value="USD_JPY">USD/JPY</option>
                <option value="XAU_USD">XAU/USD</option>
            </select>
            <select id="filterResult" class="form-input text-sm">
                <option value="all">All Results</option>
                <option value="win">Winning</option>
                <option value="loss">Losing</option>
            </select>
            <input type="text" id="searchHistory" placeholder="Search..." class="form-input text-sm flex-1">
        </div>
    </div>

    <!-- History Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Instrument</th>
                        <th>Type</th>
                        <th>Units</th>
                        <th>Entry</th>
                        <th>Exit</th>
                        <th>Duration</th>
                        <th>P/L</th>
                        <th>P/L %</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody id="historyTable">
                    <tr>
                        <td colspan="10" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p>Loading trade history...</p>
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
    let tradeHistory = [];

    async function loadHistory() {
        try {
            const response = await fetch(`${API_BASE_URL}/trade/history`);
            const result = await response.json();

            if (result.success) {
                tradeHistory = result.data?.trades || [];
                updateStatistics();
                renderHistory();
            }
        } catch (error) {
            console.error('Error loading history:', error);
        }
    }

    function updateStatistics() {
        const total = tradeHistory.length;
        const winning = tradeHistory.filter(t => parseFloat(t.realizedPL || 0) > 0).length;
        const losing = tradeHistory.filter(t => parseFloat(t.realizedPL || 0) < 0).length;
        const totalProfit = tradeHistory.reduce((sum, t) => sum + parseFloat(t.realizedPL || 0), 0);

        document.getElementById('totalTrades').textContent = total;
        document.getElementById('winningTrades').textContent = winning;
        document.getElementById('losingTrades').textContent = losing;
        document.getElementById('totalProfit').textContent = '$' + totalProfit.toFixed(2);
        document.getElementById('totalProfit').className = totalProfit >= 0 
            ? 'text-2xl font-bold text-green-600 mt-1' 
            : 'text-2xl font-bold text-red-600 mt-1';
    }

    function renderHistory(filteredHistory = null) {
        const tbody = document.getElementById('historyTable');
        const history = filteredHistory || tradeHistory;

        if (history.length === 0) {
            tbody.innerHTML = '<tr><td colspan="10" class="text-center py-8 text-gray-500"><p>No trade history found</p></td></tr>';
            return;
        }

        tbody.innerHTML = history.map(trade => {
            const pl = parseFloat(trade.realizedPL || 0);
            const plClass = pl >= 0 ? 'text-green-600' : 'text-red-600';
            const type = trade.currentUnits > 0 ? 'BUY' : 'SELL';
            const typeColor = type === 'BUY' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            const openTime = new Date(trade.openTime || trade.time);
            const closeTime = trade.closeTime ? new Date(trade.closeTime) : null;
            const duration = closeTime ? Math.round((closeTime - openTime) / (1000 * 60)) + 'm' : '--';

            return `
                <tr class="hover:bg-gray-50">
                    <td class="text-sm text-gray-600">${openTime.toLocaleString()}</td>
                    <td class="font-medium">${trade.instrument?.replace('_', '/') || 'N/A'}</td>
                    <td><span class="px-2 py-1 rounded text-xs font-medium ${typeColor}">${type}</span></td>
                    <td class="font-mono text-sm">${Math.abs(trade.currentUnits || trade.units || 0)}</td>
                    <td class="font-mono text-sm">${parseFloat(trade.openPrice || 0).toFixed(5)}</td>
                    <td class="font-mono text-sm">${closeTime ? parseFloat(trade.closePrice || 0).toFixed(5) : '--'}</td>
                    <td class="text-sm text-gray-600">${duration}</td>
                    <td class="font-semibold ${plClass}">${pl >= 0 ? '+' : ''}$${pl.toFixed(2)}</td>
                    <td class="font-semibold ${plClass}">${trade.openPrice ? ((pl / (Math.abs(trade.currentUnits || 0) * trade.openPrice)) * 100).toFixed(2) : '0.00'}%</td>
                    <td>
                        <button class="text-blue-600 hover:text-blue-700 text-sm">View</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function applyFilters() {
        const instrument = document.getElementById('filterInstrument').value;
        const result = document.getElementById('filterResult').value;
        const search = document.getElementById('searchHistory').value.toLowerCase();

        let filtered = tradeHistory;

        if (instrument !== 'all') {
            filtered = filtered.filter(t => t.instrument === instrument);
        }
        if (result === 'win') {
            filtered = filtered.filter(t => parseFloat(t.realizedPL || 0) > 0);
        } else if (result === 'loss') {
            filtered = filtered.filter(t => parseFloat(t.realizedPL || 0) < 0);
        }
        if (search) {
            filtered = filtered.filter(t => 
                (t.instrument || '').toLowerCase().includes(search)
            );
        }

        renderHistory(filtered);
    }

    document.getElementById('filterInstrument').addEventListener('change', applyFilters);
    document.getElementById('filterResult').addEventListener('change', applyFilters);
    document.getElementById('searchHistory').addEventListener('input', applyFilters);

    loadHistory();
});
</script>
@endpush
@endsection
