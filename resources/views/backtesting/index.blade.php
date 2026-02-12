@extends('layouts.app')

@section('title', 'Backtesting - FxEngne')
@section('page-title', 'Backtesting')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Backtesting Dashboard</h2>
            <p class="text-sm text-gray-600 mt-1">Test strategies with historical data</p>
        </div>
        <a href="{{ route('backtesting.run') }}" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Run Backtest
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Backtests</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">24</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Best Result</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">+18.5%</p>
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
                    <p class="text-sm text-gray-600">Avg Win Rate</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">68.2%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Running</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">2</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <a href="{{ route('backtesting.run') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Run Backtest</h3>
                <p class="text-sm text-gray-600 mt-1">Test strategies</p>
            </div>
        </a>

        <a href="{{ route('backtesting.historical-data') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Historical Data</h3>
                <p class="text-sm text-gray-600 mt-1">Manage data</p>
            </div>
        </a>

        <a href="{{ route('backtesting.reports') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Reports</h3>
                <p class="text-sm text-gray-600 mt-1">View results</p>
            </div>
        </a>

        <a href="{{ route('backtesting.compare') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Compare</h3>
                <p class="text-sm text-gray-600 mt-1">Compare strategies</p>
            </div>
        </a>
    </div>

    <!-- Recent Backtests -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Backtests</h3>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Strategy</th>
                        <th>Instrument</th>
                        <th>Period</th>
                        <th>Trades</th>
                        <th>Win Rate</th>
                        <th>Return</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">EMA Crossover</td>
                        <td>EUR/USD</td>
                        <td class="text-sm text-gray-600">2024-01-01 to 2024-01-31</td>
                        <td>42</td>
                        <td><span class="text-green-600 font-semibold">68.5%</span></td>
                        <td class="text-green-600 font-semibold">+15.2%</td>
                        <td><span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Completed</span></td>
                        <td><a href="{{ route('backtesting.reports') }}" class="text-blue-600 hover:text-blue-700 text-sm">View</a></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">RSI Strategy</td>
                        <td>GBP/USD</td>
                        <td class="text-sm text-gray-600">2024-01-01 to 2024-01-31</td>
                        <td>38</td>
                        <td><span class="text-green-600 font-semibold">72.3%</span></td>
                        <td class="text-green-600 font-semibold">+12.8%</td>
                        <td><span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Completed</span></td>
                        <td><a href="{{ route('backtesting.reports') }}" class="text-blue-600 hover:text-blue-700 text-sm">View</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
