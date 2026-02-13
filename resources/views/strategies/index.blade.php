@extends('layouts.app')

@section('title', 'Strategies - FXEngine')
@section('page-title', 'Strategies')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Trading Strategies</h2>
            <p class="text-sm text-gray-600 mt-1">Manage and monitor your trading strategies</p>
        </div>
        <a href="{{ route('strategies.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Strategy
        </a>
    </div>

    <!-- Strategy Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Strategies</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">6</p>
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
                    <p class="text-sm text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">4</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Best Performer</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">+15.2%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
    <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Signals</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">127</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Strategies Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- EMA Crossover -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">EMA Crossover</h3>
                    <p class="text-sm text-gray-600 mt-1">Fast and slow EMA crossover signals</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Win Rate:</span>
                    <span class="font-semibold text-green-600">68.5%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Trades:</span>
                    <span class="font-semibold">42</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Profit:</span>
                    <span class="font-semibold text-green-600">+$1,250.00</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex space-x-2">
                    <a href="{{ route('strategies.performance') }}" class="btn btn-secondary flex-1 text-sm">View</a>
                    <a href="{{ route('strategies.backtesting') }}" class="btn btn-secondary flex-1 text-sm">Backtest</a>
                </div>
            </div>
        </div>

        <!-- RSI Strategy -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">RSI Strategy</h3>
                    <p class="text-sm text-gray-600 mt-1">Oversold/Overbought RSI signals</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Win Rate:</span>
                    <span class="font-semibold text-green-600">72.3%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Trades:</span>
                    <span class="font-semibold">38</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Profit:</span>
                    <span class="font-semibold text-green-600">+$980.50</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex space-x-2">
                    <a href="{{ route('strategies.performance') }}" class="btn btn-secondary flex-1 text-sm">View</a>
                    <a href="{{ route('strategies.backtesting') }}" class="btn btn-secondary flex-1 text-sm">Backtest</a>
                </div>
            </div>
        </div>

        <!-- MACD Crossover -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">MACD Crossover</h3>
                    <p class="text-sm text-gray-600 mt-1">MACD line crossover signals</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Win Rate:</span>
                    <span class="font-semibold text-green-600">65.2%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Trades:</span>
                    <span class="font-semibold">23</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Profit:</span>
                    <span class="font-semibold text-green-600">+$650.00</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex space-x-2">
                    <a href="{{ route('strategies.performance') }}" class="btn btn-secondary flex-1 text-sm">View</a>
                    <a href="{{ route('strategies.backtesting') }}" class="btn btn-secondary flex-1 text-sm">Backtest</a>
                </div>
            </div>
        </div>

        <!-- Support/Resistance -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Support/Resistance</h3>
                    <p class="text-sm text-gray-600 mt-1">Price action at key levels</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Win Rate:</span>
                    <span class="font-semibold text-gray-600">58.7%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Trades:</span>
                    <span class="font-semibold">15</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Profit:</span>
                    <span class="font-semibold text-red-600">-$120.00</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex space-x-2">
                    <a href="{{ route('strategies.performance') }}" class="btn btn-secondary flex-1 text-sm">View</a>
                    <a href="{{ route('strategies.backtesting') }}" class="btn btn-secondary flex-1 text-sm">Backtest</a>
                </div>
            </div>
        </div>

        <!-- Bollinger Bands -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Bollinger Bands</h3>
                    <p class="text-sm text-gray-600 mt-1">Volatility-based signals</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Win Rate:</span>
                    <span class="font-semibold text-gray-600">--</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Trades:</span>
                    <span class="font-semibold">0</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Profit:</span>
                    <span class="font-semibold text-gray-600">--</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex space-x-2">
                    <a href="{{ route('strategies.performance') }}" class="btn btn-secondary flex-1 text-sm">View</a>
                    <a href="{{ route('strategies.backtesting') }}" class="btn btn-secondary flex-1 text-sm">Backtest</a>
                </div>
            </div>
        </div>

        <!-- MA Convergence -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">MA Convergence</h3>
                    <p class="text-sm text-gray-600 mt-1">Golden/Death cross signals</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Win Rate:</span>
                    <span class="font-semibold text-green-600">75.0%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Trades:</span>
                    <span class="font-semibold">8</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Profit:</span>
                    <span class="font-semibold text-green-600">+$450.00</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex space-x-2">
                    <a href="{{ route('strategies.performance') }}" class="btn btn-secondary flex-1 text-sm">View</a>
                    <a href="{{ route('strategies.backtesting') }}" class="btn btn-secondary flex-1 text-sm">Backtest</a>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('title', 'Strategies - FXEngine')
@section('page-title', 'Strategies')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Trading Strategies</h2>
            <p class="text-sm text-gray-600 mt-1">Manage and monitor your trading strategies</p>
        </div>
        <a href="{{ route('strategies.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Strategy
        </a>
    </div>

    <!-- Strategy Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Strategies</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">6</p>
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
                    <p class="text-sm text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">4</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Best Performer</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">+15.2%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Signals</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">127</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Strategies Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- EMA Crossover -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">EMA Crossover</h3>
                    <p class="text-sm text-gray-600 mt-1">Fast and slow EMA crossover signals</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Win Rate:</span>
                    <span class="font-semibold text-green-600">68.5%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Trades:</span>
                    <span class="font-semibold">42</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Profit:</span>
                    <span class="font-semibold text-green-600">+$1,250.00</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex space-x-2">
                    <a href="{{ route('strategies.performance') }}" class="btn btn-secondary flex-1 text-sm">View</a>
                    <a href="{{ route('strategies.backtesting') }}" class="btn btn-secondary flex-1 text-sm">Backtest</a>
                </div>
            </div>
        </div>

        <!-- RSI Strategy -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">RSI Strategy</h3>
                    <p class="text-sm text-gray-600 mt-1">Oversold/Overbought RSI signals</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Win Rate:</span>
                    <span class="font-semibold text-green-600">72.3%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Trades:</span>
                    <span class="font-semibold">38</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Profit:</span>
                    <span class="font-semibold text-green-600">+$980.50</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex space-x-2">
                    <a href="{{ route('strategies.performance') }}" class="btn btn-secondary flex-1 text-sm">View</a>
                    <a href="{{ route('strategies.backtesting') }}" class="btn btn-secondary flex-1 text-sm">Backtest</a>
                </div>
            </div>
        </div>

        <!-- MACD Crossover -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">MACD Crossover</h3>
                    <p class="text-sm text-gray-600 mt-1">MACD line crossover signals</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Win Rate:</span>
                    <span class="font-semibold text-green-600">65.2%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Trades:</span>
                    <span class="font-semibold">23</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Profit:</span>
                    <span class="font-semibold text-green-600">+$650.00</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex space-x-2">
                    <a href="{{ route('strategies.performance') }}" class="btn btn-secondary flex-1 text-sm">View</a>
                    <a href="{{ route('strategies.backtesting') }}" class="btn btn-secondary flex-1 text-sm">Backtest</a>
                </div>
            </div>
        </div>

        <!-- Support/Resistance -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Support/Resistance</h3>
                    <p class="text-sm text-gray-600 mt-1">Price action at key levels</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Win Rate:</span>
                    <span class="font-semibold text-gray-600">58.7%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Trades:</span>
                    <span class="font-semibold">15</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Profit:</span>
                    <span class="font-semibold text-red-600">-$120.00</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex space-x-2">
                    <a href="{{ route('strategies.performance') }}" class="btn btn-secondary flex-1 text-sm">View</a>
                    <a href="{{ route('strategies.backtesting') }}" class="btn btn-secondary flex-1 text-sm">Backtest</a>
                </div>
            </div>
        </div>

        <!-- Bollinger Bands -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Bollinger Bands</h3>
                    <p class="text-sm text-gray-600 mt-1">Volatility-based signals</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Win Rate:</span>
                    <span class="font-semibold text-gray-600">--</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Trades:</span>
                    <span class="font-semibold">0</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Profit:</span>
                    <span class="font-semibold text-gray-600">--</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex space-x-2">
                    <a href="{{ route('strategies.performance') }}" class="btn btn-secondary flex-1 text-sm">View</a>
                    <a href="{{ route('strategies.backtesting') }}" class="btn btn-secondary flex-1 text-sm">Backtest</a>
                </div>
            </div>
        </div>

        <!-- MA Convergence -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">MA Convergence</h3>
                    <p class="text-sm text-gray-600 mt-1">Golden/Death cross signals</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Win Rate:</span>
                    <span class="font-semibold text-green-600">75.0%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Trades:</span>
                    <span class="font-semibold">8</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Profit:</span>
                    <span class="font-semibold text-green-600">+$450.00</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex space-x-2">
                    <a href="{{ route('strategies.performance') }}" class="btn btn-secondary flex-1 text-sm">View</a>
                    <a href="{{ route('strategies.backtesting') }}" class="btn btn-secondary flex-1 text-sm">Backtest</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
