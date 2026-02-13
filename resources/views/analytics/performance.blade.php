@extends('layouts.app')

@section('title', 'Performance Reports - FXEngine')
@section('page-title', 'Performance Reports')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Performance Reports</h2>
            <p class="text-sm text-gray-600 mt-1">Comprehensive performance analysis with live metrics</p>
        </div>
        <div class="flex items-center space-x-3">
            <select id="timeframeSelect" class="form-input">
                <option value="all" {{ $timeframe === 'all' ? 'selected' : '' }}>All Time</option>
                <option value="year" {{ $timeframe === 'year' ? 'selected' : '' }}>This Year</option>
                <option value="month" {{ $timeframe === 'month' ? 'selected' : '' }}>This Month</option>
                <option value="week" {{ $timeframe === 'week' ? 'selected' : '' }}>This Week</option>
                <option value="today" {{ $timeframe === 'today' ? 'selected' : '' }}>Today</option>
            </select>
            <button id="refreshData" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Key Performance Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card bg-gradient-to-br from-green-50 to-emerald-50 border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Profit</p>
                    <p class="text-3xl font-bold text-green-600 mt-1" id="totalProfit">${{ number_format($metrics['total_profit'], 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $metrics['total_trades'] }} trades</p>
                </div>
                <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Win Rate</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1" id="winRate">{{ number_format($metrics['win_rate'], 2) }}%</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $metrics['winning_trades'] }}W / {{ $metrics['losing_trades'] }}L</p>
                </div>
                <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-purple-50 to-pink-50 border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Profit Factor</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1" id="profitFactor">{{ number_format($metrics['profit_factor'], 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-green-600">${{ number_format($metrics['total_wins'], 2) }}</span> / 
                        <span class="text-red-600">${{ number_format($metrics['total_losses'], 2) }}</span>
                    </p>
                </div>
                <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-yellow-50 to-amber-50 border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Sharpe Ratio</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1" id="sharpeRatio">{{ number_format($metrics['sharpe_ratio'], 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        @if($metrics['sharpe_ratio'] >= 2)
                            <span class="text-green-600 font-semibold">Excellent</span>
                        @elseif($metrics['sharpe_ratio'] >= 1)
                            <span class="text-blue-600 font-semibold">Good</span>
                        @else
                            <span class="text-gray-600">Fair</span>
                        @endif
                    </p>
                </div>
                <div class="w-16 h-16 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Metrics Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="card">
            <p class="text-xs text-gray-600">Max Drawdown</p>
            <p class="text-xl font-bold text-red-600 mt-1">{{ number_format($metrics['max_drawdown'], 2) }}%</p>
        </div>
        <div class="card">
            <p class="text-xs text-gray-600">Avg Win</p>
            <p class="text-xl font-bold text-green-600 mt-1">${{ number_format($metrics['avg_win'], 2) }}</p>
        </div>
        <div class="card">
            <p class="text-xs text-gray-600">Avg Loss</p>
            <p class="text-xl font-bold text-red-600 mt-1">${{ number_format($metrics['avg_loss'], 2) }}</p>
        </div>
        <div class="card">
            <p class="text-xs text-gray-600">Expectancy</p>
            <p class="text-xl font-bold {{ $metrics['expectancy'] >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">${{ number_format($metrics['expectancy'], 2) }}</p>
        </div>
        <div class="card">
            <p class="text-xs text-gray-600">R:R Ratio</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($metrics['risk_reward_ratio'], 2) }}</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Equity Curve -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Equity Curve</h3>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span>Live</span>
                </div>
            </div>
            <div class="relative" style="height: 350px;">
                <canvas id="equityChart"></canvas>
            </div>
        </div>

        <!-- Win/Loss Distribution -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Win/Loss Distribution</h3>
            <div class="relative" style="height: 350px;">
                <canvas id="winLossChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Performance Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Daily Performance -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Performance</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="dailyPerformanceChart"></canvas>
            </div>
        </div>

        <!-- Largest Wins/Losses -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Largest Trades</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                    <div>
                        <p class="font-semibold text-gray-900">Largest Win</p>
                        <p class="text-xs text-gray-600">Best performing trade</p>
                    </div>
                    <p class="text-xl font-bold text-green-600">${{ number_format($metrics['largest_win'], 2) }}</p>
                </div>
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                    <div>
                        <p class="font-semibold text-gray-900">Largest Loss</p>
                        <p class="text-xs text-gray-600">Worst performing trade</p>
                    </div>
                    <p class="text-xl font-bold text-red-600">${{ number_format($metrics['largest_loss'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Performance Summary -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Summary</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Total Trades</span>
                        <span class="font-semibold text-gray-900">{{ $metrics['total_trades'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Winning Trades</span>
                        <span class="font-semibold text-green-600">{{ $metrics['winning_trades'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $metrics['total_trades'] > 0 ? ($metrics['winning_trades'] / $metrics['total_trades']) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Losing Trades</span>
                        <span class="font-semibold text-red-600">{{ $metrics['losing_trades'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-600 h-2 rounded-full" style="width: {{ $metrics['total_trades'] > 0 ? ($metrics['losing_trades'] / $metrics['total_trades']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';
    const metrics = @json($metrics);
    const timeAnalysis = @json($timeAnalysis);

    // Equity Curve Chart
    const equityCtx = document.getElementById('equityChart');
    if (equityCtx && metrics.equity_curve && metrics.equity_curve.length > 0) {
        const equityLabels = metrics.equity_curve.map((_, i) => `Trade ${i + 1}`);
        new Chart(equityCtx, {
            type: 'line',
            data: {
                labels: equityLabels,
                datasets: [{
                    label: 'Equity',
                    data: metrics.equity_curve,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
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
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
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
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Win/Loss Distribution Chart
    const winLossCtx = document.getElementById('winLossChart');
    if (winLossCtx) {
        new Chart(winLossCtx, {
            type: 'doughnut',
            data: {
                labels: ['Winning Trades', 'Losing Trades'],
                datasets: [{
                    data: [metrics.winning_trades, metrics.losing_trades],
                    backgroundColor: ['#10b981', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Daily Performance Chart
    const dailyCtx = document.getElementById('dailyPerformanceChart');
    if (dailyCtx && timeAnalysis && timeAnalysis.length > 0) {
        const dailyData = timeAnalysis.slice(-30); // Last 30 days
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: dailyData.map(d => {
                    const date = new Date(d.date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Daily Profit',
                    data: dailyData.map(d => d.profit),
                    backgroundColor: function(context) {
                        return context.parsed.y >= 0 ? '#10b981' : '#ef4444';
                    },
                    borderRadius: 4
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
                                return '$' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toFixed(0);
                            }
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { maxRotation: 45, minRotation: 45 }
                    }
                }
            }
        });
    }

    // Timeframe selector
    document.getElementById('timeframeSelect')?.addEventListener('change', function() {
        const timeframe = this.value;
        window.location.href = '{{ route("analytics.performance") }}?timeframe=' + timeframe;
    });

    // Refresh button
    document.getElementById('refreshData')?.addEventListener('click', function() {
        window.location.reload();
    });

    // Auto-refresh every 30 seconds
    setInterval(() => {
        window.location.reload();
    }, 30000);
});
</script>
@endpush
@endsection
