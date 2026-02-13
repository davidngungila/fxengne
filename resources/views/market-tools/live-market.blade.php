@extends('layouts.app')

@section('title', 'Live Market - Trading Cockpit - FXEngine')
@section('page-title', 'XAUUSD Trading Cockpit')

@section('content')
<div class="space-y-4">
    <!-- LAYER 1: GOLD ALPHA SIGNAL PANEL -->
    <div class="card border-4 {{ $goldAlphaSignal['alpha_score'] >= 8 ? 'border-red-500 animate-pulse' : ($goldAlphaSignal['alpha_score'] >= 6 ? 'border-yellow-500' : 'border-gray-300') }} bg-gradient-to-br {{ $goldAlphaSignal['direction'] === 'BUY' ? 'from-green-50 to-emerald-50' : ($goldAlphaSignal['direction'] === 'SELL' ? 'from-red-50 to-rose-50' : 'from-gray-50 to-slate-50') }}">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <h2 class="text-2xl font-bold text-gray-900">GOLD ALPHA SIGNAL</h2>
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $goldAlphaSignal['alpha_score'] >= 8 ? 'bg-red-600 text-white animate-pulse' : ($goldAlphaSignal['alpha_score'] >= 6 ? 'bg-yellow-600 text-white' : 'bg-gray-600 text-white') }}">
                    ALPHA: {{ number_format($goldAlphaSignal['alpha_score'], 1) }}/10
                </span>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Countdown to Next State</p>
                <p class="text-2xl font-bold text-gray-900" id="countdownTimer">{{ $goldAlphaSignal['countdown'] ?? '--:--' }}</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-4 border-2 {{ $goldAlphaSignal['direction'] === 'BUY' ? 'border-green-500' : ($goldAlphaSignal['direction'] === 'SELL' ? 'border-red-500' : 'border-gray-300') }}">
                <p class="text-sm text-gray-600 mb-1">Direction Signal</p>
                <p class="text-3xl font-bold {{ $goldAlphaSignal['direction'] === 'BUY' ? 'text-green-600' : ($goldAlphaSignal['direction'] === 'SELL' ? 'text-red-600' : 'text-gray-600') }}">
                    {{ $goldAlphaSignal['direction'] }}
                </p>
            </div>
            
            <div class="bg-white rounded-lg p-4 border-2 border-blue-500">
                <p class="text-sm text-gray-600 mb-1">Confidence Score</p>
                <p class="text-3xl font-bold text-blue-600">{{ number_format($goldAlphaSignal['confidence'], 0) }}%</p>
                <div class="mt-2 bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $goldAlphaSignal['confidence'] }}%"></div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg p-4 border-2 border-purple-500">
                <p class="text-sm text-gray-600 mb-1">Model State</p>
                <p class="text-lg font-bold text-purple-600">{{ str_replace('_', ' ', $goldAlphaSignal['model_state']) }}</p>
            </div>
            
            <div class="bg-white rounded-lg p-4 border-2 border-yellow-500">
                <p class="text-sm text-gray-600 mb-1">Alpha Score</p>
                <p class="text-3xl font-bold text-yellow-600">{{ number_format($goldAlphaSignal['alpha_score'], 1) }}</p>
                <p class="text-xs text-gray-500 mt-1">/ 10.0</p>
            </div>
        </div>
        
        <div class="mt-4 p-3 bg-white rounded-lg border border-gray-200">
            <p class="text-sm font-medium text-gray-900">Signal Rationale:</p>
            <p class="text-sm text-gray-700 mt-1">{{ $goldAlphaSignal['reason'] }}</p>
        </div>
    </div>

    <!-- LAYER 2: CPI EVENT COMMAND CENTER -->
    @if($cpiEvent)
    <div class="card bg-gradient-to-br from-red-50 to-orange-50 border-2 border-red-500">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">CPI EVENT COMMAND CENTER</h2>
            <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded">CRITICAL EVENT</span>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Event Data</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                        <span class="text-sm text-gray-600">Event:</span>
                        <span class="font-semibold text-gray-900">{{ $cpiEvent['event'] }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                        <span class="text-sm text-gray-600">Time:</span>
                        <span class="font-semibold text-gray-900">{{ $cpiEvent['time'] }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                        <span class="text-sm text-gray-600">Actual:</span>
                        <span class="font-bold {{ isset($eventSignal['comparison']) && $eventSignal['comparison'] === 'hotter' ? 'text-red-600' : (isset($eventSignal['comparison']) && $eventSignal['comparison'] === 'worse' ? 'text-green-600' : 'text-gray-600') }}">
                            {{ $cpiEvent['actual'] ?? 'Pending' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                        <span class="text-sm text-gray-600">Forecast:</span>
                        <span class="font-semibold text-gray-900">{{ $cpiEvent['forecast'] }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                        <span class="text-sm text-gray-600">Previous:</span>
                        <span class="font-semibold text-gray-900">{{ $cpiEvent['previous'] }}</span>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Trade Parameters</h3>
                <div class="space-y-3">
                    <div class="p-3 bg-white rounded-lg border-2 border-blue-500">
                        <p class="text-xs text-gray-600 mb-1">Net Effect</p>
                        <p class="text-lg font-bold text-blue-600">
                            @if(isset($eventSignal['comparison']) && $eventSignal['comparison'] === 'hotter')
                                Inflationary Surprise → Bearish XAUUSD
                            @elseif(isset($eventSignal['comparison']) && $eventSignal['comparison'] === 'worse')
                                Weaker Data → Bullish XAUUSD
                            @else
                                In-Line → Neutral
                            @endif
                        </p>
                    </div>
                    
                    <div class="p-3 bg-white rounded-lg">
                        <p class="text-xs text-gray-600 mb-2">Entry Zone (Fibonacci Retracement)</p>
                        <div class="space-y-1">
                            <div class="flex justify-between text-sm">
                                <span>38.2% Fib:</span>
                                <span class="font-semibold text-gray-900">${{ number_format($entryZones['38.2'], 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>50.0% Fib:</span>
                                <span class="font-semibold text-gray-900">${{ number_format($entryZones['50.0'], 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>61.8% Fib:</span>
                                <span class="font-semibold text-gray-900">${{ number_format($entryZones['61.8'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-2">
                        <div class="p-3 bg-white rounded-lg text-center">
                            <p class="text-xs text-gray-600">Stop Loss</p>
                            <p class="text-sm font-bold text-red-600">${{ number_format($postNewsHigh, 2) }}</p>
                        </div>
                        <div class="p-3 bg-white rounded-lg text-center">
                            <p class="text-xs text-gray-600">TP1</p>
                            <p class="text-sm font-bold text-green-600">${{ number_format($postNewsLow, 2) }}</p>
                        </div>
                        <div class="p-3 bg-white rounded-lg text-center">
                            <p class="text-xs text-gray-600">TP2</p>
                            <p class="text-sm font-bold text-green-600">${{ number_format($postNewsLow - 20, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- LAYER 3: LIVE TECHNICAL OVERLAY (Price Ladder) -->
    <div class="card">
        <h2 class="text-xl font-bold text-gray-900 mb-4">PRICE LADDER & POSITION SIZING</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Price Ladder -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Price Ladder</h3>
                <div class="space-y-2" id="priceLadder">
                    <!-- Price levels will be populated by JavaScript -->
                </div>
            </div>
            
            <!-- Risk Meter -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Risk Meter & Position Sizing</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-500">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Current ATR (14)</p>
                                <p class="text-xl font-bold text-gray-900">{{ number_format($atr, 2) }} pips</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Volatility Regime</p>
                                <p class="text-xl font-bold {{ $atr > 20 ? 'text-red-600' : ($atr > 15 ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ $atr > 20 ? 'High' : ($atr > 15 ? 'Medium' : 'Low') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-white rounded-lg border-2 border-green-500">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Max Risk Per Trade:</span>
                                <span class="font-semibold text-gray-900">{{ $positionSize['dollar_risk'] / 50 }}%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Recommended Lot Size:</span>
                                <span class="text-xl font-bold text-green-600">{{ number_format($positionSize['lot_size'], 2) }} lots</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Dollar Risk Amount:</span>
                                <span class="font-semibold text-gray-900">${{ number_format($positionSize['dollar_risk'], 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Risk/Reward Ratio:</span>
                                <span class="font-semibold text-gray-900">1:{{ number_format($positionSize['risk_reward_ratio'], 1) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Risk Gauge -->
                    <div class="p-4 bg-white rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Risk Level</p>
                        <div class="relative h-8 bg-gray-200 rounded-full overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-gray-700">
                                {{ $positionSize['dollar_risk'] / 50 }}% Risk
                            </div>
                            <div class="h-full bg-gradient-to-r from-green-500 via-yellow-500 to-red-500 transition-all duration-300" 
                                 style="width: {{ min(100, ($positionSize['dollar_risk'] / 50) * 10) }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LAYER 4: XAUUSD Live Chart -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">XAUUSD Live Chart</h2>
            <div class="flex items-center space-x-4 px-5 py-3 bg-gradient-to-r from-gray-800 to-gray-900 rounded-lg border border-gray-700">
                <div class="flex items-center space-x-2">
                    <div class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-medium text-green-400">Live</span>
                </div>
                <div class="border-l border-gray-600 pl-4">
                    <span class="text-xs text-gray-400">Price:</span>
                    <span class="text-xl font-bold text-white ml-2" id="xauusdPrice">${{ number_format($currentPrice, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="relative" style="height: 500px; background-color: var(--trading-bg-dark-navy); border-radius: 8px;">
            <canvas id="xauusdChart"></canvas>
        </div>
    </div>

    <!-- LAYER 5: TRADE JOURNAL & SYSTEM STATUS -->
    <div class="card">
        <h2 class="text-xl font-bold text-gray-900 mb-4">SYSTEM JOURNAL (Live Feed)</h2>
        <div class="space-y-2 max-h-64 overflow-y-auto" id="systemJournal">
            <!-- Journal entries will be populated by JavaScript -->
            <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
                <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono text-gray-600">{{ now()->format('H:i:s') }}</span>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">ARMED</span>
                    </div>
                    <p class="text-sm text-gray-900 mt-1">System initialized. Awaiting pullback to entry zone.</p>
                </div>
            </div>
            
            @if($cpiEvent && isset($eventSignal))
            <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono text-gray-600">{{ $cpiEvent['time'] }}</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">CPI PARSED</span>
                    </div>
                    <p class="text-sm text-gray-900 mt-1">{{ $eventSignal['reason'] ?? 'CPI data processed' }}</p>
                </div>
            </div>
            @endif
            
            <div class="flex items-start space-x-3 p-3 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                <div class="flex-shrink-0 w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono text-gray-600">{{ now()->subMinutes(2)->format('H:i:s') }}</span>
                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded">TFT CHECK</span>
                    </div>
                    <p class="text-sm text-gray-900 mt-1">Confidence {{ number_format($goldAlphaSignal['confidence'], 0) }}% ({{ $goldAlphaSignal['confidence'] >= 65 ? 'PASS' : 'FAIL' }})</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Learning Model Dashboard -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">Live Learning Model Dashboard</h2>
            <div class="flex items-center space-x-2">
                <div id="modelStatus" class="flex items-center space-x-2 px-3 py-1 bg-gray-100 rounded-lg">
                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                    <span class="text-xs font-medium text-gray-600">Not Connected</span>
                </div>
                <button id="toggleModelDashboard" class="btn btn-secondary text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    Connect
                </button>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-6 border-2 border-dashed border-gray-300">
            <div id="modelDashboardPlaceholder" class="text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Live Model Learning</h3>
                <p class="text-sm text-gray-600 mb-4">Connect to RLTradingAgent for real-time model training visualization</p>
                <div class="bg-gray-800 rounded-lg p-4 text-left max-w-2xl mx-auto mb-4">
                    <code class="text-xs text-green-400 block whitespace-pre-wrap"># Deploy RLTradingAgent
git clone https://github.com/saeedsamie/RLTradingAgent.git
cd RLTradingAgent
pip install -r requirements.txt

# Start training pipeline
python scripts/run_rl_pipeline.py

# Start dashboard (separate terminal)
uvicorn web.main_webview:app --reload --port 8001</code>
                </div>
                <div class="text-sm text-gray-500">
                    <p>Dashboard URL: <code class="bg-gray-200 px-2 py-1 rounded">http://localhost:8001/training_dashboard</code></p>
                </div>
            </div>
            
            <!-- Iframe container (hidden by default) -->
            <div id="modelDashboard" class="hidden">
                <iframe src="http://localhost:8001/training_dashboard" 
                        width="100%" 
                        height="600px" 
                        class="border-0 rounded-lg bg-white shadow-lg"
                        title="Live Model Training Dashboard"
                        onload="updateModelStatus(true)"
                        onerror="updateModelStatus(false)">
                </iframe>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';
    const currentPrice = {{ $currentPrice }};
    const entryZones = @json($entryZones);
    const stopLoss = {{ $postNewsHigh }};
    const takeProfit1 = {{ $postNewsLow }};
    const takeProfit2 = {{ $postNewsLow - 20 }};
    
    // Update countdown timer
    function updateCountdown() {
        // This would calculate based on event time
        const timerEl = document.getElementById('countdownTimer');
        if (timerEl) {
            // Placeholder - would calculate from server time
            timerEl.textContent = '{{ $goldAlphaSignal['countdown'] ?? '--:--' }}';
        }
    }
    
    // Update price ladder
    function updatePriceLadder(price) {
        const ladder = document.getElementById('priceLadder');
        if (!ladder) return;
        
        const levels = [
            { price: stopLoss, label: 'STOP LOSS', color: 'red', type: 'stop' },
            { price: entryZones['61.8'], label: '61.8% ENTRY', color: 'yellow', type: 'entry' },
            { price: entryZones['50.0'], label: '50.0% ENTRY (PENDING)', color: 'yellow', type: 'entry' },
            { price: entryZones['38.2'], label: '38.2% ENTRY (PENDING)', color: 'yellow', type: 'entry' },
            { price: price, label: 'CURRENT PRICE', color: 'blue', type: 'current' },
            { price: takeProfit1, label: 'TP1 (Pending)', color: 'green', type: 'tp' },
            { price: takeProfit2, label: 'TP2 (Pending)', color: 'green', type: 'tp' },
        ];
        
        ladder.innerHTML = levels.map(level => {
            const distance = Math.abs(price - level.price);
            const distancePips = (distance * 100).toFixed(1);
            const isAbove = level.price > price;
            const status = level.type === 'entry' && isAbove ? 'PENDING' : (level.type === 'current' ? 'ACTIVE' : 'PENDING');
            
            return `
                <div class="flex items-center justify-between p-3 rounded-lg border-2 ${level.color === 'red' ? 'border-red-500 bg-red-50' : level.color === 'green' ? 'border-green-500 bg-green-50' : level.color === 'yellow' ? 'border-yellow-500 bg-yellow-50' : 'border-blue-500 bg-blue-50'}">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 rounded-full ${level.color === 'red' ? 'bg-red-500' : level.color === 'green' ? 'bg-green-500' : level.color === 'yellow' ? 'bg-yellow-500' : 'bg-blue-500'}"></div>
                        <div>
                            <p class="font-bold text-gray-900">$${level.price.toFixed(2)}</p>
                            <p class="text-xs text-gray-600">${level.label}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold ${isAbove ? 'text-red-600' : 'text-green-600'}">
                            ${isAbove ? '+' : '-'}${distancePips} pips
                        </p>
                        <p class="text-xs text-gray-500">${status}</p>
                    </div>
                </div>
            `;
        }).join('');
    }
    
    // Update price display
    function updatePrice(newPrice) {
        const priceEl = document.getElementById('xauusdPrice');
        if (priceEl) {
            priceEl.textContent = '$' + newPrice.toFixed(2);
        }
        updatePriceLadder(newPrice);
    }
    
    // Fetch live XAUUSD price
    async function fetchLivePrice() {
        try {
            const response = await fetch(`${API_BASE_URL}/market/prices?instruments=XAU_USD`);
            const result = await response.json();
            
            if (result.success && result.data && result.data.length > 0) {
                const price = parseFloat(result.data[0].bids[0].price);
                updatePrice(price);
            }
        } catch (error) {
            console.error('Error fetching price:', error);
        }
    }
    
    // Model Dashboard Toggle
    document.getElementById('toggleModelDashboard')?.addEventListener('click', function() {
        const dashboard = document.getElementById('modelDashboard');
        const placeholder = document.getElementById('modelDashboardPlaceholder');
        
        if (dashboard.classList.contains('hidden')) {
            dashboard.classList.remove('hidden');
            placeholder.classList.add('hidden');
            this.textContent = 'Disconnect';
            this.classList.remove('btn-secondary');
            this.classList.add('btn-danger');
        } else {
            dashboard.classList.add('hidden');
            placeholder.classList.remove('hidden');
            this.textContent = 'Connect';
            this.classList.remove('btn-danger');
            this.classList.add('btn-secondary');
        }
    });
    
    // Update model connection status
    function updateModelStatus(connected) {
        const statusEl = document.getElementById('modelStatus');
        if (!statusEl) return;
        
        const dot = statusEl.querySelector('div');
        const text = statusEl.querySelector('span');
        
        if (connected) {
            dot.className = 'w-2 h-2 bg-green-500 rounded-full animate-pulse';
            text.textContent = 'Connected';
            text.className = 'text-xs font-medium text-green-600';
            statusEl.className = 'flex items-center space-x-2 px-3 py-1 bg-green-100 rounded-lg';
        } else {
            dot.className = 'w-2 h-2 bg-gray-400 rounded-full';
            text.textContent = 'Not Connected';
            text.className = 'text-xs font-medium text-gray-600';
            statusEl.className = 'flex items-center space-x-2 px-3 py-1 bg-gray-100 rounded-lg';
        }
    }
    
    // Check if model dashboard is accessible
    function checkModelDashboard() {
        fetch('http://localhost:8001/training_dashboard', { method: 'HEAD', mode: 'no-cors' })
            .then(() => updateModelStatus(true))
            .catch(() => updateModelStatus(false));
    }
    
    // Initialize
    updatePriceLadder(currentPrice);
    updateCountdown();
    checkModelDashboard();
    
    // Update every 2 seconds
    setInterval(fetchLivePrice, 2000);
    
    // Check model dashboard every 30 seconds
    setInterval(checkModelDashboard, 30000);
    
    // Add journal entry function
    function addJournalEntry(timestamp, status, message, type = 'info') {
        const journal = document.getElementById('systemJournal');
        if (!journal) return;
        
        const colors = {
            'info': { bg: 'bg-blue-50', border: 'border-blue-500', dot: 'bg-blue-500', badge: 'bg-blue-100 text-blue-800' },
            'success': { bg: 'bg-green-50', border: 'border-green-500', dot: 'bg-green-500', badge: 'bg-green-100 text-green-800' },
            'warning': { bg: 'bg-yellow-50', border: 'border-yellow-500', dot: 'bg-yellow-500', badge: 'bg-yellow-100 text-yellow-800' },
            'error': { bg: 'bg-red-50', border: 'border-red-500', dot: 'bg-red-500', badge: 'bg-red-100 text-red-800' },
        };
        
        const color = colors[type] || colors.info;
        const entry = document.createElement('div');
        entry.className = `flex items-start space-x-3 p-3 ${color.bg} rounded-lg border-l-4 ${color.border}`;
        entry.innerHTML = `
            <div class="flex-shrink-0 w-2 h-2 ${color.dot} rounded-full mt-2"></div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono text-gray-600">${timestamp}</span>
                    <span class="px-2 py-1 ${color.badge} text-xs font-medium rounded">${status}</span>
                </div>
                <p class="text-sm text-gray-900 mt-1">${message}</p>
            </div>
        `;
        
        journal.insertBefore(entry, journal.firstChild);
        
        // Keep only last 20 entries
        while (journal.children.length > 20) {
            journal.removeChild(journal.lastChild);
        }
    }
    
    // Example: Add entry when price enters zone
    // addJournalEntry('09:25:00', 'ENTRY ZONE', 'Price approaching 50% Fib retracement', 'warning');
});
</script>
@endpush
@endsection
