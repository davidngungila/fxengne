@extends('layouts.app')

@section('title', 'Win/Loss Analysis - FXEngine')
@section('page-title', 'Win/Loss Analysis')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Win/Loss Analysis</h2>
        <p class="text-sm text-gray-600 mt-1">Detailed win and loss statistics</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <p class="text-sm text-gray-600">Total Trades</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">103</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Winning Trades</p>
            <p class="text-2xl font-bold text-green-600 mt-1">71</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Losing Trades</p>
            <p class="text-2xl font-bold text-red-600 mt-1">32</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Win Rate</p>
            <p class="text-2xl font-bold text-purple-600 mt-1">68.9%</p>
        </div>
    </div>

    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Win/Loss Distribution</h3>
        <div class="relative" style="height: 300px;">
            <canvas id="winLossChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('winLossChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Winning', 'Losing'],
                datasets: [{
                    data: [68.9, 31.1],
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




