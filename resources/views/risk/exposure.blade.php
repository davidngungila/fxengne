@extends('layouts.app')

@section('title', 'Exposure Control - FXEngine')
@section('page-title', 'Exposure Control')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Exposure Control</h2>
        <p class="text-sm text-gray-600 mt-1">Monitor and manage your trading exposure across instruments</p>
    </div>

    <!-- Exposure Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Exposure</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">$9,500</p>
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
                    <p class="text-sm text-gray-600">Max Exposure</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">$15,000</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Exposure %</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">63.3%</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Open Positions</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">5</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Exposure by Instrument -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Exposure by Instrument</h3>
        <div class="space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-gray-900">EUR/USD</span>
                        <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-800">Long</span>
                    </div>
                    <span class="font-semibold text-gray-900">$2,500 (26.3%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full" style="width: 26.3%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-gray-900">GBP/USD</span>
                        <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-800">Short</span>
                    </div>
                    <span class="font-semibold text-gray-900">$1,800 (18.9%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-600 h-3 rounded-full" style="width: 18.9%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-gray-900">XAU/USD</span>
                        <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-800">Long</span>
                    </div>
                    <span class="font-semibold text-gray-900">$5,200 (54.7%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-yellow-600 h-3 rounded-full" style="width: 54.7%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exposure Configuration -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Exposure Limits</h3>
            <form class="space-y-4">
                <div>
                    <label class="form-label">Maximum Total Exposure ($)</label>
                    <input type="number" class="form-input" value="15000" min="1000" step="500">
                </div>
                <div>
                    <label class="form-label">Maximum Exposure per Instrument (%)</label>
                    <input type="number" class="form-input" value="50" min="10" max="100" step="5">
                </div>
                <div>
                    <label class="form-label">Maximum Open Positions</label>
                    <input type="number" class="form-input" value="10" min="1" max="50" step="1">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="autoLimitExposure" class="form-checkbox" checked>
                    <label for="autoLimitExposure" class="ml-2 text-sm text-gray-700">Auto-limit exposure when threshold reached</label>
                </div>
            </form>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Exposure Alerts</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Total Exposure</p>
                            <p class="text-sm text-gray-600">$9,500 / $15,000 (63.3%)</p>
                        </div>
                    </div>
                    <span class="text-sm text-green-600 font-semibold">Safe</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">XAU/USD Exposure</p>
                            <p class="text-sm text-gray-600">54.7% (Limit: 50%)</p>
                        </div>
                    </div>
                    <span class="text-sm text-yellow-600 font-semibold">Warning</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Exposure Table -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detailed Exposure</h3>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Instrument</th>
                        <th>Position</th>
                        <th>Units</th>
                        <th>Entry Price</th>
                        <th>Current Price</th>
                        <th>Exposure</th>
                        <th>% of Total</th>
                        <th>P/L</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">EUR/USD</td>
                        <td><span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Long</span></td>
                        <td class="font-mono text-sm">10,000</td>
                        <td class="font-mono text-sm">1.0850</td>
                        <td class="font-mono text-sm">1.0865</td>
                        <td class="font-semibold">$2,500</td>
                        <td class="text-sm">26.3%</td>
                        <td class="text-green-600 font-semibold">+$15.00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">GBP/USD</td>
                        <td><span class="px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">Short</span></td>
                        <td class="font-mono text-sm">-8,000</td>
                        <td class="font-mono text-sm">1.2650</td>
                        <td class="font-mono text-sm">1.2625</td>
                        <td class="font-semibold">$1,800</td>
                        <td class="text-sm">18.9%</td>
                        <td class="text-green-600 font-semibold">+$20.00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">XAU/USD</td>
                        <td><span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Long</span></td>
                        <td class="font-mono text-sm">5</td>
                        <td class="font-mono text-sm">2,640.00</td>
                        <td class="font-mono text-sm">2,645.00</td>
                        <td class="font-semibold">$5,200</td>
                        <td class="text-sm">54.7%</td>
                        <td class="text-green-600 font-semibold">+$25.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex space-x-3">
        <button class="btn btn-primary flex-1">Save Settings</button>
        <button class="btn btn-secondary">Export Report</button>
    </div>
</div>
@endsection

@section('title', 'Exposure Control - FXEngine')
@section('page-title', 'Exposure Control')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Exposure Control</h2>
        <p class="text-sm text-gray-600 mt-1">Monitor and manage your trading exposure across instruments</p>
    </div>

    <!-- Exposure Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Exposure</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">$9,500</p>
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
                    <p class="text-sm text-gray-600">Max Exposure</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">$15,000</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Exposure %</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">63.3%</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Open Positions</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">5</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Exposure by Instrument -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Exposure by Instrument</h3>
        <div class="space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-gray-900">EUR/USD</span>
                        <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-800">Long</span>
                    </div>
                    <span class="font-semibold text-gray-900">$2,500 (26.3%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full" style="width: 26.3%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-gray-900">GBP/USD</span>
                        <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-800">Short</span>
                    </div>
                    <span class="font-semibold text-gray-900">$1,800 (18.9%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-600 h-3 rounded-full" style="width: 18.9%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-gray-900">XAU/USD</span>
                        <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-800">Long</span>
                    </div>
                    <span class="font-semibold text-gray-900">$5,200 (54.7%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-yellow-600 h-3 rounded-full" style="width: 54.7%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exposure Configuration -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Exposure Limits</h3>
            <form class="space-y-4">
                <div>
                    <label class="form-label">Maximum Total Exposure ($)</label>
                    <input type="number" class="form-input" value="15000" min="1000" step="500">
                </div>
                <div>
                    <label class="form-label">Maximum Exposure per Instrument (%)</label>
                    <input type="number" class="form-input" value="50" min="10" max="100" step="5">
                </div>
                <div>
                    <label class="form-label">Maximum Open Positions</label>
                    <input type="number" class="form-input" value="10" min="1" max="50" step="1">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="autoLimitExposure" class="form-checkbox" checked>
                    <label for="autoLimitExposure" class="ml-2 text-sm text-gray-700">Auto-limit exposure when threshold reached</label>
                </div>
            </form>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Exposure Alerts</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Total Exposure</p>
                            <p class="text-sm text-gray-600">$9,500 / $15,000 (63.3%)</p>
                        </div>
                    </div>
                    <span class="text-sm text-green-600 font-semibold">Safe</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">XAU/USD Exposure</p>
                            <p class="text-sm text-gray-600">54.7% (Limit: 50%)</p>
                        </div>
                    </div>
                    <span class="text-sm text-yellow-600 font-semibold">Warning</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Exposure Table -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detailed Exposure</h3>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Instrument</th>
                        <th>Position</th>
                        <th>Units</th>
                        <th>Entry Price</th>
                        <th>Current Price</th>
                        <th>Exposure</th>
                        <th>% of Total</th>
                        <th>P/L</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">EUR/USD</td>
                        <td><span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Long</span></td>
                        <td class="font-mono text-sm">10,000</td>
                        <td class="font-mono text-sm">1.0850</td>
                        <td class="font-mono text-sm">1.0865</td>
                        <td class="font-semibold">$2,500</td>
                        <td class="text-sm">26.3%</td>
                        <td class="text-green-600 font-semibold">+$15.00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">GBP/USD</td>
                        <td><span class="px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">Short</span></td>
                        <td class="font-mono text-sm">-8,000</td>
                        <td class="font-mono text-sm">1.2650</td>
                        <td class="font-mono text-sm">1.2625</td>
                        <td class="font-semibold">$1,800</td>
                        <td class="text-sm">18.9%</td>
                        <td class="text-green-600 font-semibold">+$20.00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">XAU/USD</td>
                        <td><span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Long</span></td>
                        <td class="font-mono text-sm">5</td>
                        <td class="font-mono text-sm">2,640.00</td>
                        <td class="font-mono text-sm">2,645.00</td>
                        <td class="font-semibold">$5,200</td>
                        <td class="text-sm">54.7%</td>
                        <td class="text-green-600 font-semibold">+$25.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex space-x-3">
        <button class="btn btn-primary flex-1">Save Settings</button>
        <button class="btn btn-secondary">Export Report</button>
    </div>
</div>
@endsection
