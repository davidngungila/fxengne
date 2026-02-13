@extends('layouts.app')

@section('title', 'Dashboard - FXEngine')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header with Quick Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Trading Dashboard</h2>
            <p class="text-sm text-gray-600 mt-1">Welcome back! Here's your trading overview</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('trading.manual-entry') }}" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Trade
            </a>
            <button id="refreshDashboard" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Account Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Account Balance</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="accountBalance">$10,000.00</p>
                    <p class="text-xs text-gray-500 mt-1">Last updated: <span id="lastUpdate">--</span></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Equity</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="equity">$10,250.00</p>
                    <p class="text-xs text-green-600 mt-1 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        +2.5%
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Free Margin</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="freeMargin">$8,500.00</p>
                    <p class="text-xs text-gray-500 mt-1">Margin Used: 15%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Margin Level</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="marginLevel">603.33%</p>
                    <p class="text-xs text-gray-500 mt-1">Status: <span class="text-green-600 font-medium">Safe</span></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.partials.live-market-data')

    <!-- Performance Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Today's P/L</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="todayPL">+$250.00</p>
                    <p class="text-xs text-gray-500 mt-1">5 trades</p>
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
                    <p class="text-2xl font-bold text-purple-600 mt-1" id="winRate">68.5%</p>
                    <p class="text-xs text-gray-500 mt-1">103 total trades</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Profit Factor</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="profitFactor">2.15</p>
                    <p class="text-xs text-gray-500 mt-1">Excellent</p>
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
                    <p class="text-sm text-gray-600">Open Trades</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="openTrades">3</p>
                    <p class="text-xs text-green-600 mt-1">+$150.00 floating</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Equity Curve</h3>
                <select id="equityTimeframe" class="form-input text-sm w-32">
                    <option value="7d">Last 7 Days</option>
                    <option value="30d" selected>Last 30 Days</option>
                    <option value="90d">Last 90 Days</option>
                    <option value="1y">Last Year</option>
                </select>
            </div>
            <div class="relative" style="height: 350px;">
                <canvas id="equityChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Daily Performance</h3>
                <select id="dailyTimeframe" class="form-input text-sm w-32">
                    <option value="7d">Last 7 Days</option>
                    <option value="30d" selected>Last 30 Days</option>
                </select>
            </div>
            <div class="relative" style="height: 350px;">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Win/Loss Distribution</h3>
            <div class="relative" style="height: 250px;">
                <canvas id="winLossChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Growth</h3>
            <div class="relative" style="height: 250px;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance by Pair</h3>
            <div class="relative" style="height: 250px;">
                <canvas id="pairChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Trades -->
        <div class="lg:col-span-2 card">
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
                            <th>Entry</th>
                            <th>Exit</th>
                            <th>P/L</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="recentTrades">
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                <p>Loading recent trades...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="space-y-6">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Weekly P/L</span>
                            <span class="text-lg font-bold text-green-600">+$1,250.00</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Monthly P/L</span>
                            <span class="text-lg font-bold text-green-600">+$3,500.00</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Best Trade</span>
                            <span class="text-lg font-bold text-green-600">+$450.00</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Worst Trade</span>
                            <span class="text-lg font-bold text-red-600">-$120.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Signals -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Active Signals</h3>
                    <a href="{{ route('signals.active') }}" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
                </div>
                <div id="activeSignals" class="space-y-2">
                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">EUR/USD</p>
                                <p class="text-xs text-gray-600">BUY Signal</p>
                            </div>
                            <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">85%</span>
                        </div>
                    </div>
                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">GBP/USD</p>
                                <p class="text-xs text-gray-600">SELL Signal</p>
                            </div>
                            <span class="px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">72%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Risk Metrics</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Sharpe Ratio</span>
                    <span class="font-semibold text-gray-900">1.85</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Max Drawdown</span>
                    <span class="font-semibold text-red-600">-8.5%</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Avg R:R Ratio</span>
                    <span class="font-semibold text-gray-900">1:2.5</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Risk per Trade</span>
                    <span class="font-semibold text-gray-900">1.0%</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Trading Activity</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Trades</span>
                    <span class="font-semibold text-gray-900">103</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Winning Trades</span>
                    <span class="font-semibold text-green-600">71</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Losing Trades</span>
                    <span class="font-semibold text-red-600">32</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Avg Trade Duration</span>
                    <span class="font-semibold text-gray-900">4.5h</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('trading.manual-entry') }}" class="block w-full btn btn-primary text-center">New Trade</a>
                <a href="{{ route('signals.active') }}" class="block w-full btn btn-secondary text-center">View Signals</a>
                <a href="{{ route('analytics.performance') }}" class="block w-full btn btn-secondary text-center">Analytics</a>
                <a href="{{ route('risk.index') }}" class="block w-full btn btn-secondary text-center">Risk Management</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Live Market Data Functions
let previousPrices = {};

// Load live market prices
async function loadLiveMarketData() {
    const API_BASE_URL = window.API_BASE_URL || '{{ url("/api") }}';
    
    try {
        const instruments = [
            'XAU_USD', 'EUR_USD', 'GBP_USD', 'USD_JPY',
            'AUD_USD', 'USD_CAD', 'USD_CHF', 'NZD_USD',
            'EUR_GBP', 'EUR_JPY'
        ];

        const response = await fetch(`${API_BASE_URL}/market/prices?instruments=${instruments.join(',')}`);
        const result = await response.json();

        if (result.success && result.data && result.data.length > 0) {
            result.data.forEach(price => {
                updateMarketPrice(price);
            });
            
            updateMarketMovers(result.data);
            
            const statusEl = document.getElementById('marketDataStatus');
            if (statusEl) {
                statusEl.textContent = 'Live';
                const indicator = statusEl.previousElementSibling;
                if (indicator) {
                    indicator.classList.remove('bg-red-500');
                    indicator.classList.add('bg-green-500');
                }
            }
        }
    } catch (error) {
        console.error('Error loading market data:', error);
        const statusEl = document.getElementById('marketDataStatus');
        if (statusEl) {
            statusEl.textContent = 'Offline';
            const indicator = statusEl.previousElementSibling;
            if (indicator) {
                indicator.classList.remove('bg-green-500');
                indicator.classList.add('bg-red-500');
            }
        }
    }
}

function updateMarketPrice(priceData) {
    if (!priceData || !priceData.instrument) return;
    const instrument = priceData.instrument.replace('_', '').toLowerCase();
    const bid = parseFloat(priceData.bids?.[0]?.price || 0);
    const ask = parseFloat(priceData.asks?.[0]?.price || 0);
    const mid = (bid + ask) / 2;
    if (bid === 0 || ask === 0) return;
    const isGold = instrument.includes('xau');
    const decimals = isGold ? 2 : 5;
    const spreadMultiplier = isGold ? 100 : 10000;
    const bidEl = document.getElementById(`${instrument}bid`);
    const askEl = document.getElementById(`${instrument}ask`);
    const spreadEl = document.getElementById(`${instrument}spread`);
    const changeEl = document.getElementById(`${instrument}change`);
    if (bidEl) {
        const prevBid = parseFloat(bidEl.textContent.replace(/[^0-9.]/g, '')) || bid;
        bidEl.textContent = bid.toFixed(decimals);
        if (prevBid !== bid && prevBid !== 0) {
            bidEl.classList.add('animate-pulse');
            setTimeout(() => bidEl.classList.remove('animate-pulse'), 500);
        }
    }
    if (askEl) {
        const prevAsk = parseFloat(askEl.textContent.replace(/[^0-9.]/g, '')) || ask;
        askEl.textContent = ask.toFixed(decimals);
        if (prevAsk !== ask && prevAsk !== 0) {
            askEl.classList.add('animate-pulse');
            setTimeout(() => askEl.classList.remove('animate-pulse'), 500);
        }
    }
    if (spreadEl) {
        const spreadPips = ((ask - bid) * spreadMultiplier).toFixed(1);
        spreadEl.textContent = isGold ? `$${spreadPips}` : `${spreadPips} pips`;
    }
    if (changeEl && previousPrices[instrument]) {
        const prevMid = previousPrices[instrument];
        const change = mid - prevMid;
        const changePercent = (change / prevMid) * 100;
        const isPositive = change >= 0;
        changeEl.textContent = `${isPositive ? '+' : ''}${changePercent.toFixed(2)}%`;
        changeEl.className = `text-xs font-semibold px-2 py-1 rounded ${isPositive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
    } else if (changeEl) {
        changeEl.textContent = '--';
        changeEl.className = 'text-xs font-semibold px-2 py-1 rounded bg-gray-100 text-gray-600';
    }
    previousPrices[instrument] = mid;
}

function updateMarketMovers(prices) {
    const container = document.getElementById('marketMovers');
    if (!container) return;
    const movers = prices.map(price => {
        const instrument = price.instrument.replace('_', '/');
        const bid = parseFloat(price.bids?.[0]?.price || 0);
        const ask = parseFloat(price.asks?.[0]?.price || 0);
        const mid = (bid + ask) / 2;
        const instKey = price.instrument.replace('_', '').toLowerCase();
        const prevMid = previousPrices[instKey] || mid;
        const change = mid - prevMid;
        const changePercent = prevMid > 0 ? (change / prevMid) * 100 : 0;
        return { instrument, change, changePercent, mid };
    }).filter(m => Math.abs(m.changePercent) > 0.01)
      .sort((a, b) => Math.abs(b.changePercent) - Math.abs(a.changePercent))
      .slice(0, 5);
    if (movers.length === 0) {
        container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No significant movements</p>';
        return;
    }
    container.innerHTML = movers.map(mover => {
        const isPositive = mover.changePercent >= 0;
        const colorClass = isPositive ? 'text-green-600' : 'text-red-600';
        const bgColorClass = isPositive ? 'bg-green-500' : 'bg-red-500';
        const sign = isPositive ? '+' : '';
        return `<div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 ${bgColorClass} rounded-full"></div>
                <div>
                    <p class="font-medium text-sm text-gray-900">${mover.instrument}</p>
                    <p class="text-xs text-gray-600">${sign}${mover.changePercent.toFixed(2)}%</p>
                </div>
            </div>
            <span class="font-bold ${colorClass}">${sign}${mover.change.toFixed(5)}</span>
        </div>`;
    }).join('');
}

document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';
    window.API_BASE_URL = API_BASE_URL;
    let equityChart, dailyChart, winLossChart, monthlyChart, pairChart;

    // Update last update time
    function updateLastUpdate() {
        document.getElementById('lastUpdate').textContent = new Date().toLocaleTimeString();
    }
    updateLastUpdate();
    setInterval(updateLastUpdate, 60000);

    // Load dashboard data
    async function loadDashboardData() {
        try {
            // Load account summary
            const accountResponse = await fetch(`${API_BASE_URL}/market/account-summary`);
            const accountResult = await accountResponse.json();
            
            if (accountResult.success && accountResult.data) {
                const account = accountResult.data;
                document.getElementById('accountBalance').textContent = '$' + parseFloat(account.balance || 10000).toFixed(2);
                document.getElementById('equity').textContent = '$' + parseFloat(account.equity || 10250).toFixed(2);
                document.getElementById('freeMargin').textContent = '$' + parseFloat(account.marginAvailable || 8500).toFixed(2);
                const marginLevel = account.marginLevel || 603.33;
                document.getElementById('marginLevel').textContent = marginLevel.toFixed(2) + '%';
            }

            // Load recent trades
            const tradesResponse = await fetch(`${API_BASE_URL}/trade/history?limit=5`);
            const tradesResult = await tradesResponse.json();
            
            if (tradesResult.success) {
                renderRecentTrades(tradesResult.data?.trades || []);
            }

            // Load active signals
            const signalsResponse = await fetch(`${API_BASE_URL}/signals/active`);
            const signalsResult = await signalsResponse.json();
            
            if (signalsResult.success) {
                renderActiveSignals(signalsResult.data || []);
            }
        } catch (error) {
            console.error('Error loading dashboard data:', error);
        }
    }

    function renderRecentTrades(trades) {
        const tbody = document.getElementById('recentTrades');
        
        if (trades.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-500"><p>No recent trades</p></td></tr>';
            return;
        }

        tbody.innerHTML = trades.map(trade => {
            const pl = parseFloat(trade.realizedPL || 0);
            const plClass = pl >= 0 ? 'text-green-600' : 'text-red-600';
            const type = trade.currentUnits > 0 ? 'BUY' : 'SELL';
            const typeColor = type === 'BUY' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';

            return `
                <tr class="hover:bg-gray-50">
                    <td class="text-sm text-gray-600">${new Date(trade.openTime || trade.time).toLocaleString()}</td>
                    <td class="font-medium">${trade.instrument?.replace('_', '/') || 'N/A'}</td>
                    <td><span class="px-2 py-1 rounded text-xs font-medium ${typeColor}">${type}</span></td>
                    <td class="font-mono text-sm">${parseFloat(trade.openPrice || 0).toFixed(5)}</td>
                    <td class="font-mono text-sm">${trade.closePrice ? parseFloat(trade.closePrice).toFixed(5) : '--'}</td>
                    <td class="font-semibold ${plClass}">${pl >= 0 ? '+' : ''}$${pl.toFixed(2)}</td>
                    <td><span class="px-2 py-1 rounded text-xs font-medium ${trade.state === 'OPEN' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}">${trade.state === 'OPEN' ? 'Open' : 'Closed'}</span></td>
                </tr>
            `;
        }).join('');
    }

    function renderActiveSignals(signals) {
        const container = document.getElementById('activeSignals');
        const displaySignals = signals.slice(0, 2);
        
        if (displaySignals.length === 0) {
            container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No active signals</p>';
            return;
        }

        container.innerHTML = displaySignals.map(signal => {
            const strength = signal.strength || 0;
            const strengthColor = strength >= 80 ? 'bg-green-100 text-green-800' : 
                                 strength >= 60 ? 'bg-yellow-100 text-yellow-800' : 
                                 'bg-gray-100 text-gray-800';
            const bgColor = signal.type === 'BUY' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';

            return `
                <div class="p-3 ${bgColor} border rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900 text-sm">${signal.instrument?.replace('_', '/') || 'N/A'}</p>
                            <p class="text-xs text-gray-600">${signal.type || 'N/A'} Signal</p>
                        </div>
                        <span class="px-2 py-1 rounded text-xs font-medium ${strengthColor}">${strength}%</span>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Initialize charts
    function initCharts() {
        const days = [];
        const equityData = [];
        const dailyPL = [];
        let baseEquity = 10000;
        
        for (let i = 29; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            days.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            
            const pl = Math.floor(Math.random() * 300) - 100;
            baseEquity += pl;
            equityData.push(baseEquity);
            dailyPL.push(pl);
        }

        // Equity Curve Chart
        const equityCtx = document.getElementById('equityChart');
        if (equityCtx) {
            equityChart = new Chart(equityCtx, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Equity',
                        data: equityData,
                        borderColor: TradingColors.entryExit.takeProfit,
                        backgroundColor: TradingColors.toRgba(TradingColors.entryExit.takeProfit, 0.1),
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
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Equity: $' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: { display: true, grid: { display: false } },
                        y: {
                            beginAtZero: false,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        }

        // Daily Performance Chart
        const dailyCtx = document.getElementById('dailyChart');
        if (dailyCtx) {
            dailyChart = new Chart(dailyCtx, {
                type: 'bar',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'P/L',
                        data: dailyPL,
                        backgroundColor: function(context) {
                            return context.parsed.y >= 0 
                                ? TradingColors.toRgba(TradingColors.candles.bullish, 0.8)
                                : TradingColors.toRgba(TradingColors.candles.bearish, 0.8);
                        },
                        borderColor: function(context) {
                            return context.parsed.y >= 0 
                                ? TradingColors.candles.bullish
                                : TradingColors.candles.bearish;
                        },
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.y;
                                    return 'P/L: ' + (value >= 0 ? '+' : '') + '$' + value.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: { display: true, grid: { display: false } },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
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
                        data: [68.5, 31.5],
                        backgroundColor: [
                            TradingColors.toRgba(TradingColors.candles.bullish, 0.8),
                            TradingColors.toRgba(TradingColors.candles.bearish, 0.8)
                        ],
                        borderColor: [
                            TradingColors.candles.bullish,
                            TradingColors.candles.bearish
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 15, font: { size: 12 } }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Monthly Chart
        const monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            const monthlyData = [10000, 10200, 10500, 10300, 10800, 11200];
            
            monthlyChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Monthly Equity',
                        data: monthlyData,
                        borderColor: TradingColors.movingAverages.ema21,
                        backgroundColor: TradingColors.toRgba(TradingColors.movingAverages.ema21, 0.1),
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Equity: $' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            beginAtZero: false,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        // Pair Performance Chart
        const pairCtx = document.getElementById('pairChart');
        if (pairCtx) {
            pairChart = new Chart(pairCtx, {
                type: 'bar',
                data: {
                    labels: ['EUR/USD', 'GBP/USD', 'XAU/USD'],
                    datasets: [{
                        label: 'Profit',
                        data: [1250, 980, 650],
                        backgroundColor: [
                            TradingColors.toRgba(TradingColors.candles.bullish, 0.8),
                            TradingColors.toRgba(TradingColors.candles.bullish, 0.8),
                            TradingColors.toRgba(TradingColors.candles.bullish, 0.8)
                        ],
                        borderColor: TradingColors.candles.bullish,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Profit: $' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Refresh button
    document.getElementById('refreshDashboard').addEventListener('click', function() {
        loadDashboardData();
    });

    // Initialize
    initCharts();
    loadDashboardData();
    
    // Load live market data
    if (typeof loadLiveMarketData === 'function') {
        loadLiveMarketData();
        // Auto-refresh market data every 5 seconds
        setInterval(loadLiveMarketData, 5000);
    }
    
    // Market data refresh button
    document.getElementById('refreshMarketData')?.addEventListener('click', function() {
        if (typeof loadLiveMarketData === 'function') {
            loadLiveMarketData();
        }
    });
    
    // Auto-refresh every 30 seconds
    setInterval(loadDashboardData, 30000);
});
</script>
@endpush
@endsection

@section('title', 'Dashboard - FXEngine')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header with Quick Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Trading Dashboard</h2>
            <p class="text-sm text-gray-600 mt-1">Welcome back! Here's your trading overview</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('trading.manual-entry') }}" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Trade
            </a>
            <button id="refreshDashboard" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Account Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Account Balance</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="accountBalance">$10,000.00</p>
                    <p class="text-xs text-gray-500 mt-1">Last updated: <span id="lastUpdate">--</span></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Equity</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="equity">$10,250.00</p>
                    <p class="text-xs text-green-600 mt-1 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        +2.5%
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Free Margin</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="freeMargin">$8,500.00</p>
                    <p class="text-xs text-gray-500 mt-1">Margin Used: 15%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Margin Level</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="marginLevel">603.33%</p>
                    <p class="text-xs text-gray-500 mt-1">Status: <span class="text-green-600 font-medium">Safe</span></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.partials.live-market-data')

    <!-- Performance Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Today's P/L</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="todayPL">+$250.00</p>
                    <p class="text-xs text-gray-500 mt-1">5 trades</p>
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
                    <p class="text-2xl font-bold text-purple-600 mt-1" id="winRate">68.5%</p>
                    <p class="text-xs text-gray-500 mt-1">103 total trades</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Profit Factor</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="profitFactor">2.15</p>
                    <p class="text-xs text-gray-500 mt-1">Excellent</p>
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
                    <p class="text-sm text-gray-600">Open Trades</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="openTrades">3</p>
                    <p class="text-xs text-green-600 mt-1">+$150.00 floating</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Equity Curve</h3>
                <select id="equityTimeframe" class="form-input text-sm w-32">
                    <option value="7d">Last 7 Days</option>
                    <option value="30d" selected>Last 30 Days</option>
                    <option value="90d">Last 90 Days</option>
                    <option value="1y">Last Year</option>
                </select>
            </div>
            <div class="relative" style="height: 350px;">
                <canvas id="equityChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Daily Performance</h3>
                <select id="dailyTimeframe" class="form-input text-sm w-32">
                    <option value="7d">Last 7 Days</option>
                    <option value="30d" selected>Last 30 Days</option>
                </select>
            </div>
            <div class="relative" style="height: 350px;">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Win/Loss Distribution</h3>
            <div class="relative" style="height: 250px;">
                <canvas id="winLossChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Growth</h3>
            <div class="relative" style="height: 250px;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance by Pair</h3>
            <div class="relative" style="height: 250px;">
                <canvas id="pairChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Trades -->
        <div class="lg:col-span-2 card">
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
                            <th>Entry</th>
                            <th>Exit</th>
                            <th>P/L</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="recentTrades">
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                <p>Loading recent trades...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="space-y-6">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Weekly P/L</span>
                            <span class="text-lg font-bold text-green-600">+$1,250.00</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Monthly P/L</span>
                            <span class="text-lg font-bold text-green-600">+$3,500.00</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Best Trade</span>
                            <span class="text-lg font-bold text-green-600">+$450.00</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Worst Trade</span>
                            <span class="text-lg font-bold text-red-600">-$120.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Signals -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Active Signals</h3>
                    <a href="{{ route('signals.active') }}" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
                </div>
                <div id="activeSignals" class="space-y-2">
                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">EUR/USD</p>
                                <p class="text-xs text-gray-600">BUY Signal</p>
                            </div>
                            <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">85%</span>
                        </div>
                    </div>
                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">GBP/USD</p>
                                <p class="text-xs text-gray-600">SELL Signal</p>
                            </div>
                            <span class="px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">72%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Risk Metrics</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Sharpe Ratio</span>
                    <span class="font-semibold text-gray-900">1.85</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Max Drawdown</span>
                    <span class="font-semibold text-red-600">-8.5%</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Avg R:R Ratio</span>
                    <span class="font-semibold text-gray-900">1:2.5</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Risk per Trade</span>
                    <span class="font-semibold text-gray-900">1.0%</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Trading Activity</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Trades</span>
                    <span class="font-semibold text-gray-900">103</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Winning Trades</span>
                    <span class="font-semibold text-green-600">71</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Losing Trades</span>
                    <span class="font-semibold text-red-600">32</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Avg Trade Duration</span>
                    <span class="font-semibold text-gray-900">4.5h</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('trading.manual-entry') }}" class="block w-full btn btn-primary text-center">New Trade</a>
                <a href="{{ route('signals.active') }}" class="block w-full btn btn-secondary text-center">View Signals</a>
                <a href="{{ route('analytics.performance') }}" class="block w-full btn btn-secondary text-center">Analytics</a>
                <a href="{{ route('risk.index') }}" class="block w-full btn btn-secondary text-center">Risk Management</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Live Market Data Functions
let previousPrices = {};

// Load live market prices
async function loadLiveMarketData() {
    const API_BASE_URL = window.API_BASE_URL || '{{ url("/api") }}';
    
    try {
        const instruments = [
            'XAU_USD', 'EUR_USD', 'GBP_USD', 'USD_JPY',
            'AUD_USD', 'USD_CAD', 'USD_CHF', 'NZD_USD',
            'EUR_GBP', 'EUR_JPY'
        ];

        const response = await fetch(`${API_BASE_URL}/market/prices?instruments=${instruments.join(',')}`);
        const result = await response.json();

        if (result.success && result.data && result.data.length > 0) {
            result.data.forEach(price => {
                updateMarketPrice(price);
            });
            
            updateMarketMovers(result.data);
            
            const statusEl = document.getElementById('marketDataStatus');
            if (statusEl) {
                statusEl.textContent = 'Live';
                const indicator = statusEl.previousElementSibling;
                if (indicator) {
                    indicator.classList.remove('bg-red-500');
                    indicator.classList.add('bg-green-500');
                }
            }
        }
    } catch (error) {
        console.error('Error loading market data:', error);
        const statusEl = document.getElementById('marketDataStatus');
        if (statusEl) {
            statusEl.textContent = 'Offline';
            const indicator = statusEl.previousElementSibling;
            if (indicator) {
                indicator.classList.remove('bg-green-500');
                indicator.classList.add('bg-red-500');
            }
        }
    }
}

function updateMarketPrice(priceData) {
    if (!priceData || !priceData.instrument) return;
    const instrument = priceData.instrument.replace('_', '').toLowerCase();
    const bid = parseFloat(priceData.bids?.[0]?.price || 0);
    const ask = parseFloat(priceData.asks?.[0]?.price || 0);
    const mid = (bid + ask) / 2;
    if (bid === 0 || ask === 0) return;
    const isGold = instrument.includes('xau');
    const decimals = isGold ? 2 : 5;
    const spreadMultiplier = isGold ? 100 : 10000;
    const bidEl = document.getElementById(`${instrument}bid`);
    const askEl = document.getElementById(`${instrument}ask`);
    const spreadEl = document.getElementById(`${instrument}spread`);
    const changeEl = document.getElementById(`${instrument}change`);
    if (bidEl) {
        const prevBid = parseFloat(bidEl.textContent.replace(/[^0-9.]/g, '')) || bid;
        bidEl.textContent = bid.toFixed(decimals);
        if (prevBid !== bid && prevBid !== 0) {
            bidEl.classList.add('animate-pulse');
            setTimeout(() => bidEl.classList.remove('animate-pulse'), 500);
        }
    }
    if (askEl) {
        const prevAsk = parseFloat(askEl.textContent.replace(/[^0-9.]/g, '')) || ask;
        askEl.textContent = ask.toFixed(decimals);
        if (prevAsk !== ask && prevAsk !== 0) {
            askEl.classList.add('animate-pulse');
            setTimeout(() => askEl.classList.remove('animate-pulse'), 500);
        }
    }
    if (spreadEl) {
        const spreadPips = ((ask - bid) * spreadMultiplier).toFixed(1);
        spreadEl.textContent = isGold ? `$${spreadPips}` : `${spreadPips} pips`;
    }
    if (changeEl && previousPrices[instrument]) {
        const prevMid = previousPrices[instrument];
        const change = mid - prevMid;
        const changePercent = (change / prevMid) * 100;
        const isPositive = change >= 0;
        changeEl.textContent = `${isPositive ? '+' : ''}${changePercent.toFixed(2)}%`;
        changeEl.className = `text-xs font-semibold px-2 py-1 rounded ${isPositive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
    } else if (changeEl) {
        changeEl.textContent = '--';
        changeEl.className = 'text-xs font-semibold px-2 py-1 rounded bg-gray-100 text-gray-600';
    }
    previousPrices[instrument] = mid;
}

function updateMarketMovers(prices) {
    const container = document.getElementById('marketMovers');
    if (!container) return;
    const movers = prices.map(price => {
        const instrument = price.instrument.replace('_', '/');
        const bid = parseFloat(price.bids?.[0]?.price || 0);
        const ask = parseFloat(price.asks?.[0]?.price || 0);
        const mid = (bid + ask) / 2;
        const instKey = price.instrument.replace('_', '').toLowerCase();
        const prevMid = previousPrices[instKey] || mid;
        const change = mid - prevMid;
        const changePercent = prevMid > 0 ? (change / prevMid) * 100 : 0;
        return { instrument, change, changePercent, mid };
    }).filter(m => Math.abs(m.changePercent) > 0.01)
      .sort((a, b) => Math.abs(b.changePercent) - Math.abs(a.changePercent))
      .slice(0, 5);
    if (movers.length === 0) {
        container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No significant movements</p>';
        return;
    }
    container.innerHTML = movers.map(mover => {
        const isPositive = mover.changePercent >= 0;
        const colorClass = isPositive ? 'text-green-600' : 'text-red-600';
        const bgColorClass = isPositive ? 'bg-green-500' : 'bg-red-500';
        const sign = isPositive ? '+' : '';
        return `<div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 ${bgColorClass} rounded-full"></div>
                <div>
                    <p class="font-medium text-sm text-gray-900">${mover.instrument}</p>
                    <p class="text-xs text-gray-600">${sign}${mover.changePercent.toFixed(2)}%</p>
                </div>
            </div>
            <span class="font-bold ${colorClass}">${sign}${mover.change.toFixed(5)}</span>
        </div>`;
    }).join('');
}

document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';
    window.API_BASE_URL = API_BASE_URL;
    let equityChart, dailyChart, winLossChart, monthlyChart, pairChart;

    // Update last update time
    function updateLastUpdate() {
        document.getElementById('lastUpdate').textContent = new Date().toLocaleTimeString();
    }
    updateLastUpdate();
    setInterval(updateLastUpdate, 60000);

    // Load dashboard data
    async function loadDashboardData() {
        try {
            // Load account summary
            const accountResponse = await fetch(`${API_BASE_URL}/market/account-summary`);
            const accountResult = await accountResponse.json();
            
            if (accountResult.success && accountResult.data) {
                const account = accountResult.data;
                document.getElementById('accountBalance').textContent = '$' + parseFloat(account.balance || 10000).toFixed(2);
                document.getElementById('equity').textContent = '$' + parseFloat(account.equity || 10250).toFixed(2);
                document.getElementById('freeMargin').textContent = '$' + parseFloat(account.marginAvailable || 8500).toFixed(2);
                const marginLevel = account.marginLevel || 603.33;
                document.getElementById('marginLevel').textContent = marginLevel.toFixed(2) + '%';
            }

            // Load recent trades
            const tradesResponse = await fetch(`${API_BASE_URL}/trade/history?limit=5`);
            const tradesResult = await tradesResponse.json();
            
            if (tradesResult.success) {
                renderRecentTrades(tradesResult.data?.trades || []);
            }

            // Load active signals
            const signalsResponse = await fetch(`${API_BASE_URL}/signals/active`);
            const signalsResult = await signalsResponse.json();
            
            if (signalsResult.success) {
                renderActiveSignals(signalsResult.data || []);
            }
        } catch (error) {
            console.error('Error loading dashboard data:', error);
        }
    }

    function renderRecentTrades(trades) {
        const tbody = document.getElementById('recentTrades');
        
        if (trades.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-500"><p>No recent trades</p></td></tr>';
            return;
        }

        tbody.innerHTML = trades.map(trade => {
            const pl = parseFloat(trade.realizedPL || 0);
            const plClass = pl >= 0 ? 'text-green-600' : 'text-red-600';
            const type = trade.currentUnits > 0 ? 'BUY' : 'SELL';
            const typeColor = type === 'BUY' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';

            return `
                <tr class="hover:bg-gray-50">
                    <td class="text-sm text-gray-600">${new Date(trade.openTime || trade.time).toLocaleString()}</td>
                    <td class="font-medium">${trade.instrument?.replace('_', '/') || 'N/A'}</td>
                    <td><span class="px-2 py-1 rounded text-xs font-medium ${typeColor}">${type}</span></td>
                    <td class="font-mono text-sm">${parseFloat(trade.openPrice || 0).toFixed(5)}</td>
                    <td class="font-mono text-sm">${trade.closePrice ? parseFloat(trade.closePrice).toFixed(5) : '--'}</td>
                    <td class="font-semibold ${plClass}">${pl >= 0 ? '+' : ''}$${pl.toFixed(2)}</td>
                    <td><span class="px-2 py-1 rounded text-xs font-medium ${trade.state === 'OPEN' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}">${trade.state === 'OPEN' ? 'Open' : 'Closed'}</span></td>
                </tr>
            `;
        }).join('');
    }

    function renderActiveSignals(signals) {
        const container = document.getElementById('activeSignals');
        const displaySignals = signals.slice(0, 2);
        
        if (displaySignals.length === 0) {
            container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No active signals</p>';
            return;
        }

        container.innerHTML = displaySignals.map(signal => {
            const strength = signal.strength || 0;
            const strengthColor = strength >= 80 ? 'bg-green-100 text-green-800' : 
                                 strength >= 60 ? 'bg-yellow-100 text-yellow-800' : 
                                 'bg-gray-100 text-gray-800';
            const bgColor = signal.type === 'BUY' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';

            return `
                <div class="p-3 ${bgColor} border rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900 text-sm">${signal.instrument?.replace('_', '/') || 'N/A'}</p>
                            <p class="text-xs text-gray-600">${signal.type || 'N/A'} Signal</p>
                        </div>
                        <span class="px-2 py-1 rounded text-xs font-medium ${strengthColor}">${strength}%</span>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Initialize charts
    function initCharts() {
        const days = [];
        const equityData = [];
        const dailyPL = [];
        let baseEquity = 10000;
        
        for (let i = 29; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            days.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            
            const pl = Math.floor(Math.random() * 300) - 100;
            baseEquity += pl;
            equityData.push(baseEquity);
            dailyPL.push(pl);
        }

        // Equity Curve Chart
        const equityCtx = document.getElementById('equityChart');
        if (equityCtx) {
            equityChart = new Chart(equityCtx, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Equity',
                        data: equityData,
                        borderColor: TradingColors.entryExit.takeProfit,
                        backgroundColor: TradingColors.toRgba(TradingColors.entryExit.takeProfit, 0.1),
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
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Equity: $' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: { display: true, grid: { display: false } },
                        y: {
                            beginAtZero: false,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        }

        // Daily Performance Chart
        const dailyCtx = document.getElementById('dailyChart');
        if (dailyCtx) {
            dailyChart = new Chart(dailyCtx, {
                type: 'bar',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'P/L',
                        data: dailyPL,
                        backgroundColor: function(context) {
                            return context.parsed.y >= 0 
                                ? TradingColors.toRgba(TradingColors.candles.bullish, 0.8)
                                : TradingColors.toRgba(TradingColors.candles.bearish, 0.8);
                        },
                        borderColor: function(context) {
                            return context.parsed.y >= 0 
                                ? TradingColors.candles.bullish
                                : TradingColors.candles.bearish;
                        },
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.y;
                                    return 'P/L: ' + (value >= 0 ? '+' : '') + '$' + value.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: { display: true, grid: { display: false } },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
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
                        data: [68.5, 31.5],
                        backgroundColor: [
                            TradingColors.toRgba(TradingColors.candles.bullish, 0.8),
                            TradingColors.toRgba(TradingColors.candles.bearish, 0.8)
                        ],
                        borderColor: [
                            TradingColors.candles.bullish,
                            TradingColors.candles.bearish
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 15, font: { size: 12 } }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Monthly Chart
        const monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            const monthlyData = [10000, 10200, 10500, 10300, 10800, 11200];
            
            monthlyChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Monthly Equity',
                        data: monthlyData,
                        borderColor: TradingColors.movingAverages.ema21,
                        backgroundColor: TradingColors.toRgba(TradingColors.movingAverages.ema21, 0.1),
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Equity: $' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            beginAtZero: false,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        // Pair Performance Chart
        const pairCtx = document.getElementById('pairChart');
        if (pairCtx) {
            pairChart = new Chart(pairCtx, {
                type: 'bar',
                data: {
                    labels: ['EUR/USD', 'GBP/USD', 'XAU/USD'],
                    datasets: [{
                        label: 'Profit',
                        data: [1250, 980, 650],
                        backgroundColor: [
                            TradingColors.toRgba(TradingColors.candles.bullish, 0.8),
                            TradingColors.toRgba(TradingColors.candles.bullish, 0.8),
                            TradingColors.toRgba(TradingColors.candles.bullish, 0.8)
                        ],
                        borderColor: TradingColors.candles.bullish,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Profit: $' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Refresh button
    document.getElementById('refreshDashboard').addEventListener('click', function() {
        loadDashboardData();
    });

    // Initialize
    initCharts();
    loadDashboardData();
    
    // Load live market data
    if (typeof loadLiveMarketData === 'function') {
        loadLiveMarketData();
        // Auto-refresh market data every 5 seconds
        setInterval(loadLiveMarketData, 5000);
    }
    
    // Market data refresh button
    document.getElementById('refreshMarketData')?.addEventListener('click', function() {
        if (typeof loadLiveMarketData === 'function') {
            loadLiveMarketData();
        }
    });
    
    // Auto-refresh every 30 seconds
    setInterval(loadDashboardData, 30000);
});
</script>
@endpush
@endsection
