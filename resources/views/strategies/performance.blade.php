@extends('layouts.app')

@section('title', 'Strategy Performance - FXEngine')
@section('page-title', 'Strategy Performance')

@section('content')
<div class="space-y-6">
    <!-- Header with Filters -->
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Strategy Performance Analytics</h2>
            <p class="text-sm text-gray-600 mt-1">Comprehensive analysis and comparison of trading strategies</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <select id="strategyFilter" class="form-input text-sm">
                <option value="all">All Strategies</option>
                <option value="ema_crossover">EMA Crossover</option>
                <option value="rsi_oversold_overbought">RSI Strategy</option>
                <option value="macd_crossover">MACD Crossover</option>
                <option value="bollinger_bands">Bollinger Bands</option>
                <option value="support_resistance">Support/Resistance</option>
            </select>
            <select id="timeFilter" class="form-input text-sm">
                <option value="all">All Time</option>
                <option value="7d">Last 7 Days</option>
                <option value="30d">Last 30 Days</option>
                <option value="90d">Last 90 Days</option>
                <option value="1y">Last Year</option>
            </select>
            <select id="instrumentFilter" class="form-input text-sm">
                <option value="all">All Instruments</option>
                <option value="EUR_USD">EUR/USD</option>
                <option value="GBP_USD">GBP/USD</option>
                <option value="USD_JPY">USD/JPY</option>
                <option value="XAU_USD">XAU/USD</option>
            </select>
            <button id="exportBtn" class="btn btn-secondary text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export
            </button>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card bg-gradient-to-br from-green-50 to-green-100 border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Return</p>
                    <p class="text-3xl font-bold text-green-700 mt-1" id="totalReturn">+15.2%</p>
                    <p class="text-xs text-gray-600 mt-1">vs Market: +12.5%</p>
                </div>
                <div class="w-14 h-14 bg-green-200 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Sharpe Ratio</p>
                    <p class="text-3xl font-bold text-blue-700 mt-1" id="sharpeRatio">1.85</p>
                    <p class="text-xs text-gray-600 mt-1">Risk-adjusted return</p>
                </div>
                <div class="w-14 h-14 bg-blue-200 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-red-50 to-red-100 border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Max Drawdown</p>
                    <p class="text-3xl font-bold text-red-700 mt-1" id="maxDrawdown">-8.5%</p>
                    <p class="text-xs text-gray-600 mt-1">Peak to trough</p>
                </div>
                <div class="w-14 h-14 bg-red-200 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Profit Factor</p>
                    <p class="text-3xl font-bold text-purple-700 mt-1" id="profitFactor">2.15</p>
                    <p class="text-xs text-gray-600 mt-1">Gross profit / Gross loss</p>
                </div>
                <div class="w-14 h-14 bg-purple-200 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="card">
            <p class="text-xs text-gray-600">Win Rate</p>
            <p class="text-2xl font-bold text-gray-900 mt-1" id="winRate">68.5%</p>
        </div>
        <div class="card">
            <p class="text-xs text-gray-600">Total Trades</p>
            <p class="text-2xl font-bold text-gray-900 mt-1" id="totalTrades">103</p>
        </div>
        <div class="card">
            <p class="text-xs text-gray-600">Avg Win</p>
            <p class="text-2xl font-bold text-green-600 mt-1" id="avgWin">$45.20</p>
        </div>
        <div class="card">
            <p class="text-xs text-gray-600">Avg Loss</p>
            <p class="text-2xl font-bold text-red-600 mt-1" id="avgLoss">-$28.50</p>
        </div>
        <div class="card">
            <p class="text-xs text-gray-600">Risk/Reward</p>
            <p class="text-2xl font-bold text-gray-900 mt-1" id="riskReward">1.59</p>
        </div>
    </div>

    <!-- Advanced Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Equity Curve -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Equity Curve</h3>
                <div class="flex items-center space-x-2 text-xs text-gray-600">
                    <span class="flex items-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-1"></span>
                        Equity
                    </span>
                    <span class="flex items-center">
                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-1"></span>
                        Benchmark
                    </span>
                </div>
            </div>
            <div class="relative" style="height: 350px;">
                <canvas id="equityCurveChart"></canvas>
            </div>
        </div>

        <!-- Drawdown Chart -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Drawdown Analysis</h3>
                <span class="text-xs text-gray-600">Peak: $12,500 | Current: $11,450</span>
            </div>
            <div class="relative" style="height: 350px;">
                <canvas id="drawdownChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Advanced Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Monthly Returns -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Returns</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="monthlyReturnsChart"></canvas>
            </div>
        </div>

        <!-- Win/Loss Distribution -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Win/Loss Distribution</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="winLossChart"></canvas>
            </div>
        </div>

        <!-- Performance by Time -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance by Hour</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="hourlyPerformanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Strategy Comparison Table -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Strategy Comparison</h3>
            <div class="flex items-center space-x-2">
                <button id="sortBy" class="text-sm text-gray-600 hover:text-gray-900">Sort by: Total Profit</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('strategy')">
                            Strategy
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('trades')">
                            Total Trades
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('winRate')">
                            Win Rate
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('profit')">
                            Total Profit
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('avgProfit')">
                            Avg Profit
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('drawdown')">
                            Max Drawdown
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('sharpe')">
                            Sharpe Ratio
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('profitFactor')">
                            Profit Factor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody id="strategyTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Performance Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Trade Statistics -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Trade Statistics</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Winning Trades</span>
                    <span class="text-lg font-bold text-green-600" id="winningTrades">71</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Losing Trades</span>
                    <span class="text-lg font-bold text-red-600" id="losingTrades">32</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Largest Win</span>
                    <span class="text-lg font-bold text-green-600" id="largestWin">$185.50</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Largest Loss</span>
                    <span class="text-lg font-bold text-red-600" id="largestLoss">-$95.20</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Consecutive Wins</span>
                    <span class="text-lg font-bold text-green-600" id="consecutiveWins">8</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Consecutive Losses</span>
                    <span class="text-lg font-bold text-red-600" id="consecutiveLosses">3</span>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Advanced Metrics</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Sortino Ratio</span>
                    <span class="text-lg font-bold text-gray-900" id="sortinoRatio">2.12</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Calmar Ratio</span>
                    <span class="text-lg font-bold text-gray-900" id="calmarRatio">1.79</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Expectancy</span>
                    <span class="text-lg font-bold text-gray-900" id="expectancy">$12.45</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Recovery Factor</span>
                    <span class="text-lg font-bold text-gray-900" id="recoveryFactor">1.79</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Average Trade Duration</span>
                    <span class="text-lg font-bold text-gray-900" id="avgDuration">4h 32m</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Profit per Day</span>
                    <span class="text-lg font-bold text-green-600" id="profitPerDay">$45.20</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance by Instrument -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance by Instrument</h3>
        <div class="relative" style="height: 300px;">
            <canvas id="instrumentPerformanceChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample data - Replace with real API calls
    const performanceData = {
        totalReturn: 15.2,
        sharpeRatio: 1.85,
        maxDrawdown: -8.5,
        profitFactor: 2.15,
        winRate: 68.5,
        totalTrades: 103,
        avgWin: 45.20,
        avgLoss: -28.50,
        riskReward: 1.59,
        winningTrades: 71,
        losingTrades: 32,
        largestWin: 185.50,
        largestLoss: -95.20,
        consecutiveWins: 8,
        consecutiveLosses: 3,
        sortinoRatio: 2.12,
        calmarRatio: 1.79,
        expectancy: 12.45,
        recoveryFactor: 1.79,
        avgDuration: '4h 32m',
        profitPerDay: 45.20
    };

    // Equity Curve Chart
    const equityCtx = document.getElementById('equityCurveChart');
    if (equityCtx) {
        const equityData = generateEquityData();
        new Chart(equityCtx, {
            type: 'line',
            data: {
                labels: equityData.labels,
                datasets: [{
                    label: 'Equity',
                    data: equityData.equity,
                    borderColor: TradingColors.candles.bullish,
                    backgroundColor: TradingColors.candles.bullish + '20',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0
                }, {
                    label: 'Benchmark',
                    data: equityData.benchmark,
                    borderColor: TradingColors.secondary,
                    backgroundColor: TradingColors.secondary + '20',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    borderDash: [5, 5]
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
                    x: {
                        display: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        display: true,
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

    // Drawdown Chart
    const drawdownCtx = document.getElementById('drawdownChart');
    if (drawdownCtx) {
        const drawdownData = generateDrawdownData();
        new Chart(drawdownCtx, {
            type: 'bar',
            data: {
                labels: drawdownData.labels,
                datasets: [{
                    label: 'Drawdown',
                    data: drawdownData.values,
                    backgroundColor: function(context) {
                        return context.parsed.y < 0 
                            ? TradingColors.candles.bearish 
                            : TradingColors.candles.bullish;
                    },
                    borderColor: function(context) {
                        return context.parsed.y < 0 
                            ? TradingColors.candles.bearish 
                            : TradingColors.candles.bullish;
                    }
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
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    // Monthly Returns Chart
    const monthlyCtx = document.getElementById('monthlyReturnsChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Monthly Return',
                    data: [2.5, 3.2, -1.5, 4.1, 2.8, 3.9, 1.2, -0.8, 2.5, 3.1, 2.9, 1.8],
                    backgroundColor: function(context) {
                        return context.parsed.y >= 0 
                            ? TradingColors.candles.bullish 
                            : TradingColors.candles.bearish;
                    },
                    borderRadius: 4
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
                        ticks: {
                            callback: function(value) {
                                return value + '%';
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
        new Chart(winLossCtx, {
            type: 'doughnut',
            data: {
                labels: ['Winning', 'Losing'],
                datasets: [{
                    data: [68.5, 31.5],
                    backgroundColor: [
                        TradingColors.candles.bullish,
                        TradingColors.candles.bearish
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

    // Hourly Performance Chart
    const hourlyCtx = document.getElementById('hourlyPerformanceChart');
    if (hourlyCtx) {
        const hours = Array.from({length: 24}, (_, i) => i.toString().padStart(2, '0') + ':00');
        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: hours,
                datasets: [{
                    label: 'Profit',
                    data: generateHourlyData(),
                    backgroundColor: function(context) {
                        return context.parsed.y >= 0 
                            ? TradingColors.candles.bullish 
                            : TradingColors.candles.bearish;
                    },
                    borderRadius: 2
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
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
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

    // Instrument Performance Chart
    const instrumentCtx = document.getElementById('instrumentPerformanceChart');
    if (instrumentCtx) {
        new Chart(instrumentCtx, {
            type: 'bar',
            data: {
                labels: ['EUR/USD', 'GBP/USD', 'USD/JPY', 'XAU/USD', 'AUD/USD'],
                datasets: [{
                    label: 'Profit',
                    data: [1250, 980, 650, 1850, 420],
                    backgroundColor: TradingColors.candles.bullish,
                    borderRadius: 4
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

    // Strategy Comparison Table Data
    const strategies = [
        {
            name: 'EMA Crossover',
            trades: 42,
            winRate: 68.5,
            profit: 1250.00,
            avgProfit: 29.76,
            drawdown: -6.2,
            sharpe: 1.75,
            profitFactor: 2.15
        },
        {
            name: 'RSI Strategy',
            trades: 38,
            winRate: 72.3,
            profit: 980.50,
            avgProfit: 25.80,
            drawdown: -5.8,
            sharpe: 1.82,
            profitFactor: 2.08
        },
        {
            name: 'MACD Crossover',
            trades: 23,
            winRate: 65.2,
            profit: 650.00,
            avgProfit: 28.26,
            drawdown: -8.5,
            sharpe: 1.45,
            profitFactor: 1.95
        },
        {
            name: 'Bollinger Bands',
            trades: 31,
            winRate: 70.1,
            profit: 1120.00,
            avgProfit: 36.13,
            drawdown: -7.2,
            sharpe: 1.68,
            profitFactor: 2.22
        },
        {
            name: 'Support/Resistance',
            trades: 28,
            winRate: 64.3,
            profit: 890.00,
            avgProfit: 31.79,
            drawdown: -9.1,
            sharpe: 1.52,
            profitFactor: 1.88
        }
    ];

    renderStrategyTable(strategies);

    // Filter handlers
    document.getElementById('strategyFilter')?.addEventListener('change', applyFilters);
    document.getElementById('timeFilter')?.addEventListener('change', applyFilters);
    document.getElementById('instrumentFilter')?.addEventListener('change', applyFilters);

    // Export handler
    document.getElementById('exportBtn')?.addEventListener('click', exportData);

    function generateEquityData() {
        const labels = [];
        const equity = [];
        const benchmark = [];
        let currentEquity = 10000;
        let currentBenchmark = 10000;

        for (let i = 0; i < 90; i++) {
            const date = new Date();
            date.setDate(date.getDate() - (90 - i));
            labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            
            const change = (Math.random() - 0.45) * 100;
            currentEquity += change;
            equity.push(Math.max(0, currentEquity));
            
            const benchmarkChange = (Math.random() - 0.48) * 80;
            currentBenchmark += benchmarkChange;
            benchmark.push(Math.max(0, currentBenchmark));
        }

        return { labels, equity, benchmark };
    }

    function generateDrawdownData() {
        const labels = [];
        const values = [];
        
        for (let i = 0; i < 30; i++) {
            const date = new Date();
            date.setDate(date.getDate() - (30 - i));
            labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            values.push((Math.random() - 0.7) * 10);
        }

        return { labels, values };
    }

    function generateHourlyData() {
        return Array.from({length: 24}, () => (Math.random() - 0.4) * 50);
    }

    function renderStrategyTable(data) {
        const tbody = document.getElementById('strategyTableBody');
        if (!tbody) return;

        tbody.innerHTML = data.map(strategy => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-blue-500 mr-2"></div>
                        <span class="font-medium text-gray-900">${strategy.name}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${strategy.trades}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-green-600 font-semibold">${strategy.winRate}%</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-green-600 font-semibold">+$${strategy.profit.toFixed(2)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$${strategy.avgProfit.toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-red-600">${strategy.drawdown}%</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${strategy.sharpe.toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${strategy.profitFactor.toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <a href="#" class="text-blue-600 hover:text-blue-700">View Details</a>
                </td>
            </tr>
        `).join('');
    }

    function applyFilters() {
        // Filter logic would go here
        console.log('Filters applied');
    }

    function exportData() {
        // Export logic would go here
        alert('Export functionality will be implemented');
    }

    function sortTable(column) {
        // Sort logic would go here
        console.log('Sort by:', column);
    }

    // Update metrics
    Object.keys(performanceData).forEach(key => {
        const element = document.getElementById(key);
        if (element) {
            if (typeof performanceData[key] === 'number' && key.includes('Rate') || key.includes('Ratio') || key.includes('Factor')) {
                element.textContent = performanceData[key].toFixed(2);
            } else if (typeof performanceData[key] === 'number' && key.includes('Return') || key.includes('Drawdown')) {
                element.textContent = (performanceData[key] >= 0 ? '+' : '') + performanceData[key].toFixed(1) + '%';
            } else if (typeof performanceData[key] === 'number' && key.includes('Win') || key.includes('Loss') || key.includes('Profit')) {
                element.textContent = '$' + Math.abs(performanceData[key]).toFixed(2);
            } else {
                element.textContent = performanceData[key];
            }
        }
    });
});
</script>
@endpush
@endsection
