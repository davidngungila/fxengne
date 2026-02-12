@extends('layouts.app')

@section('title', 'Manual Entry - FXEngine')
@section('page-title', 'Manual Entry')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Manual Trade Entry</h2>
        <p class="text-sm text-gray-600 mt-1">Place trades manually with full control</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Trade Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Trade Details</h3>
                <form id="tradeForm" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Instrument</label>
                            <select id="instrument" class="form-input" required>
                                <option value="">Select Instrument</option>
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
                        <div>
                            <label class="form-label">Direction</label>
                            <select id="direction" class="form-input" required>
                                <option value="BUY">BUY</option>
                                <option value="SELL">SELL</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Units / Lot Size</label>
                        <input type="number" id="units" class="form-input" value="1000" min="1" step="1" required>
                        <p class="text-xs text-gray-500 mt-1">Minimum: 1 unit</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Stop Loss</label>
                            <input type="number" id="stopLoss" class="form-input" step="0.00001" placeholder="Optional">
                        </div>
                        <div>
                            <label class="form-label">Take Profit</label>
                            <input type="number" id="takeProfit" class="form-input" step="0.00001" placeholder="Optional">
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Current Price:</span>
                            <span class="font-mono font-semibold text-gray-900" id="currentPrice">--</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Spread:</span>
                            <span class="font-mono text-sm text-gray-600" id="spread">--</span>
                        </div>
                    </div>

                    <div id="tradeError" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm"></div>
                    <div id="tradeSuccess" class="hidden p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm"></div>

                    <div class="flex space-x-3">
                        <button type="submit" class="btn btn-primary flex-1">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Execute Trade
                        </button>
                        <button type="button" id="calculateRisk" class="btn btn-secondary">
                            Calculate Risk
                        </button>
                    </div>
                </form>
            </div>

            <!-- Risk Calculator -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Risk Calculator</h3>
                <div class="space-y-3">
                    <div>
                        <label class="form-label">Account Balance</label>
                        <input type="number" id="accountBalance" class="form-input" value="10000" step="0.01">
                    </div>
                    <div>
                        <label class="form-label">Risk Percentage (%)</label>
                        <input type="number" id="riskPercent" class="form-input" value="2" min="0.1" max="10" step="0.1">
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Risk Amount:</span>
                            <span class="font-semibold text-gray-900" id="riskAmount">$0.00</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Recommended Units:</span>
                            <span class="font-semibold text-gray-900" id="recommendedUnits">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Market Info Sidebar -->
        <div class="space-y-6">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Market Information</h3>
                <div id="marketInfo" class="space-y-3">
                    <p class="text-sm text-gray-500 text-center py-4">Select an instrument to view market data</p>
                </div>
            </div>

            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('trading.open-trades') }}" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">View Open Trades</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                    <a href="{{ route('trading.history') }}" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">Trade History</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';

    // Load market data when instrument changes
    document.getElementById('instrument').addEventListener('change', async function() {
        const instrument = this.value;
        if (!instrument) return;

        try {
            const response = await fetch(`${API_BASE_URL}/market/prices?instruments=${instrument}`);
            const result = await response.json();

            if (result.success && result.data && result.data.length > 0) {
                const price = result.data[0];
                const bid = parseFloat(price.bids[0].price);
                const ask = parseFloat(price.asks[0].price);
                const spread = ask - bid;

                document.getElementById('currentPrice').textContent = 
                    document.getElementById('direction').value === 'BUY' ? '$' + ask.toFixed(5) : '$' + bid.toFixed(5);
                document.getElementById('spread').textContent = (spread * 10000).toFixed(1) + ' pips';

                // Update market info
                updateMarketInfo(price);
            }
        } catch (error) {
            console.error('Error loading market data:', error);
        }
    });

    function updateMarketInfo(price) {
        const marketInfo = document.getElementById('marketInfo');
        const bid = parseFloat(price.bids[0].price);
        const ask = parseFloat(price.asks[0].price);
        const spread = ask - bid;

        marketInfo.innerHTML = `
            <div class="space-y-2 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Bid:</span>
                    <span class="font-mono font-semibold text-red-600">$${bid.toFixed(5)}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Ask:</span>
                    <span class="font-mono font-semibold text-green-600">$${ask.toFixed(5)}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Spread:</span>
                    <span class="font-mono font-semibold text-gray-900">${(spread * 10000).toFixed(1)} pips</span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                    <span class="text-gray-600">Time:</span>
                    <span class="text-xs text-gray-500">${new Date(price.time).toLocaleTimeString()}</span>
                </div>
            </div>
        `;
    }

    // Calculate risk
    document.getElementById('calculateRisk').addEventListener('click', function() {
        const balance = parseFloat(document.getElementById('accountBalance').value);
        const riskPercent = parseFloat(document.getElementById('riskPercent').value);
        const riskAmount = balance * (riskPercent / 100);

        document.getElementById('riskAmount').textContent = '$' + riskAmount.toFixed(2);

        // Calculate recommended units based on stop loss
        const stopLoss = parseFloat(document.getElementById('stopLoss').value);
        const currentPrice = parseFloat(document.getElementById('currentPrice').textContent.replace('$', ''));
        
        if (stopLoss && currentPrice) {
            const stopLossDistance = Math.abs(currentPrice - stopLoss);
            const recommendedUnits = Math.floor(riskAmount / stopLossDistance);
            document.getElementById('recommendedUnits').textContent = recommendedUnits;
        }
    });

    // Form submission
    document.getElementById('tradeForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const errorDiv = document.getElementById('tradeError');
        const successDiv = document.getElementById('tradeSuccess');
        errorDiv.classList.add('hidden');
        successDiv.classList.add('hidden');

        const formData = {
            instrument: document.getElementById('instrument').value,
            units: parseInt(document.getElementById('units').value),
            side: document.getElementById('direction').value,
            stop_loss: document.getElementById('stopLoss').value || null,
            take_profit: document.getElementById('takeProfit').value || null
        };

        try {
            const response = await fetch(`${API_BASE_URL}/trade/execute`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.success) {
                successDiv.textContent = 'Trade executed successfully!';
                successDiv.classList.remove('hidden');
                this.reset();
                setTimeout(() => {
                    window.location.href = '{{ route("trading.open-trades") }}';
                }, 2000);
            } else {
                errorDiv.textContent = result.message || 'Trade execution failed';
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            errorDiv.textContent = 'Error executing trade: ' + error.message;
            errorDiv.classList.remove('hidden');
        }
    });
});
</script>
@endpush
@endsection
