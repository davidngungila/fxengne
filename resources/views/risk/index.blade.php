@extends('layouts.app')

@section('title', 'Risk Management - FXEngine')
@section('page-title', 'Risk Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Risk Management Dashboard</h2>
        <p class="text-sm text-gray-600 mt-1">Monitor and control your trading risk exposure</p>
    </div>

    <!-- Risk Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Risk Per Trade</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">1.0%</p>
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
                    <p class="text-sm text-gray-600">Daily Risk Limit</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">5.0%</p>
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
                    <p class="text-sm text-gray-600">Margin Used</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">35%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('risk.settings') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Risk Settings</h3>
                    <p class="text-sm text-gray-600">Configure risk parameters</p>
                </div>
            </div>
        </a>

        <a href="{{ route('risk.daily-limits') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Daily Limits</h3>
                    <p class="text-sm text-gray-600">Set daily trading limits</p>
                </div>
            </div>
        </a>

        <a href="{{ route('risk.lot-calculator') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Lot Calculator</h3>
                    <p class="text-sm text-gray-600">Calculate position sizes</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Risk Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Drawdown Chart</h3>
            <div class="relative" style="height: 250px;">
                <canvas id="drawdownChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Risk Exposure</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">EUR/USD</span>
                        <span class="text-sm font-semibold">$2,500</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 25%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">GBP/USD</span>
                        <span class="text-sm font-semibold">$1,800</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 18%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">XAU/USD</span>
                        <span class="text-sm font-semibold">$5,200</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: 52%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Total Exposure</span>
                        <span class="text-sm font-semibold">$9,500</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 95%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Alerts -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Risk Alerts</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Daily Loss Limit Warning</p>
                        <p class="text-sm text-gray-600">You've used 80% of your daily loss limit</p>
                    </div>
                </div>
                <span class="text-sm text-yellow-600 font-semibold">80%</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Margin Usage Normal</p>
                        <p class="text-sm text-gray-600">Current margin usage is within safe limits</p>
                    </div>
                </div>
                <span class="text-sm text-green-600 font-semibold">35%</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('drawdownChart');
    if (ctx) {
        const labels = Array.from({ length: 30 }, (_, i) => `Day ${i + 1}`);
        const data = Array.from({ length: 30 }, (_, i) => -Math.abs(Math.sin(i / 5) * 5));

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
