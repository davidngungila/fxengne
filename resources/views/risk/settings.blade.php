@extends('layouts.app')

@section('title', 'Risk Settings - FxEngne')
@section('page-title', 'Risk Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Risk Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Configure your risk management parameters</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Risk Per Trade</h3>
                <form class="space-y-4">
                    <div>
                        <label class="form-label">Risk Percentage (%)</label>
                        <input type="number" class="form-input" value="1.0" min="0.1" max="10" step="0.1">
                        <p class="text-xs text-gray-500 mt-1">Recommended: 1-2% per trade</p>
                    </div>
                    <div>
                        <label class="form-label">Fixed Risk Amount ($)</label>
                        <input type="number" class="form-input" value="100" min="10" step="10">
                        <p class="text-xs text-gray-500 mt-1">Optional: Set fixed dollar amount</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Account Balance:</span>
                            <span class="font-semibold text-gray-900">$10,000.00</span>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-sm font-medium text-gray-700">Risk Amount:</span>
                            <span class="font-semibold text-green-600">$100.00</span>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Stop Loss Settings</h3>
                <form class="space-y-4">
                    <div>
                        <label class="form-label">Default Stop Loss (%)</label>
                        <input type="number" class="form-input" value="1.0" min="0.1" max="10" step="0.1">
                    </div>
                    <div>
                        <label class="form-label">Minimum Stop Loss (Pips)</label>
                        <input type="number" class="form-input" value="20" min="5" step="5">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="requireStopLoss" class="form-checkbox">
                        <label for="requireStopLoss" class="ml-2 text-sm text-gray-700">Require stop loss on all trades</label>
                    </div>
                </form>
            </div>

            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Take Profit Settings</h3>
                <form class="space-y-4">
                    <div>
                        <label class="form-label">Default Take Profit (%)</label>
                        <input type="number" class="form-input" value="2.0" min="0.1" max="20" step="0.1">
                    </div>
                    <div>
                        <label class="form-label">Risk/Reward Ratio</label>
                        <input type="number" class="form-input" value="2.0" min="1" max="5" step="0.1">
                        <p class="text-xs text-gray-500 mt-1">Recommended: Minimum 1:2</p>
                    </div>
                </form>
            </div>

            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Position Sizing</h3>
                <form class="space-y-4">
                    <div>
                        <label class="form-label">Maximum Position Size (%)</label>
                        <input type="number" class="form-input" value="10" min="1" max="50" step="1">
                        <p class="text-xs text-gray-500 mt-1">Maximum position size relative to account</p>
                    </div>
                    <div>
                        <label class="form-label">Maximum Open Positions</label>
                        <input type="number" class="form-input" value="5" min="1" max="20" step="1">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="autoPositionSize" class="form-checkbox" checked>
                        <label for="autoPositionSize" class="ml-2 text-sm text-gray-700">Auto-calculate position size based on risk</label>
                    </div>
                </form>
            </div>

            <div class="flex space-x-3">
                <button class="btn btn-primary flex-1">Save Settings</button>
                <button class="btn btn-secondary">Reset to Defaults</button>
            </div>
        </div>

        <!-- Risk Guidelines -->
        <div class="space-y-6">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Risk Guidelines</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Conservative</p>
                            <p class="text-gray-600">0.5-1% risk per trade</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Moderate</p>
                            <p class="text-gray-600">1-2% risk per trade</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Aggressive</p>
                            <p class="text-gray-600">2-5% risk per trade (Not recommended)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Risk Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Risk Per Trade:</span>
                        <span class="font-semibold text-gray-900">1.0%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Daily Risk Limit:</span>
                        <span class="font-semibold text-yellow-600">5.0%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Max Positions:</span>
                        <span class="font-semibold text-gray-900">5</span>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">Status:</span>
                            <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
