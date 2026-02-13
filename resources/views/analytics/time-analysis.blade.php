@extends('layouts.app')

@section('title', 'Time Analysis - FXEngine')
@section('page-title', 'Time Analysis')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Time Analysis</h2>
        <p class="text-sm text-gray-600 mt-1">Performance analysis by time periods</p>
    </div>

    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance by Hour</h3>
        <div class="relative" style="height: 300px;">
            <canvas id="hourlyChart"></canvas>
        </div>
    </div>

    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance by Day of Week</h3>
        <div class="relative" style="height: 300px;">
            <canvas id="dailyChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hourlyCtx = document.getElementById('hourlyChart');
    if (hourlyCtx) {
        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: Array.from({length: 24}, (_, i) => i + ':00'),
                datasets: [{
                    label: 'Profit',
                    data: Array.from({length: 24}, () => Math.random() * 200 - 50),
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
                plugins: { legend: { display: false } }
            }
        });
    }

    const dailyCtx = document.getElementById('dailyChart');
    if (dailyCtx) {
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Profit',
                    data: [150, 200, -50, 180, 120, 0, 0],
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
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
@endpush
@endsection




