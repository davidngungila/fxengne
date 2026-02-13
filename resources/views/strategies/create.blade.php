@extends('layouts.app')

@section('title', 'Create Strategy - FXEngine')
@section('page-title', 'Create Strategy')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Create New Strategy</h2>
        <p class="text-sm text-gray-600 mt-1">Build custom trading strategies with technical indicators</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Strategy Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Strategy Configuration</h3>
                <form id="strategyForm" class="space-y-4">
                    <div>
                        <label class="form-label">Strategy Name</label>
                        <input type="text" id="strategyName" class="form-input" placeholder="e.g., My Custom Strategy" required>
                    </div>

                    <div>
                        <label class="form-label">Description</label>
                        <textarea id="strategyDescription" class="form-input" rows="3" placeholder="Describe your strategy..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Timeframe</label>
                            <select id="timeframe" class="form-input" required>
                                <option value="M1">1 Minute</option>
                                <option value="M5">5 Minutes</option>
                                <option value="M15">15 Minutes</option>
                                <option value="H1" selected>1 Hour</option>
                                <option value="H4">4 Hours</option>
                                <option value="D">Daily</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Instruments</label>
                            <select id="instruments" class="form-input" multiple>
                                <option value="EUR_USD" selected>EUR/USD</option>
                                <option value="GBP_USD">GBP/USD</option>
                                <option value="USD_JPY">USD/JPY</option>
                                <option value="XAU_USD">XAU/USD</option>
                            </select>
                        </div>
                    </div>

                    <!-- Indicators -->
                    <div>
                        <label class="form-label mb-3 block">Technical Indicators</label>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="indicators" value="ema" class="form-checkbox">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-900">EMA (Exponential Moving Average)</div>
                                    <div class="text-sm text-gray-600">Fast: <input type="number" class="form-input inline w-20 ml-2" value="9"> Slow: <input type="number" class="form-input inline w-20 ml-2" value="21"></div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="indicators" value="rsi" class="form-checkbox">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-900">RSI (Relative Strength Index)</div>
                                    <div class="text-sm text-gray-600">Period: <input type="number" class="form-input inline w-20 ml-2" value="14"> Oversold: <input type="number" class="form-input inline w-20 ml-2" value="30"> Overbought: <input type="number" class="form-input inline w-20 ml-2" value="70"></div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="indicators" value="macd" class="form-checkbox">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-900">MACD (Moving Average Convergence Divergence)</div>
                                    <div class="text-sm text-gray-600">Fast: <input type="number" class="form-input inline w-20 ml-2" value="12"> Slow: <input type="number" class="form-input inline w-20 ml-2" value="26"> Signal: <input type="number" class="form-input inline w-20 ml-2" value="9"></div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="indicators" value="bollinger" class="form-checkbox">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-900">Bollinger Bands</div>
                                    <div class="text-sm text-gray-600">Period: <input type="number" class="form-input inline w-20 ml-2" value="20"> Std Dev: <input type="number" class="form-input inline w-20 ml-2" value="2"></div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Entry Conditions -->
                    <div>
                        <label class="form-label mb-3 block">Entry Conditions</label>
                        <div class="space-y-2">
                            <select id="entryCondition" class="form-input">
                                <option value="crossover">EMA Crossover</option>
                                <option value="rsi_oversold">RSI Oversold</option>
                                <option value="rsi_overbought">RSI Overbought</option>
                                <option value="macd_cross">MACD Crossover</option>
                                <option value="bb_touch">Bollinger Band Touch</option>
                            </select>
                        </div>
                    </div>

                    <!-- Risk Management -->
                    <div>
                        <label class="form-label mb-3 block">Risk Management</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Stop Loss (%)</label>
                                <input type="number" id="stopLoss" class="form-input" value="1" min="0.1" max="10" step="0.1">
                            </div>
                            <div>
                                <label class="form-label">Take Profit (%)</label>
                                <input type="number" id="takeProfit" class="form-input" value="2" min="0.1" max="20" step="0.1">
                            </div>
                            <div>
                                <label class="form-label">Risk per Trade (%)</label>
                                <input type="number" id="riskPerTrade" class="form-input" value="2" min="0.5" max="10" step="0.1">
                            </div>
                            <div>
                                <label class="form-label">Max Trades/Day</label>
                                <input type="number" id="maxTrades" class="form-input" value="5" min="1" max="50">
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="btn btn-primary flex-1">Create Strategy</button>
                        <button type="button" class="btn btn-secondary">Save as Draft</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Strategy Preview -->
        <div class="space-y-6">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Strategy Preview</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Name:</span>
                        <span class="font-medium ml-2" id="previewName">--</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Timeframe:</span>
                        <span class="font-medium ml-2" id="previewTimeframe">--</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Indicators:</span>
                        <div class="mt-1" id="previewIndicators">--</div>
                    </div>
                </div>
            </div>

    <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Tips</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Test strategies with backtesting before live trading
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Use multiple indicators for better signal confirmation
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Set appropriate stop loss and take profit levels
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update preview
    document.getElementById('strategyName').addEventListener('input', function() {
        document.getElementById('previewName').textContent = this.value || '--';
    });

    document.getElementById('timeframe').addEventListener('change', function() {
        document.getElementById('previewTimeframe').textContent = this.options[this.selectedIndex].text;
    });

    // Form submission
    document.getElementById('strategyForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Strategy created successfully! (This is a demo)');
    });
});
</script>
@endpush
@endsection

@section('title', 'Create Strategy - FXEngine')
@section('page-title', 'Create Strategy')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Create New Strategy</h2>
        <p class="text-sm text-gray-600 mt-1">Build custom trading strategies with technical indicators</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Strategy Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Strategy Configuration</h3>
                <form id="strategyForm" class="space-y-4">
                    <div>
                        <label class="form-label">Strategy Name</label>
                        <input type="text" id="strategyName" class="form-input" placeholder="e.g., My Custom Strategy" required>
                    </div>

                    <div>
                        <label class="form-label">Description</label>
                        <textarea id="strategyDescription" class="form-input" rows="3" placeholder="Describe your strategy..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Timeframe</label>
                            <select id="timeframe" class="form-input" required>
                                <option value="M1">1 Minute</option>
                                <option value="M5">5 Minutes</option>
                                <option value="M15">15 Minutes</option>
                                <option value="H1" selected>1 Hour</option>
                                <option value="H4">4 Hours</option>
                                <option value="D">Daily</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Instruments</label>
                            <select id="instruments" class="form-input" multiple>
                                <option value="EUR_USD" selected>EUR/USD</option>
                                <option value="GBP_USD">GBP/USD</option>
                                <option value="USD_JPY">USD/JPY</option>
                                <option value="XAU_USD">XAU/USD</option>
                            </select>
                        </div>
                    </div>

                    <!-- Indicators -->
                    <div>
                        <label class="form-label mb-3 block">Technical Indicators</label>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="indicators" value="ema" class="form-checkbox">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-900">EMA (Exponential Moving Average)</div>
                                    <div class="text-sm text-gray-600">Fast: <input type="number" class="form-input inline w-20 ml-2" value="9"> Slow: <input type="number" class="form-input inline w-20 ml-2" value="21"></div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="indicators" value="rsi" class="form-checkbox">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-900">RSI (Relative Strength Index)</div>
                                    <div class="text-sm text-gray-600">Period: <input type="number" class="form-input inline w-20 ml-2" value="14"> Oversold: <input type="number" class="form-input inline w-20 ml-2" value="30"> Overbought: <input type="number" class="form-input inline w-20 ml-2" value="70"></div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="indicators" value="macd" class="form-checkbox">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-900">MACD (Moving Average Convergence Divergence)</div>
                                    <div class="text-sm text-gray-600">Fast: <input type="number" class="form-input inline w-20 ml-2" value="12"> Slow: <input type="number" class="form-input inline w-20 ml-2" value="26"> Signal: <input type="number" class="form-input inline w-20 ml-2" value="9"></div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="indicators" value="bollinger" class="form-checkbox">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-900">Bollinger Bands</div>
                                    <div class="text-sm text-gray-600">Period: <input type="number" class="form-input inline w-20 ml-2" value="20"> Std Dev: <input type="number" class="form-input inline w-20 ml-2" value="2"></div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Entry Conditions -->
                    <div>
                        <label class="form-label mb-3 block">Entry Conditions</label>
                        <div class="space-y-2">
                            <select id="entryCondition" class="form-input">
                                <option value="crossover">EMA Crossover</option>
                                <option value="rsi_oversold">RSI Oversold</option>
                                <option value="rsi_overbought">RSI Overbought</option>
                                <option value="macd_cross">MACD Crossover</option>
                                <option value="bb_touch">Bollinger Band Touch</option>
                            </select>
                        </div>
                    </div>

                    <!-- Risk Management -->
                    <div>
                        <label class="form-label mb-3 block">Risk Management</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Stop Loss (%)</label>
                                <input type="number" id="stopLoss" class="form-input" value="1" min="0.1" max="10" step="0.1">
                            </div>
                            <div>
                                <label class="form-label">Take Profit (%)</label>
                                <input type="number" id="takeProfit" class="form-input" value="2" min="0.1" max="20" step="0.1">
                            </div>
                            <div>
                                <label class="form-label">Risk per Trade (%)</label>
                                <input type="number" id="riskPerTrade" class="form-input" value="2" min="0.5" max="10" step="0.1">
                            </div>
                            <div>
                                <label class="form-label">Max Trades/Day</label>
                                <input type="number" id="maxTrades" class="form-input" value="5" min="1" max="50">
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="btn btn-primary flex-1">Create Strategy</button>
                        <button type="button" class="btn btn-secondary">Save as Draft</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Strategy Preview -->
        <div class="space-y-6">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Strategy Preview</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Name:</span>
                        <span class="font-medium ml-2" id="previewName">--</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Timeframe:</span>
                        <span class="font-medium ml-2" id="previewTimeframe">--</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Indicators:</span>
                        <div class="mt-1" id="previewIndicators">--</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Tips</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Test strategies with backtesting before live trading
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Use multiple indicators for better signal confirmation
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Set appropriate stop loss and take profit levels
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update preview
    document.getElementById('strategyName').addEventListener('input', function() {
        document.getElementById('previewName').textContent = this.value || '--';
    });

    document.getElementById('timeframe').addEventListener('change', function() {
        document.getElementById('previewTimeframe').textContent = this.options[this.selectedIndex].text;
    });

    // Form submission
    document.getElementById('strategyForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Strategy created successfully! (This is a demo)');
    });
});
</script>
@endpush
@endsection
