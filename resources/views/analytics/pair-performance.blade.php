@extends('layouts.app')

@section('title', 'Pair Performance - FXEngine')
@section('page-title', 'Pair Performance')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Pair Performance</h2>
            <p class="text-sm text-gray-600 mt-1">Analyze performance by currency pair</p>
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

    <!-- Pair Performance Chart -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pair Performance Comparison</h3>
        <div class="relative" style="height: 400px;">
            <canvas id="pairPerformanceChart"></canvas>
        </div>
    </div>

    <!-- Pair Performance Table -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detailed Pair Statistics</h3>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pair</th>
                        <th>Total Trades</th>
                        <th>Winning</th>
                        <th>Losing</th>
                        <th>Win Rate</th>
                        <th>Total P/L</th>
                        <th>Avg P/L</th>
                        <th>Total Wins</th>
                        <th>Total Losses</th>
                        <th>Profit Factor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pairStats as $pair => $stats)
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">{{ str_replace('_', '/', $pair) }}</td>
                        <td>{{ $stats['total_trades'] }}</td>
                        <td class="text-green-600 font-semibold">{{ $stats['winning_trades'] }}</td>
                        <td class="text-red-600 font-semibold">{{ $stats['losing_trades'] }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $stats['win_rate'] >= 60 ? 'bg-green-100 text-green-800' : ($stats['win_rate'] >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ number_format($stats['win_rate'], 2) }}%
                            </span>
                        </td>
                        <td class="font-bold {{ $stats['total_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $stats['total_profit'] >= 0 ? '+' : '' }}${{ number_format($stats['total_profit'], 2) }}
                        </td>
                        <td class="{{ $stats['avg_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $stats['avg_profit'] >= 0 ? '+' : '' }}${{ number_format($stats['avg_profit'], 2) }}
                        </td>
                        <td class="text-green-600">${{ number_format($stats['total_wins'], 2) }}</td>
                        <td class="text-red-600">${{ number_format($stats['total_losses'], 2) }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $stats['profit_factor'] >= 2 ? 'bg-green-100 text-green-800' : ($stats['profit_factor'] >= 1.5 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ number_format($stats['profit_factor'], 2) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-8 text-gray-500">
                            <p>No trading data available for the selected timeframe</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pairStats = @json($pairStats);

    // Pair Performance Chart
    const pairCtx = document.getElementById('pairPerformanceChart');
    if (pairCtx && Object.keys(pairStats).length > 0) {
        const pairs = Object.keys(pairStats).map(p => p.replace('_', '/'));
        const profits = Object.values(pairStats).map(s => s.total_profit);
        
        new Chart(pairCtx, {
            type: 'bar',
            data: {
                labels: pairs,
                datasets: [{
                    label: 'Total Profit',
                    data: profits,
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
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Timeframe selector
    document.getElementById('timeframeSelect')?.addEventListener('change', function() {
        const timeframe = this.value;
        window.location.href = '{{ route("analytics.pair-performance") }}?timeframe=' + timeframe;
    });

