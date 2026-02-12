@extends('layouts.app')

@section('title', 'Strategy Performance - FxEngne')
@section('page-title', 'Strategy Performance')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Strategy Performance</h2>
            <p class="text-sm text-gray-600 mt-1">Analyze and compare strategy performance</p>
        </div>
        <select id="strategyFilter" class="form-input">
            <option value="all">All Strategies</option>
            <option value="ema_crossover">EMA Crossover</option>
            <option value="rsi_oversold_overbought">RSI Strategy</option>
            <option value="macd_crossover">MACD Crossover</option>
        </select>
    </div>

    <!-- Performance Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Return</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">+15.2%</p>
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
                    <p class="text-sm text-gray-600">Sharpe Ratio</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">1.85</p>
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
                    <p class="text-sm text-gray-600">Max Drawdown</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">-8.5%</p>
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
                    <p class="text-sm text-gray-600">Profit Factor</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">2.15</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Returns</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="monthlyReturnsChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Win/Loss Distribution</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="winLossChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Strategy Comparison -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Strategy Comparison</h3>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Strategy</th>
                        <th>Total Trades</th>
                        <th>Win Rate</th>
                        <th>Total Profit</th>
                        <th>Avg Profit</th>
                        <th>Max Drawdown</th>
                        <th>Sharpe Ratio</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">EMA Crossover</td>
                        <td>42</td>
                        <td><span class="text-green-600 font-semibold">68.5%</span></td>
                        <td class="text-green-600 font-semibold">+$1,250.00</td>
                        <td>$29.76</td>
                        <td class="text-red-600">-6.2%</td>
                        <td>1.75</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">RSI Strategy</td>
                        <td>38</td>
                        <td><span class="text-green-600 font-semibold">72.3%</span></td>
                        <td class="text-green-600 font-semibold">+$980.50</td>
                        <td>$25.80</td>
                        <td class="text-red-600">-5.8%</td>
                        <td>1.82</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">MACD Crossover</td>
                        <td>23</td>
                        <td><span class="text-green-600 font-semibold">65.2%</span></td>
                        <td class="text-green-600 font-semibold">+$650.00</td>
                        <td>$28.26</td>
                        <td class="text-red-600">-8.5%</td>
                        <td>1.45</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Returns Chart
    const monthlyCtx = document.getElementById('monthlyReturnsChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Monthly Return',
                    data: [2.5, 3.2, -1.5, 4.1, 2.8, 3.9],
                    backgroundColor: function(context) {
                        return context.parsed.y >= 0 
                            ? TradingColors.candles.bullish 
                            : TradingColors.candles.bearish;
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
                    ]
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
});
</script>
@endpush
@endsection
