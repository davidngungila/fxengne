@extends('layouts.app')

@section('title', 'Drawdown Protection - FXEngine')
@section('page-title', 'Drawdown Protection')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Drawdown Protection</h2>
        <p class="text-sm text-gray-600 mt-1">Monitor and protect against account drawdowns</p>
    </div>

    <!-- Drawdown Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Current Drawdown</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">-2.3%</p>
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
                    <p class="text-sm text-gray-600">Max Drawdown</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">-8.5%</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Drawdown Limit</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">-10.0%</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Recovery Needed</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">+2.35%</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Drawdown Chart -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Drawdown History</h3>
        <div class="relative" style="height: 350px;">
            <canvas id="drawdownChart"></canvas>
        </div>
    </div>

    <!-- Drawdown Configuration -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Protection Settings</h3>
            <form class="space-y-4">
                <div>
                    <label class="form-label">Maximum Drawdown Limit (%)</label>
                    <input type="number" class="form-input" value="10.0" min="5" max="30" step="0.5">
                    <p class="text-xs text-gray-500 mt-1">Trading will stop if drawdown exceeds this limit</p>
                </div>
                <div>
                    <label class="form-label">Warning Threshold (%)</label>
                    <input type="number" class="form-input" value="7.5" min="3" max="20" step="0.5">
                    <p class="text-xs text-gray-500 mt-1">Show warning when drawdown reaches this level</p>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="autoStopDrawdown" class="form-checkbox" checked>
                    <label for="autoStopDrawdown" class="ml-2 text-sm text-gray-700">Auto-stop trading at drawdown limit</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="reduceRiskDrawdown" class="form-checkbox">
                    <label for="reduceRiskDrawdown" class="ml-2 text-sm text-gray-700">Reduce risk when drawdown increases</label>
                </div>
            </form>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Drawdown Alerts</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Warning Threshold</p>
                            <p class="text-sm text-gray-600">Current: -2.3% | Warning: -7.5%</p>
                        </div>
                    </div>
                    <span class="text-sm text-green-600 font-semibold">Safe</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Maximum Drawdown</p>
                            <p class="text-sm text-gray-600">Current: -2.3% | Limit: -10.0%</p>
                        </div>
                    </div>
                    <span class="text-sm text-green-600 font-semibold">77% Safe</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Drawdown Statistics -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Drawdown Statistics</h3>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Peak Balance</th>
                        <th>Trough Balance</th>
                        <th>Drawdown</th>
                        <th>Recovery Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td>Last 30 Days</td>
                        <td class="font-mono">$10,500.00</td>
                        <td class="font-mono">$9,605.00</td>
                        <td class="text-red-600 font-semibold">-8.5%</td>
                        <td>5 days</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td>Last 7 Days</td>
                        <td class="font-mono">$10,200.00</td>
                        <td class="font-mono">$9,770.00</td>
                        <td class="text-red-600 font-semibold">-4.2%</td>
                        <td>2 days</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td>Current</td>
                        <td class="font-mono">$10,000.00</td>
                        <td class="font-mono">$9,770.00</td>
                        <td class="text-red-600 font-semibold">-2.3%</td>
                        <td>Ongoing</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex space-x-3">
        <button class="btn btn-primary flex-1">Save Settings</button>
        <button class="btn btn-secondary">Reset Drawdown</button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('drawdownChart');
    if (ctx) {
        const labels = Array.from({ length: 30 }, (_, i) => `Day ${i + 1}`);
        const data = Array.from({ length: 30 }, (_, i) => {
            const base = -Math.abs(Math.sin(i / 5) * 8);
            return base + (Math.random() * 2 - 1);
        });

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Drawdown',
                    data: data,
                    borderColor: TradingColors.candles.bearish,
                    backgroundColor: TradingColors.toRgba(TradingColors.candles.bearish, 0.1),
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Warning Threshold',
                    data: Array(30).fill(-7.5),
                    borderColor: TradingColors.indicators.warning,
                    borderWidth: 2,
                    borderDash: [5, 5],
                    fill: false
                }, {
                    label: 'Max Limit',
                    data: Array(30).fill(-10.0),
                    borderColor: TradingColors.candles.bearish,
                    borderWidth: 2,
                    borderDash: [10, 5],
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
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
});
</script>
@endpush
@endsection
