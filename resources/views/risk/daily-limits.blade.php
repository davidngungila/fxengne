@extends('layouts.app')

@section('title', 'Daily Limits - FXEngine')
@section('page-title', 'Daily Limits')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Daily Trading Limits</h2>
        <p class="text-sm text-gray-600 mt-1">Set and monitor your daily trading limits</p>
    </div>

    <!-- Daily Limits Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Daily Loss Limit</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">$500</p>
                    <p class="text-xs text-gray-500 mt-1">5.0% of account</p>
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
                    <p class="text-sm text-gray-600">Daily Profit Target</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">$1,000</p>
                    <p class="text-xs text-gray-500 mt-1">10.0% of account</p>
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
                    <p class="text-sm text-gray-600">Today's P/L</p>
                    <p class="text-2xl font-bold mt-1" id="todayPL">-$120</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Remaining Limit</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">$380</p>
                    <p class="text-xs text-gray-500 mt-1">76% remaining</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bars -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Limit Progress</h3>
        <div class="space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Loss Limit Usage</span>
                    <span class="text-sm font-semibold text-red-600">24% ($120 / $500)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-red-600 h-3 rounded-full" style="width: 24%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Profit Target Progress</span>
                    <span class="text-sm font-semibold text-gray-600">0% ($0 / $1,000)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-600 h-3 rounded-full" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Limit Configuration -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Loss Limits</h3>
            <form class="space-y-4">
                <div>
                    <label class="form-label">Daily Loss Limit ($)</label>
                    <input type="number" class="form-input" value="500" min="50" step="50">
                </div>
                <div>
                    <label class="form-label">Daily Loss Limit (%)</label>
                    <input type="number" class="form-input" value="5.0" min="1" max="20" step="0.5">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="autoStopLoss" class="form-checkbox" checked>
                    <label for="autoStopLoss" class="ml-2 text-sm text-gray-700">Auto-stop trading when limit reached</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="lossWarning" class="form-checkbox" checked>
                    <label for="lossWarning" class="ml-2 text-sm text-gray-700">Show warning at 80% of limit</label>
                </div>
            </form>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Profit Targets</h3>
            <form class="space-y-4">
                <div>
                    <label class="form-label">Daily Profit Target ($)</label>
                    <input type="number" class="form-input" value="1000" min="100" step="100">
                </div>
                <div>
                    <label class="form-label">Daily Profit Target (%)</label>
                    <input type="number" class="form-input" value="10.0" min="1" max="50" step="1">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="autoStopProfit" class="form-checkbox">
                    <label for="autoStopProfit" class="ml-2 text-sm text-gray-700">Auto-stop trading when target reached</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="profitNotification" class="form-checkbox" checked>
                    <label for="profitNotification" class="ml-2 text-sm text-gray-700">Notify when target reached</label>
                </div>
            </form>
        </div>
    </div>

    <!-- Daily Statistics -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Statistics</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Total Trades</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">8</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Winning Trades</p>
                <p class="text-2xl font-bold text-green-600 mt-1">5</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Losing Trades</p>
                <p class="text-2xl font-bold text-red-600 mt-1">3</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Win Rate</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">62.5%</p>
            </div>
        </div>
    </div>

    <div class="flex space-x-3">
        <button class="btn btn-primary flex-1">Save Limits</button>
        <button class="btn btn-secondary">Reset Today</button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update today's P/L color
    const todayPL = document.getElementById('todayPL');
    const plValue = parseFloat(todayPL.textContent.replace('$', '').replace('-', ''));
    if (plValue < 0) {
        todayPL.className = 'text-2xl font-bold text-red-600 mt-1';
    } else {
        todayPL.className = 'text-2xl font-bold text-green-600 mt-1';
    }
});
</script>
@endpush
@endsection

@section('title', 'Daily Limits - FXEngine')
@section('page-title', 'Daily Limits')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Daily Trading Limits</h2>
        <p class="text-sm text-gray-600 mt-1">Set and monitor your daily trading limits</p>
    </div>

    <!-- Daily Limits Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Daily Loss Limit</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">$500</p>
                    <p class="text-xs text-gray-500 mt-1">5.0% of account</p>
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
                    <p class="text-sm text-gray-600">Daily Profit Target</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">$1,000</p>
                    <p class="text-xs text-gray-500 mt-1">10.0% of account</p>
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
                    <p class="text-sm text-gray-600">Today's P/L</p>
                    <p class="text-2xl font-bold mt-1" id="todayPL">-$120</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Remaining Limit</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">$380</p>
                    <p class="text-xs text-gray-500 mt-1">76% remaining</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bars -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Limit Progress</h3>
        <div class="space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Loss Limit Usage</span>
                    <span class="text-sm font-semibold text-red-600">24% ($120 / $500)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-red-600 h-3 rounded-full" style="width: 24%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Profit Target Progress</span>
                    <span class="text-sm font-semibold text-gray-600">0% ($0 / $1,000)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-600 h-3 rounded-full" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Limit Configuration -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Loss Limits</h3>
            <form class="space-y-4">
                <div>
                    <label class="form-label">Daily Loss Limit ($)</label>
                    <input type="number" class="form-input" value="500" min="50" step="50">
                </div>
                <div>
                    <label class="form-label">Daily Loss Limit (%)</label>
                    <input type="number" class="form-input" value="5.0" min="1" max="20" step="0.5">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="autoStopLoss" class="form-checkbox" checked>
                    <label for="autoStopLoss" class="ml-2 text-sm text-gray-700">Auto-stop trading when limit reached</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="lossWarning" class="form-checkbox" checked>
                    <label for="lossWarning" class="ml-2 text-sm text-gray-700">Show warning at 80% of limit</label>
                </div>
            </form>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Profit Targets</h3>
            <form class="space-y-4">
                <div>
                    <label class="form-label">Daily Profit Target ($)</label>
                    <input type="number" class="form-input" value="1000" min="100" step="100">
                </div>
                <div>
                    <label class="form-label">Daily Profit Target (%)</label>
                    <input type="number" class="form-input" value="10.0" min="1" max="50" step="1">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="autoStopProfit" class="form-checkbox">
                    <label for="autoStopProfit" class="ml-2 text-sm text-gray-700">Auto-stop trading when target reached</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="profitNotification" class="form-checkbox" checked>
                    <label for="profitNotification" class="ml-2 text-sm text-gray-700">Notify when target reached</label>
                </div>
            </form>
        </div>
    </div>

    <!-- Daily Statistics -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Statistics</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Total Trades</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">8</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Winning Trades</p>
                <p class="text-2xl font-bold text-green-600 mt-1">5</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Losing Trades</p>
                <p class="text-2xl font-bold text-red-600 mt-1">3</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Win Rate</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">62.5%</p>
            </div>
        </div>
    </div>

    <div class="flex space-x-3">
        <button class="btn btn-primary flex-1">Save Limits</button>
        <button class="btn btn-secondary">Reset Today</button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update today's P/L color
    const todayPL = document.getElementById('todayPL');
    const plValue = parseFloat(todayPL.textContent.replace('$', '').replace('-', ''));
    if (plValue < 0) {
        todayPL.className = 'text-2xl font-bold text-red-600 mt-1';
    } else {
        todayPL.className = 'text-2xl font-bold text-green-600 mt-1';
    }
});
</script>
@endpush
@endsection
