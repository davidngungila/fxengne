@extends('layouts.app')

@section('title', 'Win/Loss Analysis - FXEngine')
@section('page-title', 'Win/Loss Analysis')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Win/Loss Analysis</h2>
            <p class="text-sm text-gray-600 mt-1">Detailed win and loss statistics</p>
        </div>
        <div class="flex items-center space-x-3">
            <select id="timeframeSelect" class="form-input">
                <option value="all" {{ $timeframe === 'all' ? 'selected' : '' }}>All Time</option>
                <option value="year" {{ $timeframe === 'year' ? 'selected' : '' }}>This Year</option>
                <option value="month" {{ $timeframe === 'month' ? 'selected' : '' }}>This Month</option>
                <option value="week" {{ $timeframe === 'week' ? 'selected' : '' }}>This Week</option>
            </select>
            <button id="refreshData" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card bg-gradient-to-br from-green-50 to-emerald-50 border-green-200">
            <p class="text-sm text-gray-600">Winning Trades</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $analysis['winning_trades'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($analysis['win_rate'], 2) }}% win rate</p>
        </div>
        <div class="card bg-gradient-to-br from-red-50 to-pink-50 border-red-200">
            <p class="text-sm text-gray-600">Losing Trades</p>
            <p class="text-3xl font-bold text-red-600 mt-1">{{ $analysis['losing_trades'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format(100 - $analysis['win_rate'], 2) }}% loss rate</p>
        </div>
        <div class="card bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-200">
            <p class="text-sm text-gray-600">Average Win</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">${{ number_format($analysis['avg_win'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Per winning trade</p>
        </div>
        <div class="card bg-gradient-to-br from-purple-50 to-pink-50 border-purple-200">
            <p class="text-sm text-gray-600">Average Loss</p>
            <p class="text-3xl font-bold text-purple-600 mt-1">${{ number_format($analysis['avg_loss'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Per losing trade</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Win/Loss Distribution -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Win/Loss Distribution</h3>
            <div class="relative" style="height: 350px;">
                <canvas id="winLossChart"></canvas>
            </div>
        </div>

        <!-- Win/Loss Amounts -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Win/Loss Amounts</h3>
            <div class="relative" style="height: 350px;">
                <canvas id="winLossAmountsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Largest Wins/Losses -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Largest Trades</h3>
            <div class="space-y-3">
                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-gray-900">Largest Win</span>
                        <span class="text-2xl font-bold text-green-600">${{ number_format($analysis['largest_win'], 2) }}</span>
                    </div>
                    <p class="text-xs text-gray-600">Best performing trade</p>
                </div>
                <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-gray-900">Largest Loss</span>
                        <span class="text-2xl font-bold text-red-600">${{ number_format($analysis['largest_loss'], 2) }}</span>
                    </div>
                    <p class="text-xs text-gray-600">Worst performing trade</p>
                </div>
            </div>
        </div>

        <!-- Totals -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Total Summary</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Total Wins</span>
                        <span class="text-xl font-bold text-green-600">${{ number_format($analysis['total_wins'], 2) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Total Losses</span>
                        <span class="text-xl font-bold text-red-600">${{ number_format($analysis['total_losses'], 2) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-600 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-900">Net Profit</span>
                        <span class="text-2xl font-bold {{ ($analysis['total_wins'] - $analysis['total_losses']) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ ($analysis['total_wins'] - $analysis['total_losses']) >= 0 ? '+' : '' }}${{ number_format($analysis['total_wins'] - $analysis['total_losses'], 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consecutive Streaks -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Consecutive Streaks</h3>
            <div class="space-y-4">
                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">Longest Win Streak</p>
                            <p class="text-xs text-gray-600 mt-1">Consecutive wins</p>
                        </div>
                        <span class="text-3xl font-bold text-green-600">{{ $analysis['consecutive_wins'] }}</span>
                    </div>
                </div>
                <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">Longest Loss Streak</p>
                            <p class="text-xs text-gray-600 mt-1">Consecutive losses</p>
                        </div>
                        <span class="text-3xl font-bold text-red-600">{{ $analysis['consecutive_losses'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const analysis = @json($analysis);

    // Win/Loss Distribution Chart
    const winLossCtx = document.getElementById('winLossChart');
    if (winLossCtx) {
        new Chart(winLossCtx, {
            type: 'doughnut',
            data: {
                labels: ['Winning Trades', 'Losing Trades'],
                datasets: [{
                    data: [analysis.winning_trades, analysis.losing_trades],
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

    // Win/Loss Amounts Chart
    const amountsCtx = document.getElementById('winLossAmountsChart');
    if (amountsCtx) {
        new Chart(amountsCtx, {
            type: 'bar',
            data: {
                labels: ['Total Wins', 'Total Losses'],
                datasets: [{
                    label: 'Amount',
                    data: [analysis.total_wins, -analysis.total_losses],
                    backgroundColor: ['#10b981', '#ef4444'],
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
                                return '$' + Math.abs(context.parsed.y).toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                return '$' + Math.abs(value).toFixed(0);
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

    // Timeframe selector
    document.getElementById('timeframeSelect')?.addEventListener('change', function() {
        const timeframe = this.value;
        window.location.href = '{{ route("analytics.win-loss") }}?timeframe=' + timeframe;
    });

    // Refresh button
    document.getElementById('refreshData')?.addEventListener('click', function() {
        window.location.reload();
    });
});
</script>
@endpush
@endsection
