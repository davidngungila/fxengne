@extends('layouts.app')

@section('title', 'Risk Metrics - FXEngine')
@section('page-title', 'Risk Metrics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Risk Metrics</h2>
            <p class="text-sm text-gray-600 mt-1">Comprehensive risk analysis with live calculations</p>
        </div>
        <div class="flex items-center space-x-2">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span id="riskStatus">Live</span>
            </div>
            <button id="refreshRisk" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Risk Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Value at Risk (95%) -->
        <div class="card bg-gradient-to-br from-red-50 to-orange-50 border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">VaR (95%)</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">${{ number_format($riskMetrics['var_95'], 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">95% confidence level</p>
                </div>
                <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Value at Risk (99%) -->
        <div class="card bg-gradient-to-br from-orange-50 to-red-50 border-orange-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">VaR (99%)</p>
                    <p class="text-3xl font-bold text-orange-600 mt-1">${{ number_format($riskMetrics['var_99'], 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">99% confidence level</p>
                </div>
                <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Current Exposure -->
        <div class="card bg-gradient-to-br from-yellow-50 to-amber-50 border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Current Exposure</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">${{ number_format($riskMetrics['current_exposure'], 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $riskMetrics['open_positions'] }} open positions</p>
                </div>
                <div class="w-16 h-16 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Max Drawdown -->
        <div class="card bg-gradient-to-br from-purple-50 to-pink-50 border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Max Drawdown</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1">{{ number_format($performanceMetrics['max_drawdown'], 2) }}%</p>
                    <p class="text-xs text-gray-500 mt-1">Historical maximum</p>
                </div>
                <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Risk Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="card">
            <p class="text-sm text-gray-600">Avg Risk per Trade</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($riskMetrics['avg_risk_per_trade'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Average risk exposure</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Avg R:R Ratio</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($riskMetrics['avg_risk_reward_ratio'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Risk/Reward average</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Position Size Consistency</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($riskMetrics['position_size_consistency'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Standard deviation</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Max Exposure</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($riskMetrics['max_exposure'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Historical maximum</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Total Margin Used</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">${{ number_format($riskMetrics['total_margin_used'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Current margin</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Unrealized P/L</p>
            <p class="text-2xl font-bold {{ $riskMetrics['unrealized_pl'] >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                {{ $riskMetrics['unrealized_pl'] >= 0 ? '+' : '' }}${{ number_format($riskMetrics['unrealized_pl'], 2) }}
            </p>
            <p class="text-xs text-gray-500 mt-1">Floating profit/loss</p>
        </div>
    </div>

    <!-- Risk Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Drawdown Chart -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Drawdown Analysis</h3>
                <span class="text-xs text-gray-500">Historical drawdowns</span>
            </div>
            <div class="relative" style="height: 350px;">
                <canvas id="drawdownChart"></canvas>
            </div>
        </div>

        <!-- Risk/Reward Distribution -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Risk/Reward Distribution</h3>
                <span class="text-xs text-gray-500">Trade R:R ratios</span>
            </div>
            <div class="relative" style="height: 350px;">
                <canvas id="riskRewardChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Risk Summary Table -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Risk Summary</h3>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Metric</th>
                        <th>Value</th>
                        <th>Status</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-medium">Sharpe Ratio</td>
                        <td class="font-bold">{{ number_format($performanceMetrics['sharpe_ratio'], 2) }}</td>
                        <td>
                            @if($performanceMetrics['sharpe_ratio'] >= 2)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Excellent</span>
                            @elseif($performanceMetrics['sharpe_ratio'] >= 1)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">Good</span>
                            @else
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">Fair</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-600">Risk-adjusted return measure</td>
                    </tr>
                    <tr>
                        <td class="font-medium">Max Drawdown</td>
                        <td class="font-bold text-red-600">{{ number_format($performanceMetrics['max_drawdown'], 2) }}%</td>
                        <td>
                            @if($performanceMetrics['max_drawdown'] <= 10)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Low</span>
                            @elseif($performanceMetrics['max_drawdown'] <= 20)
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">Moderate</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">High</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-600">Maximum peak-to-trough decline</td>
                    </tr>
                    <tr>
                        <td class="font-medium">Value at Risk (95%)</td>
                        <td class="font-bold">${{ number_format($riskMetrics['var_95'], 2) }}</td>
                        <td>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">95% Confidence</span>
                        </td>
                        <td class="text-sm text-gray-600">Potential loss at 95% confidence</td>
                    </tr>
                    <tr>
                        <td class="font-medium">Value at Risk (99%)</td>
                        <td class="font-bold">${{ number_format($riskMetrics['var_99'], 2) }}</td>
                        <td>
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded">99% Confidence</span>
                        </td>
                        <td class="text-sm text-gray-600">Potential loss at 99% confidence</td>
                    </tr>
                    <tr>
                        <td class="font-medium">Current Exposure</td>
                        <td class="font-bold">${{ number_format($riskMetrics['current_exposure'], 2) }}</td>
                        <td>
                            @if($riskMetrics['current_exposure'] < $riskMetrics['max_exposure'] * 0.5)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Low</span>
                            @elseif($riskMetrics['current_exposure'] < $riskMetrics['max_exposure'] * 0.8)
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">Moderate</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">High</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-600">Current margin exposure</td>
                    </tr>
                    <tr>
                        <td class="font-medium">Average R:R Ratio</td>
                        <td class="font-bold">{{ number_format($riskMetrics['avg_risk_reward_ratio'], 2) }}</td>
                        <td>
                            @if($riskMetrics['avg_risk_reward_ratio'] >= 2)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Excellent</span>
                            @elseif($riskMetrics['avg_risk_reward_ratio'] >= 1.5)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">Good</span>
                            @else
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">Fair</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-600">Average risk to reward ratio</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const performanceMetrics = @json($performanceMetrics);
    const riskMetrics = @json($riskMetrics);

    // Drawdown Chart
    const drawdownCtx = document.getElementById('drawdownChart');
    if (drawdownCtx && performanceMetrics.equity_curve && performanceMetrics.equity_curve.length > 0) {
        const equityCurve = performanceMetrics.equity_curve;
        const drawdowns = [];
        let peak = equityCurve[0];
        
        equityCurve.forEach((value, index) => {
            if (value > peak) peak = value;
            const drawdown = ((peak - value) / peak) * 100;
            drawdowns.push(-drawdown); // Negative for visualization
        });

        const labels = equityCurve.map((_, i) => `Trade ${i + 1}`);
        
        new Chart(drawdownCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Drawdown',
                    data: drawdowns,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 2,
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
                                return Math.abs(context.parsed.y).toFixed(2) + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                return Math.abs(value).toFixed(1) + '%';
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

    // Risk/Reward Distribution Chart (placeholder - would need actual trade data)
    const riskRewardCtx = document.getElementById('riskRewardChart');
    if (riskRewardCtx) {
        // Sample data - in production, this would come from actual trades
        const rrRatios = [1.0, 1.5, 2.0, 2.5, 3.0, 3.5, 4.0];
        const frequencies = [5, 8, 12, 15, 10, 6, 4];
        
        new Chart(riskRewardCtx, {
            type: 'bar',
            data: {
                labels: rrRatios.map(r => r.toFixed(1) + ':1'),
                datasets: [{
                    label: 'Number of Trades',
                    data: frequencies,
                    backgroundColor: '#3b82f6',
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
                                return context.parsed.y + ' trades';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 2
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

    // Refresh button
    document.getElementById('refreshRisk')?.addEventListener('click', function() {
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
