@extends('layouts.app')

@section('title', 'Trading Dashboard - FXEngine')
@section('page-title', 'Trading')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Trading Dashboard</h2>
            <p class="text-sm text-gray-600 mt-1">Manage your trades, positions, and trading activities</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span id="lastUpdate">Live</span>
            </div>
            <button id="refreshData" class="btn btn-secondary">
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

    <!-- Trading Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Open Trades</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="openTradesCount">{{ $openTradesCount }}</p>
                    <p class="text-xs text-gray-500 mt-1" id="unrealizedPL">Unrealized: $<span id="unrealizedPLValue">{{ number_format($unrealizedPL, 2) }}</span></p>
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
                    <p class="text-sm text-gray-600">Total Profit</p>
                    <p class="text-3xl font-bold mt-1 {{ $totalProfit >= 0 ? 'text-green-600' : 'text-red-600' }}" id="totalProfit">${{ number_format($totalProfit, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Realized P/L</p>
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
                    <p class="text-sm text-gray-600">Win Rate</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1" id="winRate">{{ number_format($winRate, 1) }}%</p>
                    <p class="text-xs text-gray-500 mt-1">Success Rate</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Trades</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="totalTrades">{{ $totalTrades }}</p>
                    <p class="text-xs text-gray-500 mt-1">All Time</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Equity Curve Chart -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Equity Curve</h3>
                <select id="equityTimeframe" class="form-input text-xs">
                    <option value="7">Last 7 Days</option>
                    <option value="30" selected>Last 30 Days</option>
                    <option value="90">Last 90 Days</option>
                    <option value="all">All Time</option>
                </select>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="equityChart"></canvas>
            </div>
        </div>

        <!-- Win/Loss Distribution -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Win/Loss Distribution</h3>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="winLossChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('trading.open-trades') }}" class="card hover:shadow-xl transition-all transform hover:-translate-y-2 border-2 border-transparent hover:border-blue-200">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 text-lg">Open Trades</h3>
                    <p class="text-sm text-gray-600">View and manage active positions</p>
                </div>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <a href="{{ route('trading.history') }}" class="card hover:shadow-xl transition-all transform hover:-translate-y-2 border-2 border-transparent hover:border-green-200">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 text-lg">Trade History</h3>
                    <p class="text-sm text-gray-600">Review past trading activity</p>
                </div>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <a href="{{ route('trading.manual-entry') }}" class="card hover:shadow-xl transition-all transform hover:-translate-y-2 border-2 border-transparent hover:border-purple-200">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 text-lg">Manual Entry</h3>
                    <p class="text-sm text-gray-600">Place new trades manually</p>
                </div>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>
    </div>

    <!-- Recent Trades -->
            <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent Trades</h3>
            <a href="{{ route('trading.history') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center">
                View All
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
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
                        <th>Current/Exit</th>
                                <th>P/L</th>
                        <th>Status</th>
                            </tr>
                        </thead>
                <tbody id="recentTrades">
                    @forelse($recentTrades as $trade)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="text-sm text-gray-600">{{ $trade->opened_at->format('M d, H:i') }}</td>
                        <td class="font-medium">{{ $trade->formatted_instrument }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $trade->type === 'BUY' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $trade->type }}
                            </span>
                        </td>
                        <td class="font-mono text-sm">{{ number_format($trade->units, 0) }}</td>
                        <td class="font-mono text-sm">{{ number_format($trade->entry_price, 5) }}</td>
                        <td class="font-mono text-sm">{{ number_format($trade->current_price ?? $trade->exit_price ?? $trade->entry_price, 5) }}</td>
                        <td class="font-semibold {{ ($trade->state === 'OPEN' ? $trade->unrealized_pl : $trade->realized_pl) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ ($trade->state === 'OPEN' ? $trade->unrealized_pl : $trade->realized_pl) >= 0 ? '+' : '' }}${{ number_format($trade->state === 'OPEN' ? $trade->unrealized_pl : $trade->realized_pl, 2) }}
                        </td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $trade->state === 'OPEN' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $trade->state }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p>No recent trades</p>
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
    let refreshInterval;
    let equityChart = null;
    let winLossChart = null;
    
    // Initialize charts
    initCharts();
    
    async function loadTradingData() {
        try {
            // Load open trades
            const openResponse = await fetch(`${API_BASE_URL}/trade/open`);
            const openResult = await openResponse.json();
            
            if (openResult.success) {
                const openTrades = openResult.data?.trades || [];
                document.getElementById('openTradesCount').textContent = openTrades.length;
                
                const unrealizedPL = openTrades.reduce((sum, t) => sum + (parseFloat(t.unrealizedPL || 0)), 0);
                document.getElementById('unrealizedPLValue').textContent = unrealizedPL.toFixed(2);
            }

            // Load trade history
            const historyResponse = await fetch(`${API_BASE_URL}/trade/history?count=100`);
            const historyResult = await historyResponse.json();
            
            if (historyResult.success) {
                const trades = historyResult.data?.trades || [];
                document.getElementById('totalTrades').textContent = trades.length;
                
                // Calculate win rate
                const closedTrades = trades.filter(t => t.state === 'CLOSED');
                const winningTrades = closedTrades.filter(t => (t.realizedPL || 0) > 0).length;
                const winRate = closedTrades.length > 0 ? ((winningTrades / closedTrades.length) * 100).toFixed(1) : 0;
                document.getElementById('winRate').textContent = winRate + '%';
                
                // Calculate total profit
                const totalProfit = closedTrades.reduce((sum, t) => sum + (parseFloat(t.realizedPL || 0)), 0);
                const profitEl = document.getElementById('totalProfit');
                profitEl.textContent = '$' + totalProfit.toFixed(2);
                profitEl.className = totalProfit >= 0 
                    ? 'text-3xl font-bold text-green-600 mt-1' 
                    : 'text-3xl font-bold text-red-600 mt-1';
                
                // Update charts
                updateEquityChart(closedTrades);
                updateWinLossChart(closedTrades);
                
                // Display recent trades
                renderRecentTrades(trades.slice(0, 10));
            }
            
            // Update last update time
            document.getElementById('lastUpdate').textContent = new Date().toLocaleTimeString();
        } catch (error) {
            console.error('Error loading trading data:', error);
        }
    }

    function initCharts() {
        // Equity Curve Chart
        const equityCtx = document.getElementById('equityChart');
        if (equityCtx) {
            equityChart = new Chart(equityCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Equity',
                        data: [],
                        borderColor: '#2962FF',
                        backgroundColor: 'rgba(41, 98, 255, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 0,
                        pointHoverRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Win/Loss Chart
        const winLossCtx = document.getElementById('winLossChart');
        if (winLossCtx) {
            winLossChart = new Chart(winLossCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Wins', 'Losses'],
                    datasets: [{
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
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }

    function updateEquityChart(trades) {
        if (!equityChart || trades.length === 0) return;
        
        const timeframe = document.getElementById('equityTimeframe')?.value || '30';
        let filteredTrades = trades;
        
        if (timeframe !== 'all') {
            const days = parseInt(timeframe);
            const cutoffDate = new Date();
            cutoffDate.setDate(cutoffDate.getDate() - days);
            filteredTrades = trades.filter(t => new Date(t.closeTime || t.time) >= cutoffDate);
        }
        
        // Calculate cumulative equity
        let cumulativePL = 0;
        const equityData = [];
        const labels = [];
        
        filteredTrades.sort((a, b) => new Date(a.closeTime || a.time) - new Date(b.closeTime || b.time));
        
        filteredTrades.forEach(trade => {
            cumulativePL += parseFloat(trade.realizedPL || 0);
            equityData.push(cumulativePL);
            const date = new Date(trade.closeTime || trade.time);
            labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        });
        
        equityChart.data.labels = labels;
        equityChart.data.datasets[0].data = equityData;
        equityChart.update('none');
    }

    function updateWinLossChart(trades) {
        if (!winLossChart || trades.length === 0) return;
        
        const wins = trades.filter(t => (t.realizedPL || 0) > 0).length;
        const losses = trades.filter(t => (t.realizedPL || 0) < 0).length;
        
        winLossChart.data.datasets[0].data = [wins, losses];
        winLossChart.update('none');
    }

    function renderRecentTrades(trades) {
        const tbody = document.getElementById('recentTrades');
        
        if (trades.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p>No recent trades</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = trades.map(trade => {
            const pl = parseFloat(trade.realizedPL || trade.unrealizedPL || 0);
            const plClass = pl >= 0 ? 'text-green-600' : 'text-red-600';
            const status = trade.state === 'OPEN' ? 'Open' : 'Closed';
            const statusColor = trade.state === 'OPEN' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800';
            const time = new Date(trade.openTime || trade.time);
            const currentPrice = trade.currentPrice || trade.exitPrice || trade.openPrice;

            return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="text-sm text-gray-600">${time.toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                    <td class="font-medium">${(trade.instrument || '').replace('_', '/')}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs font-medium ${trade.type === 'BUY' || trade.currentUnits > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${trade.type || (trade.currentUnits > 0 ? 'BUY' : 'SELL')}
                        </span>
                    </td>
                    <td class="font-mono text-sm">${Math.abs(trade.currentUnits || trade.units || 0).toLocaleString()}</td>
                    <td class="font-mono text-sm">${parseFloat(trade.openPrice || 0).toFixed(5)}</td>
                    <td class="font-mono text-sm">${parseFloat(currentPrice || 0).toFixed(5)}</td>
                    <td class="font-semibold ${plClass}">${pl >= 0 ? '+' : ''}$${pl.toFixed(2)}</td>
                    <td><span class="px-2 py-1 rounded text-xs font-medium ${statusColor}">${status}</span></td>
                </tr>
            `;
        }).join('');
    }

    // Equity timeframe change
    document.getElementById('equityTimeframe')?.addEventListener('change', function() {
        loadTradingData();
    });

    // Initial load
    loadTradingData();
    
    // Auto-refresh every 15 seconds
    refreshInterval = setInterval(loadTradingData, 15000);
    
    // Manual refresh button
    document.getElementById('refreshData').addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Refreshing...';
        loadTradingData().finally(() => {
            this.disabled = false;
            this.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Refresh';
        });
    });
});
</script>
@endpush
@endsection

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Win Rate</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1" id="winRate">{{ number_format($winRate, 1) }}%</p>
                    <p class="text-xs text-gray-500 mt-1">Success Rate</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
        <div>
                    <p class="text-sm text-gray-600">Total Trades</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="totalTrades">{{ $totalTrades }}</p>
                    <p class="text-xs text-gray-500 mt-1">All Time</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Equity Curve Chart -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Equity Curve</h3>
                <select id="equityTimeframe" class="form-input text-xs">
                    <option value="7">Last 7 Days</option>
                    <option value="30" selected>Last 30 Days</option>
                    <option value="90">Last 90 Days</option>
                    <option value="all">All Time</option>
                </select>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="equityChart"></canvas>
            </div>
        </div>

        <!-- Win/Loss Distribution -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Win/Loss Distribution</h3>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="winLossChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('trading.open-trades') }}" class="card hover:shadow-xl transition-all transform hover:-translate-y-2 border-2 border-transparent hover:border-blue-200">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 text-lg">Open Trades</h3>
                    <p class="text-sm text-gray-600">View and manage active positions</p>
                </div>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <a href="{{ route('trading.history') }}" class="card hover:shadow-xl transition-all transform hover:-translate-y-2 border-2 border-transparent hover:border-green-200">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 text-lg">Trade History</h3>
                    <p class="text-sm text-gray-600">Review past trading activity</p>
                </div>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <a href="{{ route('trading.manual-entry') }}" class="card hover:shadow-xl transition-all transform hover:-translate-y-2 border-2 border-transparent hover:border-purple-200">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 text-lg">Manual Entry</h3>
                    <p class="text-sm text-gray-600">Place new trades manually</p>
                </div>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>
    </div>

    <!-- Recent Trades -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent Trades</h3>
            <a href="{{ route('trading.history') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center">
                View All
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
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
                        <th>Current/Exit</th>
                        <th>P/L</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="recentTrades">
                    @forelse($recentTrades as $trade)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="text-sm text-gray-600">{{ $trade->opened_at->format('M d, H:i') }}</td>
                        <td class="font-medium">{{ $trade->formatted_instrument }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $trade->type === 'BUY' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $trade->type }}
                            </span>
                        </td>
                        <td class="font-mono text-sm">{{ number_format($trade->units, 0) }}</td>
                        <td class="font-mono text-sm">{{ number_format($trade->entry_price, 5) }}</td>
                        <td class="font-mono text-sm">{{ number_format($trade->current_price ?? $trade->exit_price ?? $trade->entry_price, 5) }}</td>
                        <td class="font-semibold {{ ($trade->state === 'OPEN' ? $trade->unrealized_pl : $trade->realized_pl) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ ($trade->state === 'OPEN' ? $trade->unrealized_pl : $trade->realized_pl) >= 0 ? '+' : '' }}${{ number_format($trade->state === 'OPEN' ? $trade->unrealized_pl : $trade->realized_pl, 2) }}
                        </td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $trade->state === 'OPEN' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $trade->state }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p>No recent trades</p>
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
    let refreshInterval;
    let equityChart = null;
    let winLossChart = null;
    
    // Initialize charts
    initCharts();
    
    async function loadTradingData() {
        try {
            // Load open trades
            const openResponse = await fetch(`${API_BASE_URL}/trade/open`);
            const openResult = await openResponse.json();
            
            if (openResult.success) {
                const openTrades = openResult.data?.trades || [];
                document.getElementById('openTradesCount').textContent = openTrades.length;
                
                const unrealizedPL = openTrades.reduce((sum, t) => sum + (parseFloat(t.unrealizedPL || 0)), 0);
                document.getElementById('unrealizedPLValue').textContent = unrealizedPL.toFixed(2);
            }

            // Load trade history
            const historyResponse = await fetch(`${API_BASE_URL}/trade/history?count=100`);
            const historyResult = await historyResponse.json();
            
            if (historyResult.success) {
                const trades = historyResult.data?.trades || [];
                document.getElementById('totalTrades').textContent = trades.length;
                
                // Calculate win rate
                const closedTrades = trades.filter(t => t.state === 'CLOSED');
                const winningTrades = closedTrades.filter(t => (t.realizedPL || 0) > 0).length;
                const winRate = closedTrades.length > 0 ? ((winningTrades / closedTrades.length) * 100).toFixed(1) : 0;
                document.getElementById('winRate').textContent = winRate + '%';
                
                // Calculate total profit
                const totalProfit = closedTrades.reduce((sum, t) => sum + (parseFloat(t.realizedPL || 0)), 0);
                const profitEl = document.getElementById('totalProfit');
                profitEl.textContent = '$' + totalProfit.toFixed(2);
                profitEl.className = totalProfit >= 0 
                    ? 'text-3xl font-bold text-green-600 mt-1' 
                    : 'text-3xl font-bold text-red-600 mt-1';
                
                // Update charts
                updateEquityChart(closedTrades);
                updateWinLossChart(closedTrades);
                
                // Display recent trades
                renderRecentTrades(trades.slice(0, 10));
            }
            
            // Update last update time
            document.getElementById('lastUpdate').textContent = new Date().toLocaleTimeString();
        } catch (error) {
            console.error('Error loading trading data:', error);
        }
    }

    function initCharts() {
        // Equity Curve Chart
        const equityCtx = document.getElementById('equityChart');
        if (equityCtx) {
            equityChart = new Chart(equityCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Equity',
                        data: [],
                        borderColor: '#2962FF',
                        backgroundColor: 'rgba(41, 98, 255, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 0,
                        pointHoverRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Win/Loss Chart
        const winLossCtx = document.getElementById('winLossChart');
        if (winLossCtx) {
            winLossChart = new Chart(winLossCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Wins', 'Losses'],
                    datasets: [{
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
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }

    function updateEquityChart(trades) {
        if (!equityChart || trades.length === 0) return;
        
        const timeframe = document.getElementById('equityTimeframe')?.value || '30';
        let filteredTrades = trades;
        
        if (timeframe !== 'all') {
            const days = parseInt(timeframe);
            const cutoffDate = new Date();
            cutoffDate.setDate(cutoffDate.getDate() - days);
            filteredTrades = trades.filter(t => new Date(t.closeTime || t.time) >= cutoffDate);
        }
        
        // Calculate cumulative equity
        let cumulativePL = 0;
        const equityData = [];
        const labels = [];
        
        filteredTrades.sort((a, b) => new Date(a.closeTime || a.time) - new Date(b.closeTime || b.time));
        
        filteredTrades.forEach(trade => {
            cumulativePL += parseFloat(trade.realizedPL || 0);
            equityData.push(cumulativePL);
            const date = new Date(trade.closeTime || trade.time);
            labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        });
        
        equityChart.data.labels = labels;
        equityChart.data.datasets[0].data = equityData;
        equityChart.update('none');
    }

    function updateWinLossChart(trades) {
        if (!winLossChart || trades.length === 0) return;
        
        const wins = trades.filter(t => (t.realizedPL || 0) > 0).length;
        const losses = trades.filter(t => (t.realizedPL || 0) < 0).length;
        
        winLossChart.data.datasets[0].data = [wins, losses];
        winLossChart.update('none');
    }

    function renderRecentTrades(trades) {
        const tbody = document.getElementById('recentTrades');
        
        if (trades.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p>No recent trades</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = trades.map(trade => {
            const pl = parseFloat(trade.realizedPL || trade.unrealizedPL || 0);
            const plClass = pl >= 0 ? 'text-green-600' : 'text-red-600';
            const status = trade.state === 'OPEN' ? 'Open' : 'Closed';
            const statusColor = trade.state === 'OPEN' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800';
            const time = new Date(trade.openTime || trade.time);
            const currentPrice = trade.currentPrice || trade.exitPrice || trade.openPrice;

            return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="text-sm text-gray-600">${time.toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                    <td class="font-medium">${(trade.instrument || '').replace('_', '/')}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs font-medium ${trade.type === 'BUY' || trade.currentUnits > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${trade.type || (trade.currentUnits > 0 ? 'BUY' : 'SELL')}
                        </span>
                    </td>
                    <td class="font-mono text-sm">${Math.abs(trade.currentUnits || trade.units || 0).toLocaleString()}</td>
                    <td class="font-mono text-sm">${parseFloat(trade.openPrice || 0).toFixed(5)}</td>
                    <td class="font-mono text-sm">${parseFloat(currentPrice || 0).toFixed(5)}</td>
                    <td class="font-semibold ${plClass}">${pl >= 0 ? '+' : ''}$${pl.toFixed(2)}</td>
                    <td><span class="px-2 py-1 rounded text-xs font-medium ${statusColor}">${status}</span></td>
                </tr>
            `;
        }).join('');
    }

    // Equity timeframe change
    document.getElementById('equityTimeframe')?.addEventListener('change', function() {
        loadTradingData();
    });

    // Initial load
    loadTradingData();
    
    // Auto-refresh every 15 seconds
    refreshInterval = setInterval(loadTradingData, 15000);
    
    // Manual refresh button
    document.getElementById('refreshData').addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Refreshing...';
        loadTradingData().finally(() => {
            this.disabled = false;
            this.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Refresh';
        });
    });
});
</script>
@endpush
@endsection
