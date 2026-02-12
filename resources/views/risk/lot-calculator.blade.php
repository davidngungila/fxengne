@extends('layouts.app')

@section('title', 'Lot Size Calculator - FxEngne')
@section('page-title', 'Lot Size Calculator')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Lot Size Calculator</h2>
        <p class="text-sm text-gray-600 mt-1">Calculate optimal position sizes based on risk management</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Calculator Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Position Size Calculator</h3>
                <form id="lotCalculatorForm" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Account Balance ($)</label>
                            <input type="number" id="accountBalance" class="form-input" value="10000" min="100" step="100">
                        </div>
                        <div>
                            <label class="form-label">Risk Percentage (%)</label>
                            <input type="number" id="riskPercent" class="form-input" value="1.0" min="0.1" max="10" step="0.1">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Instrument</label>
                        <select id="instrument" class="form-input">
                            <option value="EUR_USD">EUR/USD</option>
                            <option value="GBP_USD">GBP/USD</option>
                            <option value="USD_JPY">USD/JPY</option>
                            <option value="USD_CHF">USD/CHF</option>
                            <option value="AUD_USD">AUD/USD</option>
                            <option value="USD_CAD">USD/CAD</option>
                            <option value="NZD_USD">NZD/USD</option>
                            <option value="XAU_USD">XAU/USD (Gold)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Entry Price</label>
                            <input type="number" id="entryPrice" class="form-input" value="1.0850" step="0.00001">
                        </div>
                        <div>
                            <label class="form-label">Stop Loss Price</label>
                            <input type="number" id="stopLoss" class="form-input" value="1.0800" step="0.00001">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Account Currency</label>
                        <select id="accountCurrency" class="form-input">
                            <option value="USD" selected>USD</option>
                            <option value="EUR">EUR</option>
                            <option value="GBP">GBP</option>
                        </select>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Risk Amount:</span>
                            <span class="font-semibold text-gray-900" id="riskAmount">$100.00</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Stop Loss Distance:</span>
                            <span class="font-semibold text-gray-900" id="stopLossDistance">50 pips</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Position Size:</span>
                            <span class="font-semibold text-green-600 text-lg" id="positionSize">20,000 units</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Lot Size:</span>
                            <span class="font-semibold text-purple-600 text-lg" id="lotSize">0.20 lots</span>
                        </div>
                    </div>

                    <button type="button" id="calculateBtn" class="btn btn-primary w-full">Calculate</button>
                </form>
            </div>

            <!-- Risk/Reward Calculator -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Risk/Reward Calculator</h3>
                <form class="space-y-4">
                    <div>
                        <label class="form-label">Take Profit Price</label>
                        <input type="number" id="takeProfit" class="form-input" value="1.0950" step="0.00001">
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Risk:</span>
                            <span class="font-semibold text-red-600">$100.00</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Reward:</span>
                            <span class="font-semibold text-green-600">$200.00</span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-green-200">
                            <span class="text-sm font-medium text-gray-700">Risk/Reward Ratio:</span>
                            <span class="font-semibold text-gray-900 text-lg">1:2.0</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Reference -->
        <div class="space-y-6">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Reference</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="font-medium text-gray-900 mb-1">Standard Lot</p>
                        <p class="text-gray-600">100,000 units</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 mb-1">Mini Lot</p>
                        <p class="text-gray-600">10,000 units</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 mb-1">Micro Lot</p>
                        <p class="text-gray-600">1,000 units</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 mb-1">Nano Lot</p>
                        <p class="text-gray-600">100 units</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pip Value</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">EUR/USD:</span>
                        <span class="font-semibold">$10 per lot</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">GBP/USD:</span>
                        <span class="font-semibold">$10 per lot</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">USD/JPY:</span>
                        <span class="font-semibold">$9.09 per lot</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">XAU/USD:</span>
                        <span class="font-semibold">$1 per pip</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Risk Guidelines</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Always use stop loss
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Risk 1-2% per trade
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Minimum 1:2 R/R ratio
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function calculateLotSize() {
        const balance = parseFloat(document.getElementById('accountBalance').value);
        const riskPercent = parseFloat(document.getElementById('riskPercent').value);
        const entryPrice = parseFloat(document.getElementById('entryPrice').value);
        const stopLoss = parseFloat(document.getElementById('stopLoss').value);
        const instrument = document.getElementById('instrument').value;

        const riskAmount = balance * (riskPercent / 100);
        const stopLossDistance = Math.abs(entryPrice - stopLoss);

        // Calculate pip value (simplified - assumes USD quote currency for most pairs)
        let pipValue = 0.0001;
        if (instrument === 'USD_JPY') {
            pipValue = 0.01;
        } else if (instrument === 'XAU_USD') {
            pipValue = 0.1;
        }

        const pips = stopLossDistance / pipValue;
        const positionSize = Math.floor((riskAmount / stopLossDistance) * entryPrice);
        const lotSize = (positionSize / 100000).toFixed(2);

        document.getElementById('riskAmount').textContent = '$' + riskAmount.toFixed(2);
        document.getElementById('stopLossDistance').textContent = pips.toFixed(1) + ' pips';
        document.getElementById('positionSize').textContent = positionSize.toLocaleString() + ' units';
        document.getElementById('lotSize').textContent = lotSize + ' lots';

        // Calculate R/R if take profit is set
        const takeProfit = parseFloat(document.getElementById('takeProfit').value);
        if (takeProfit && takeProfit !== entryPrice) {
            const rewardDistance = Math.abs(takeProfit - entryPrice);
            const rewardAmount = (rewardDistance / stopLossDistance) * riskAmount;
            const rrRatio = (rewardDistance / stopLossDistance).toFixed(2);
            
            // Update R/R display if elements exist
            const rewardEl = document.querySelector('.bg-green-50 .text-green-600');
            const rrEl = document.querySelector('.bg-green-50 .text-lg');
            if (rewardEl) rewardEl.textContent = '$' + rewardAmount.toFixed(2);
            if (rrEl) rrEl.textContent = '1:' + rrRatio;
        }
    }

    document.getElementById('calculateBtn').addEventListener('click', calculateLotSize);
    
    // Auto-calculate on input change
    ['accountBalance', 'riskPercent', 'entryPrice', 'stopLoss', 'takeProfit'].forEach(id => {
        document.getElementById(id).addEventListener('input', calculateLotSize);
    });

    // Initial calculation
    calculateLotSize();
});
</script>
@endpush
@endsection
