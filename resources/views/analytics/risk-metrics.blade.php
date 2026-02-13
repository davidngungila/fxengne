@extends('layouts.app')

@section('title', 'Risk Metrics - FXEngine')
@section('page-title', 'Risk Metrics')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Risk Metrics</h2>
        <p class="text-sm text-gray-600 mt-1">Comprehensive risk analysis and metrics</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <p class="text-sm text-gray-600">Sharpe Ratio</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">1.85</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Sortino Ratio</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">2.45</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Max Drawdown</p>
            <p class="text-2xl font-bold text-red-600 mt-1">-8.5%</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Calmar Ratio</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">1.79</p>
        </div>
    </div>

    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Drawdown Chart</h3>
        <div class="relative" style="height: 300px;">
            <canvas id="drawdownChart"></canvas>
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
                plugins: { legend: { display: false } },
                scales: {
                    y: { ticks: { callback: v => v + '%' } }
                }
            }
        });
    }
});
</script>
@endpush
@endsection



