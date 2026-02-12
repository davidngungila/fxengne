@extends('layouts.app')

@section('title', 'Performance Analytics - FxEngne')
@section('page-title', 'Performance Analytics')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Performance Analytics</h2>
        <p class="text-sm text-gray-600 mt-1">Detailed performance metrics and analysis</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <p class="text-sm text-gray-600">Total Return</p>
            <p class="text-2xl font-bold text-green-600 mt-1">+15.2%</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Sharpe Ratio</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">1.85</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Max Drawdown</p>
            <p class="text-2xl font-bold text-red-600 mt-1">-8.5%</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Profit Factor</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">2.15</p>
        </div>
    </div>

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
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
                plugins: { legend: { display: false } },
                scales: {
                    y: { ticks: { callback: v => v + '%' } }
                }
            }
        });
    }

    const winLossCtx = document.getElementById('winLossChart');
    if (winLossCtx) {
        new Chart(winLossCtx, {
            type: 'doughnut',
            data: {
                labels: ['Winning', 'Losing'],
                datasets: [{
                    data: [68.5, 31.5],
                    backgroundColor: [TradingColors.candles.bullish, TradingColors.candles.bearish]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
});
</script>
@endpush
@endsection

