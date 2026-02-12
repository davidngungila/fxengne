@extends('layouts.app')

@section('title', 'Strategy Backtesting - FxEngne')
@section('page-title', 'Backtesting')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Strategy Backtesting</h2>
            <p class="text-sm text-gray-600 mt-1">Test your strategies with historical data</p>
        </div>
        <button id="runBacktest" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Run Backtest
        </button>
    </div>

    <!-- Backtest Configuration -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Backtest Configuration</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Strategy</label>
                <select id="backtestStrategy" class="form-input">
                    <option value="ema_crossover">EMA Crossover</option>
                    <option value="rsi_oversold_overbought">RSI Strategy</option>
                    <option value="macd_crossover">MACD Crossover</option>
                    <option value="support_resistance">Support/Resistance</option>
                </select>
            </div>
            <div>
                <label class="form-label">Instrument</label>
                <select id="backtestInstrument" class="form-input">
                    <option value="EUR_USD">EUR/USD</option>
                    <option value="GBP_USD">GBP/USD</option>
                    <option value="USD_JPY">USD/JPY</option>
                    <option value="XAU_USD">XAU/USD</option>
                </select>
            </div>
            <div>
                <label class="form-label">Date From</label>
                <input type="date" id="dateFrom" class="form-input">
            </div>
            <div>
                <label class="form-label">Date To</label>
                <input type="date" id="dateTo" class="form-input">
            </div>
        </div>
    </div>

    <!-- Backtest Results -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Trades</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="backtestTrades">0</p>
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
                    <p class="text-sm text-gray-600">Win Rate</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="backtestWinRate">0%</p>
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
                    <p class="text-sm text-gray-600">Total Profit</p>
                    <p class="text-2xl font-bold mt-1" id="backtestProfit">$0.00</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Profit Factor</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="backtestProfitFactor">0.00</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Equity Curve -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Equity Curve</h3>
        <div class="relative" style="height: 300px;">
            <canvas id="equityChart"></canvas>
        </div>
    </div>

    <!-- Backtest Details -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Backtest Results</h3>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Instrument</th>
                        <th>Type</th>
                        <th>Entry</th>
                        <th>Exit</th>
                        <th>P/L</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody id="backtestResults">
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            <p>Run a backtest to see results</p>
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
    let equityChart = null;

    document.getElementById('runBacktest').addEventListener('click', function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Running...';

        // Simulate backtest
        setTimeout(() => {
            const trades = 45;
            const winRate = 68.5;
            const profit = 1250.00;
            const profitFactor = 2.15;

            document.getElementById('backtestTrades').textContent = trades;
            document.getElementById('backtestWinRate').textContent = winRate + '%';
            document.getElementById('backtestProfit').textContent = '$' + profit.toFixed(2);
            document.getElementById('backtestProfit').className = profit >= 0 
                ? 'text-2xl font-bold text-green-600 mt-1' 
                : 'text-2xl font-bold text-red-600 mt-1';
            document.getElementById('backtestProfitFactor').textContent = profitFactor.toFixed(2);

            // Render equity curve
            renderEquityCurve();
            renderBacktestResults();

            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Run Backtest';
        }, 2000);
    });

    function renderEquityCurve() {
        const ctx = document.getElementById('equityChart');
        if (!ctx) return;

        if (equityChart) {
            equityChart.destroy();
        }

        const labels = Array.from({ length: 30 }, (_, i) => `Day ${i + 1}`);
        const data = Array.from({ length: 30 }, (_, i) => 10000 + (i * 50) + Math.random() * 100);

        equityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Equity',
                    data: data,
                    borderColor: TradingColors.entryExit.takeProfit,
                    backgroundColor: TradingColors.toRgba(TradingColors.entryExit.takeProfit, 0.1),
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
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
                        beginAtZero: false,
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

    function renderBacktestResults() {
        const tbody = document.getElementById('backtestResults');
        // Sample results
        tbody.innerHTML = Array.from({ length: 10 }, (_, i) => {
            const pl = (Math.random() - 0.3) * 100;
            const plClass = pl >= 0 ? 'text-green-600' : 'text-red-600';
            return `
                <tr class="hover:bg-gray-50">
                    <td class="text-sm text-gray-600">2024-01-${String(i + 1).padStart(2, '0')}</td>
                    <td class="font-medium">EUR/USD</td>
                    <td><span class="px-2 py-1 rounded text-xs font-medium ${pl >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${pl >= 0 ? 'BUY' : 'SELL'}</span></td>
                    <td class="font-mono text-sm">1.0850</td>
                    <td class="font-mono text-sm">1.0865</td>
                    <td class="font-semibold ${plClass}">${pl >= 0 ? '+' : ''}$${pl.toFixed(2)}</td>
                    <td class="text-sm text-gray-600">${Math.floor(Math.random() * 24) + 1}h</td>
                </tr>
            `;
        }).join('');
    }
});
</script>
@endpush
@endsection
