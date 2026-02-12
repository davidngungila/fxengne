@extends('layouts.app')

@section('title', 'Spread Monitor - FxEngne')
@section('page-title', 'Spread Monitor')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Spread Monitor</h2>
            <p class="text-sm text-gray-600 mt-1">Monitor spreads across all currency pairs in real-time</p>
        </div>
        <div class="flex items-center space-x-3">
            <select id="sortBy" class="form-input text-sm">
                <option value="spread">Sort by Spread</option>
                <option value="pair">Sort by Pair</option>
                <option value="change">Sort by Change</option>
            </select>
            <button id="refreshSpreads" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Spread Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Average Spread</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="avgSpread">--</p>
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
                    <p class="text-sm text-gray-600">Tightest Spread</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="tightestSpread">--</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Widest Spread</p>
                    <p class="text-2xl font-bold text-red-600 mt-1" id="widestSpread">--</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pairs Monitored</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="pairsCount">0</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Spread Chart -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Spread Trends</h3>
            <select id="chartPeriod" class="form-input text-sm">
                <option value="1h">Last Hour</option>
                <option value="4h" selected>Last 4 Hours</option>
                <option value="24h">Last 24 Hours</option>
            </select>
        </div>
        <div class="relative" style="height: 300px;">
            <canvas id="spreadChart"></canvas>
        </div>
    </div>

    <!-- Spread Table -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Current Spreads</h3>
            <input type="text" id="searchSpreads" placeholder="Search pairs..." class="form-input w-48 text-sm">
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pair</th>
                        <th>Bid</th>
                        <th>Ask</th>
                        <th>Spread (Pips)</th>
                        <th>Spread %</th>
                        <th>Status</th>
                        <th>Change</th>
                        <th>Last Update</th>
                    </tr>
                </thead>
                <tbody id="spreadData">
                    <!-- Spread data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Spread Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Widest Spreads</h3>
            <div class="space-y-3" id="widestSpreads">
                <!-- Widest spreads will be populated by JavaScript -->
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tightest Spreads</h3>
            <div class="space-y-3" id="tightestSpreads">
                <!-- Tightest spreads will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';
    const OANDA_ENABLED = {{ $oandaEnabled ?? false ? 'true' : 'false' }};
    
    let spreadData = {};
    let spreadHistory = [];
    let spreadChart = null;

    // Default instruments
    const instruments = [
        'EUR_USD', 'GBP_USD', 'USD_JPY', 'USD_CHF', 'AUD_USD', 'USD_CAD', 'NZD_USD',
        'EUR_GBP', 'EUR_JPY', 'GBP_JPY', 'EUR_CHF', 'AUD_JPY', 'CAD_JPY',
        'XAU_USD', 'XAG_USD', 'USD_ZAR', 'USD_TRY'
    ];

    async function fetchSpreads() {
        if (OANDA_ENABLED) {
            try {
                const response = await fetch(`${API_BASE_URL}/market/prices?instruments=${instruments.join(',')}`);
                const result = await response.json();

                if (result.success && result.data) {
                    result.data.forEach(price => {
                        const instrument = price.instrument.replace('_', '');
                        const bid = parseFloat(price.bids[0].price);
                        const ask = parseFloat(price.asks[0].price);
                        const spread = ask - bid;
                        const spreadPips = spread * 10000; // For most pairs
                        const spreadPercent = (spread / bid) * 100;

                        spreadData[instrument] = {
                            pair: instrument,
                            bid: bid,
                            ask: ask,
                            spread: spread,
                            spreadPips: spreadPips,
                            spreadPercent: spreadPercent,
                            lastUpdate: new Date(price.time || new Date())
                        };
                    });
                }
            } catch (error) {
                console.error('Error fetching spreads:', error);
            }
        }

        // Fallback to simulated data
        if (Object.keys(spreadData).length === 0) {
            generateSimulatedSpreads();
        }

        renderSpreads();
        updateStatistics();
        updateSpreadChart();
    }

    function generateSimulatedSpreads() {
        const pairs = [
            { pair: 'EURUSD', baseSpread: 1.2 },
            { pair: 'GBPUSD', baseSpread: 1.5 },
            { pair: 'USDJPY', baseSpread: 0.8 },
            { pair: 'USDCHF', baseSpread: 1.0 },
            { pair: 'AUDUSD', baseSpread: 1.3 },
            { pair: 'USDCAD', baseSpread: 1.1 },
            { pair: 'NZDUSD', baseSpread: 1.4 },
            { pair: 'EURGBP', baseSpread: 1.6 },
            { pair: 'EURJPY', baseSpread: 1.2 },
            { pair: 'GBPJPY', baseSpread: 1.8 },
            { pair: 'XAUUSD', baseSpread: 0.25 },
            { pair: 'XAGUSD', baseSpread: 0.03 }
        ];

        pairs.forEach(p => {
            const variation = (Math.random() - 0.5) * 0.3;
            const spread = Math.max(0.1, p.baseSpread + variation);
            const bid = p.pair === 'XAUUSD' ? 2650 : (p.pair === 'XAGUSD' ? 30 : 1.0);
            const ask = bid + spread;

            spreadData[p.pair] = {
                pair: p.pair,
                bid: bid,
                ask: ask,
                spread: spread,
                spreadPips: spread * (p.pair.includes('XAU') || p.pair.includes('XAG') ? 1 : 10000),
                spreadPercent: (spread / bid) * 100,
                lastUpdate: new Date()
            };
        });
    }

    function renderSpreads(filteredData = null) {
        const data = filteredData || Object.values(spreadData);
        const tbody = document.getElementById('spreadData');

        tbody.innerHTML = data.map(item => {
            const statusColor = item.spreadPips < 2 ? 'text-green-600' : 
                              item.spreadPips < 5 ? 'text-yellow-600' : 'text-red-600';
            const statusText = item.spreadPips < 2 ? 'Tight' : 
                              item.spreadPips < 5 ? 'Normal' : 'Wide';

            return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="font-medium">${item.pair}</td>
                    <td class="font-mono">${item.bid.toFixed(5)}</td>
                    <td class="font-mono">${item.ask.toFixed(5)}</td>
                    <td class="font-mono font-semibold ${statusColor}">${item.spreadPips.toFixed(1)}</td>
                    <td class="font-mono text-gray-600">${item.spreadPercent.toFixed(4)}%</td>
                    <td><span class="px-2 py-1 rounded text-xs font-medium ${statusColor.replace('text-', 'bg-').replace('-600', '-100')} ${statusColor}">${statusText}</span></td>
                    <td class="text-sm text-gray-600">--</td>
                    <td class="text-sm text-gray-500">${item.lastUpdate.toLocaleTimeString()}</td>
                </tr>
            `;
        }).join('');
    }

    function updateStatistics() {
        const spreads = Object.values(spreadData).map(s => s.spreadPips);
        if (spreads.length === 0) return;

        const avg = spreads.reduce((a, b) => a + b, 0) / spreads.length;
        const min = Math.min(...spreads);
        const max = Math.max(...spreads);

        document.getElementById('avgSpread').textContent = avg.toFixed(1) + ' pips';
        document.getElementById('tightestSpread').textContent = min.toFixed(1) + ' pips';
        document.getElementById('widestSpread').textContent = max.toFixed(1) + ' pips';
        document.getElementById('pairsCount').textContent = spreads.length;

        // Update widest/tightest lists
        const sorted = Object.values(spreadData).sort((a, b) => b.spreadPips - a.spreadPips);
        document.getElementById('widestSpreads').innerHTML = sorted.slice(0, 5).map(item => `
            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900">${item.pair}</p>
                    <p class="text-sm text-gray-600">${item.spreadPips.toFixed(1)} pips</p>
                </div>
                <span class="text-red-600 font-semibold">${item.spreadPercent.toFixed(4)}%</span>
            </div>
        `).join('');

        document.getElementById('tightestSpreads').innerHTML = sorted.slice(-5).reverse().map(item => `
            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900">${item.pair}</p>
                    <p class="text-sm text-gray-600">${item.spreadPips.toFixed(1)} pips</p>
                </div>
                <span class="text-green-600 font-semibold">${item.spreadPercent.toFixed(4)}%</span>
            </div>
        `).join('');
    }

    function updateSpreadChart() {
        const ctx = document.getElementById('spreadChart');
        if (!ctx) return;

        const data = Object.values(spreadData);
        const labels = data.map(d => d.pair);
        const spreads = data.map(d => d.spreadPips);

        if (spreadChart) {
            spreadChart.destroy();
        }

        spreadChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Spread (Pips)',
                    data: spreads,
                    backgroundColor: spreads.map(s => 
                        s < 2 ? TradingColors.candles.bullish :
                        s < 5 ? TradingColors.movingAverages.ema9 :
                        TradingColors.candles.bearish
                    ),
                    borderColor: spreads.map(s => 
                        s < 2 ? TradingColors.candles.bullish :
                        s < 5 ? TradingColors.movingAverages.ema9 :
                        TradingColors.candles.bearish
                    ),
                    borderWidth: 1
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
                        callbacks: {
                            label: function(context) {
                                const item = data[context.dataIndex];
                                return [
                                    `Spread: ${item.spreadPips.toFixed(1)} pips`,
                                    `Percentage: ${item.spreadPercent.toFixed(4)}%`,
                                    `Bid: ${item.bid.toFixed(5)}`,
                                    `Ask: ${item.ask.toFixed(5)}`
                                ];
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' pips';
                            }
                        }
                    }
                }
            }
        });
    }

    // Search functionality
    document.getElementById('searchSpreads').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const filtered = Object.values(spreadData).filter(item => 
            item.pair.toLowerCase().includes(searchTerm)
        );
        renderSpreads(filtered);
    });

    // Sort functionality
    document.getElementById('sortBy').addEventListener('change', function(e) {
        const sorted = Object.values(spreadData).sort((a, b) => {
            switch(e.target.value) {
                case 'spread':
                    return b.spreadPips - a.spreadPips;
                case 'pair':
                    return a.pair.localeCompare(b.pair);
                default:
                    return 0;
            }
        });
        renderSpreads(sorted);
    });

    document.getElementById('refreshSpreads').addEventListener('click', fetchSpreads);

    // Initial load
    fetchSpreads();

    // Auto-update every 5 seconds
    setInterval(fetchSpreads, 5000);
});
</script>
@endpush
@endsection

