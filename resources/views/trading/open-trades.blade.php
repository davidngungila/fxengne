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
            <button id="exportOpenTrades" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export
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
        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Open</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="totalOpen">{{ $totalOpen }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Unrealized P/L</p>
                    <p class="text-3xl font-bold mt-1 {{ $unrealizedPL >= 0 ? 'text-green-600' : 'text-red-600' }}" id="unrealizedPL">${{ number_format($unrealizedPL, 2) }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Exposure</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="totalExposure">${{ number_format($totalExposure, 2) }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Margin Used</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="marginUsed">${{ number_format($marginUsed, 2) }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- P/L Distribution Chart -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">P/L Distribution</h3>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="plDistributionChart"></canvas>
            </div>
        </div>

        <!-- Instrument Distribution -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">By Instrument</h3>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="instrumentChart"></canvas>
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
                    @forelse($openTrades as $trade)
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">{{ $trade->formatted_instrument }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $trade->type === 'BUY' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $trade->type }}
                            </span>
                        </td>
                        <td class="font-mono text-sm">{{ number_format($trade->units, 0) }}</td>
                        <td class="font-mono text-sm">{{ number_format($trade->entry_price, 5) }}</td>
                        <td class="font-mono text-sm font-semibold">{{ number_format($trade->current_price ?? $trade->entry_price, 5) }}</td>
                        <td class="font-mono text-sm text-red-600">{{ $trade->stop_loss ? number_format($trade->stop_loss, 5) : '--' }}</td>
                        <td class="font-mono text-sm text-green-600">{{ $trade->take_profit ? number_format($trade->take_profit, 5) : '--' }}</td>
                        <td class="font-semibold {{ $trade->unrealized_pl >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $trade->unrealized_pl >= 0 ? '+' : '' }}${{ number_format($trade->unrealized_pl, 2) }}
                        </td>
                        <td class="font-semibold {{ $trade->unrealized_pl >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $trade->pl_percentage >= 0 ? '+' : '' }}{{ number_format($trade->pl_percentage, 2) }}%
                        </td>
                        <td>
                            <button onclick="closeTrade('{{ $trade->oanda_trade_id ?? $trade->id }}')" class="text-sm px-3 py-1 rounded-lg font-medium transition-colors bg-red-100 text-red-700 hover:bg-red-200">
                                Close
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>No open trades</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    let openTrades = [];
    let plChart = null;
    let instrumentChart = null;
    
    // Initialize charts
    initCharts();

    async function loadOpenTrades() {
        try {
            const response = await fetch(`${API_BASE_URL}/trade/open`);
            const result = await response.json();

            if (result.success) {
                openTrades = result.data?.trades || [];
                updateSummary();
                updateCharts();
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
        const totalExposure = openTrades.reduce((sum, t) => {
            const units = Math.abs(parseFloat(t.currentUnits || t.units || 0));
            const price = parseFloat(t.currentPrice || t.openPrice || 0);
            return sum + (units * price);
        }, 0);
        const marginUsed = openTrades.reduce((sum, t) => sum + parseFloat(t.marginUsed || 0), 0);

        document.getElementById('totalOpen').textContent = total;
        const plEl = document.getElementById('unrealizedPL');
        plEl.textContent = '$' + unrealizedPL.toFixed(2);
        plEl.className = unrealizedPL >= 0 
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
            const units = Math.abs(trade.currentUnits || trade.units || 0);
            const entryPrice = parseFloat(trade.openPrice || 0);
            const plPercent = entryPrice > 0 ? ((pl / (units * entryPrice)) * 100) : 0;
            const plClass = pl >= 0 ? 'text-green-600' : 'text-red-600';
            const type = trade.type || (trade.currentUnits > 0 ? 'BUY' : 'SELL');
            const typeColor = type === 'BUY' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            const tradeId = trade.id || (trade.oanda_trade_id || '');

            return `
                <tr class="hover:bg-gray-50">
                    <td class="font-medium">${(trade.instrument || '').replace('_', '/')}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs font-medium ${typeColor}">${type}</span>
                    </td>
                    <td class="font-mono text-sm">${units.toLocaleString()}</td>
                    <td class="font-mono text-sm">${entryPrice.toFixed(5)}</td>
                    <td class="font-mono text-sm font-semibold">${parseFloat(trade.currentPrice || trade.price || entryPrice).toFixed(5)}</td>
                    <td class="font-mono text-sm text-red-600">${trade.stopLossOrder?.price ? parseFloat(trade.stopLossOrder.price).toFixed(5) : '--'}</td>
                    <td class="font-mono text-sm text-green-600">${trade.takeProfitOrder?.price ? parseFloat(trade.takeProfitOrder.price).toFixed(5) : '--'}</td>
                    <td class="font-semibold ${plClass}">${pl >= 0 ? '+' : ''}$${pl.toFixed(2)}</td>
                    <td class="font-semibold ${plClass}">${plPercent >= 0 ? '+' : ''}${plPercent.toFixed(2)}%</td>
                    <td>
                        <button onclick="closeTrade('${tradeId}')" class="text-sm px-3 py-1 rounded-lg font-medium transition-colors bg-red-100 text-red-700 hover:bg-red-200">
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

    function initCharts() {
        // P/L Distribution Chart
        const plCtx = document.getElementById('plDistributionChart');
        if (plCtx) {
            plChart = new Chart(plCtx, {
                type: 'bar',
                data: {
                    labels: ['Profitable', 'Losing'],
                    datasets: [{
                        label: 'Trades',
                        data: [0, 0],
                        backgroundColor: ['#00C853', '#D50000'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Instrument Distribution Chart
        const instCtx = document.getElementById('instrumentChart');
        if (instCtx) {
            instrumentChart = new Chart(instCtx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            '#2962FF', '#00C853', '#FFD600', '#FF6D00', '#D50000', '#9C27B0'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }

    function updateCharts() {
        if (!plChart || !instrumentChart) return;
        
        // Update P/L Distribution
        const profitable = openTrades.filter(t => parseFloat(t.unrealizedPL || 0) > 0).length;
        const losing = openTrades.filter(t => parseFloat(t.unrealizedPL || 0) < 0).length;
        plChart.data.datasets[0].data = [profitable, losing];
        plChart.update('none');
        
        // Update Instrument Distribution
        const instrumentCounts = {};
        openTrades.forEach(trade => {
            const inst = (trade.instrument || '').replace('_', '/');
            instrumentCounts[inst] = (instrumentCounts[inst] || 0) + 1;
        });
        
        instrumentChart.data.labels = Object.keys(instrumentCounts);
        instrumentChart.data.datasets[0].data = Object.values(instrumentCounts);
        instrumentChart.update('none');
    }

    window.closeTrade = closeTrade;

    // Export functionality
    document.getElementById('exportOpenTrades').addEventListener('click', function() {
        const btn = this;
        const originalText = btn.innerHTML;
        
        // Disable button and show loading
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Exporting...';
        
        // Create download link
        const exportUrl = '{{ route("trading.open-trades.export") }}';
        const link = document.createElement('a');
        link.href = exportUrl;
        link.download = '';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Re-enable button after a short delay
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }, 1000);
    });

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
            <button id="exportOpenTrades" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export
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
        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Open</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="totalOpen">{{ $totalOpen }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Unrealized P/L</p>
                    <p class="text-3xl font-bold mt-1 {{ $unrealizedPL >= 0 ? 'text-green-600' : 'text-red-600' }}" id="unrealizedPL">${{ number_format($unrealizedPL, 2) }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Exposure</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="totalExposure">${{ number_format($totalExposure, 2) }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Margin Used</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="marginUsed">${{ number_format($marginUsed, 2) }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- P/L Distribution Chart -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">P/L Distribution</h3>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="plDistributionChart"></canvas>
            </div>
        </div>

        <!-- Instrument Distribution -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">By Instrument</h3>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="instrumentChart"></canvas>
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
                    @forelse($openTrades as $trade)
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">{{ $trade->formatted_instrument }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $trade->type === 'BUY' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $trade->type }}
                            </span>
                        </td>
                        <td class="font-mono text-sm">{{ number_format($trade->units, 0) }}</td>
                        <td class="font-mono text-sm">{{ number_format($trade->entry_price, 5) }}</td>
                        <td class="font-mono text-sm font-semibold">{{ number_format($trade->current_price ?? $trade->entry_price, 5) }}</td>
                        <td class="font-mono text-sm text-red-600">{{ $trade->stop_loss ? number_format($trade->stop_loss, 5) : '--' }}</td>
                        <td class="font-mono text-sm text-green-600">{{ $trade->take_profit ? number_format($trade->take_profit, 5) : '--' }}</td>
                        <td class="font-semibold {{ $trade->unrealized_pl >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $trade->unrealized_pl >= 0 ? '+' : '' }}${{ number_format($trade->unrealized_pl, 2) }}
                        </td>
                        <td class="font-semibold {{ $trade->unrealized_pl >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $trade->pl_percentage >= 0 ? '+' : '' }}{{ number_format($trade->pl_percentage, 2) }}%
                        </td>
                        <td>
                            <button onclick="closeTrade('{{ $trade->oanda_trade_id ?? $trade->id }}')" class="text-sm px-3 py-1 rounded-lg font-medium transition-colors bg-red-100 text-red-700 hover:bg-red-200">
                                Close
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>No open trades</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    let openTrades = [];
    let plChart = null;
    let instrumentChart = null;
    
    // Initialize charts
    initCharts();

    async function loadOpenTrades() {
        try {
            const response = await fetch(`${API_BASE_URL}/trade/open`);
            const result = await response.json();

            if (result.success) {
                openTrades = result.data?.trades || [];
                updateSummary();
                updateCharts();
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
        const totalExposure = openTrades.reduce((sum, t) => {
            const units = Math.abs(parseFloat(t.currentUnits || t.units || 0));
            const price = parseFloat(t.currentPrice || t.openPrice || 0);
            return sum + (units * price);
        }, 0);
        const marginUsed = openTrades.reduce((sum, t) => sum + parseFloat(t.marginUsed || 0), 0);

        document.getElementById('totalOpen').textContent = total;
        const plEl = document.getElementById('unrealizedPL');
        plEl.textContent = '$' + unrealizedPL.toFixed(2);
        plEl.className = unrealizedPL >= 0 
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
            const units = Math.abs(trade.currentUnits || trade.units || 0);
            const entryPrice = parseFloat(trade.openPrice || 0);
            const plPercent = entryPrice > 0 ? ((pl / (units * entryPrice)) * 100) : 0;
            const plClass = pl >= 0 ? 'text-green-600' : 'text-red-600';
            const type = trade.type || (trade.currentUnits > 0 ? 'BUY' : 'SELL');
            const typeColor = type === 'BUY' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            const tradeId = trade.id || (trade.oanda_trade_id || '');

            return `
                <tr class="hover:bg-gray-50">
                    <td class="font-medium">${(trade.instrument || '').replace('_', '/')}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs font-medium ${typeColor}">${type}</span>
                    </td>
                    <td class="font-mono text-sm">${units.toLocaleString()}</td>
                    <td class="font-mono text-sm">${entryPrice.toFixed(5)}</td>
                    <td class="font-mono text-sm font-semibold">${parseFloat(trade.currentPrice || trade.price || entryPrice).toFixed(5)}</td>
                    <td class="font-mono text-sm text-red-600">${trade.stopLossOrder?.price ? parseFloat(trade.stopLossOrder.price).toFixed(5) : '--'}</td>
                    <td class="font-mono text-sm text-green-600">${trade.takeProfitOrder?.price ? parseFloat(trade.takeProfitOrder.price).toFixed(5) : '--'}</td>
                    <td class="font-semibold ${plClass}">${pl >= 0 ? '+' : ''}$${pl.toFixed(2)}</td>
                    <td class="font-semibold ${plClass}">${plPercent >= 0 ? '+' : ''}${plPercent.toFixed(2)}%</td>
                    <td>
                        <button onclick="closeTrade('${tradeId}')" class="text-sm px-3 py-1 rounded-lg font-medium transition-colors bg-red-100 text-red-700 hover:bg-red-200">
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

    function initCharts() {
        // P/L Distribution Chart
        const plCtx = document.getElementById('plDistributionChart');
        if (plCtx) {
            plChart = new Chart(plCtx, {
                type: 'bar',
                data: {
                    labels: ['Profitable', 'Losing'],
                    datasets: [{
                        label: 'Trades',
                        data: [0, 0],
                        backgroundColor: ['#00C853', '#D50000'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Instrument Distribution Chart
        const instCtx = document.getElementById('instrumentChart');
        if (instCtx) {
            instrumentChart = new Chart(instCtx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            '#2962FF', '#00C853', '#FFD600', '#FF6D00', '#D50000', '#9C27B0'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }

    function updateCharts() {
        if (!plChart || !instrumentChart) return;
        
        // Update P/L Distribution
        const profitable = openTrades.filter(t => parseFloat(t.unrealizedPL || 0) > 0).length;
        const losing = openTrades.filter(t => parseFloat(t.unrealizedPL || 0) < 0).length;
        plChart.data.datasets[0].data = [profitable, losing];
        plChart.update('none');
        
        // Update Instrument Distribution
        const instrumentCounts = {};
        openTrades.forEach(trade => {
            const inst = (trade.instrument || '').replace('_', '/');
            instrumentCounts[inst] = (instrumentCounts[inst] || 0) + 1;
        });
        
        instrumentChart.data.labels = Object.keys(instrumentCounts);
        instrumentChart.data.datasets[0].data = Object.values(instrumentCounts);
        instrumentChart.update('none');
    }

    window.closeTrade = closeTrade;

    // Export functionality
    document.getElementById('exportOpenTrades').addEventListener('click', function() {
        const btn = this;
        const originalText = btn.innerHTML;
        
        // Disable button and show loading
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Exporting...';
        
        // Create download link
        const exportUrl = '{{ route("trading.open-trades.export") }}';
        const link = document.createElement('a');
        link.href = exportUrl;
        link.download = '';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Re-enable button after a short delay
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }, 1000);
    });

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
