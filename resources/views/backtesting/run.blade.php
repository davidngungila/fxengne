@extends('layouts.app')

@section('title', 'Run Backtest - FXEngine')
@section('page-title', 'Run Backtest')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Run Backtest</h2>
        <p class="text-sm text-gray-600 mt-1">Configure and execute strategy backtests</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Backtest Configuration</h3>
                <form id="backtestForm" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Strategy</label>
                            <select class="form-input">
                                <option>EMA Crossover</option>
                                <option>RSI Strategy</option>
                                <option>MACD Crossover</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Instrument</label>
                            <select class="form-input">
                                <option>EUR/USD</option>
                                <option>GBP/USD</option>
                                <option>XAU/USD</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Initial Capital</label>
                        <input type="number" class="form-input" value="10000">
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Run Backtest</button>
                </form>
            </div>
        </div>
        <div>
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Tips</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li>• Use at least 3 months of data</li>
                    <li>• Test multiple timeframes</li>
                    <li>• Include transaction costs</li>
                    <li>• Review drawdown periods</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

