@extends('layouts.app')

@section('title', 'Live Market - FxEngne')
@section('page-title', 'Live Market')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Live Market Prices</h2>
            <p class="text-sm text-gray-600 mt-1">Real-time currency pair quotes and market data</p>
        </div>
        <div class="flex items-center space-x-4">
            @if($oandaEnabled ?? false)
                <div class="flex items-center space-x-2 px-3 py-1 bg-green-100 rounded-lg">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-green-700">OANDA Connected</span>
                </div>
            @else
                <div class="flex items-center space-x-2 px-3 py-1 bg-yellow-100 rounded-lg">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="text-sm font-medium text-yellow-700">Simulated Data</span>
                </div>
            @endif
            <button id="refreshMarket" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Market Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Market Status</p>
                    <p class="text-lg font-semibold text-green-600 mt-1">Open</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Pairs</p>
                    <p class="text-lg font-semibold text-gray-900 mt-1" id="activePairs">28</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Last Update</p>
                    <p class="text-lg font-semibold text-gray-900 mt-1" id="lastUpdate">--:--:--</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Update Rate</p>
                    <p class="text-lg font-semibold text-gray-900 mt-1">1s</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Market Table -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Currency Pairs</h3>
            <div class="flex items-center space-x-2">
                <input type="text" id="searchPairs" placeholder="Search pairs..." class="form-input w-48 text-sm">
                <select id="filterCategory" class="form-input text-sm">
                    <option value="all">All Categories</option>
                    <option value="major">Major Pairs</option>
                    <option value="minor">Minor Pairs</option>
                    <option value="exotic">Exotic Pairs</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pair</th>
                        <th>Bid</th>
                        <th>Ask</th>
                        <th>Spread</th>
                        <th>Change</th>
                        <th>Change %</th>
                        <th>High</th>
                        <th>Low</th>
                        <th>Time</th>
                    @if($oandaEnabled ?? false)
                    <th>Actions</th>
                    @endif
                    </tr>
                </thead>
                <tbody id="marketData">
                    <!-- Market data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    @if($oandaEnabled ?? false)
    <!-- Quick Trade Execution Modal -->
    <div id="tradeModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Execute Trade</h3>
                    <button id="closeTradeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <form id="tradeForm" class="px-6 py-4">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Instrument</label>
                        <input type="text" id="tradeInstrument" class="form-input" readonly>
                    </div>
                    <div>
                        <label class="form-label">Side</label>
                        <select id="tradeSide" class="form-input">
                            <option value="BUY">BUY</option>
                            <option value="SELL">SELL</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Units</label>
                        <input type="number" id="tradeUnits" class="form-input" value="1000" min="1" step="1" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Stop Loss</label>
                            <input type="number" id="tradeStopLoss" class="form-input" step="0.00001">
                        </div>
                        <div>
                            <label class="form-label">Take Profit</label>
                            <input type="number" id="tradeTakeProfit" class="form-input" step="0.00001">
                        </div>
                    </div>
                    <div id="tradeError" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm"></div>
                    <div id="tradeSuccess" class="hidden p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm"></div>
                </div>
                <div class="mt-6 flex space-x-3">
                    <button type="button" id="cancelTrade" class="btn btn-secondary flex-1">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-1">Execute Trade</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- XAUUSD Live Chart -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">XAUUSD (Gold) Live Chart</h3>
                <p class="text-sm text-gray-600 mt-1">Professional candlestick chart with moving averages</p>
            </div>
            <div class="flex items-center space-x-3">
                <select id="chartTimeframe" class="form-input text-sm">
                    <option value="M1">1 Minute</option>
                    <option value="M5">5 Minutes</option>
                    <option value="M15">15 Minutes</option>
                    <option value="H1" selected>1 Hour</option>
                    <option value="H4">4 Hours</option>
                    <option value="D">Daily</option>
                </select>
                <div class="flex items-center space-x-4 px-5 py-3 bg-gradient-to-r from-gray-800 to-gray-900 rounded-lg border border-gray-700 shadow-lg">
                    <div class="flex items-center space-x-2">
                        <div class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse shadow-lg shadow-green-500/50"></div>
                        <span class="text-xs font-medium text-green-400">Live</span>
                    </div>
                    <div class="border-l border-gray-600 pl-4">
                        <span class="text-xs text-gray-400">Price:</span>
                        <span class="text-xl font-bold text-white ml-2 transition-colors duration-300" id="xauusdPrice">Loading...</span>
                    </div>
                    <div class="border-l border-gray-600 pl-4">
                        <span class="text-xs text-gray-400">Change:</span>
                        <span class="text-sm font-semibold ml-2 transition-all duration-300" id="xauusdChange">--</span>
                    </div>
                    <div class="border-l border-gray-600 pl-4">
                        <span class="text-xs text-gray-400">High:</span>
                        <span class="text-sm font-medium text-green-400 ml-2" id="xauusdHigh">--</span>
                    </div>
                    <div class="border-l border-gray-600 pl-4">
                        <span class="text-xs text-gray-400">Low:</span>
                        <span class="text-sm font-medium text-red-400 ml-2" id="xauusdLow">--</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative" style="height: 600px; background-color: var(--trading-bg-dark-navy); border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.1);">
            <canvas id="xauusdChart"></canvas>
        </div>
        <div class="mt-3 flex items-center justify-between text-xs">
            <div class="flex items-center space-x-5">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-gradient-to-br from-green-400 to-green-600 rounded shadow-sm"></div>
                    <span class="text-gray-300 font-medium">Bullish</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-gradient-to-br from-red-400 to-red-600 rounded shadow-sm"></div>
                    <span class="text-gray-300 font-medium">Bearish</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 border-2 border-yellow-400 rounded"></div>
                    <span class="text-gray-300 font-medium">EMA 9</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 border-2 border-cyan-400 rounded"></div>
                    <span class="text-gray-300 font-medium">EMA 21</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 border-2 border-white rounded"></div>
                    <span class="text-gray-300 font-medium">EMA 200</span>
                </div>
            </div>
            <div class="text-gray-400 text-xs">
                <span class="font-medium">Zoom: Scroll | Pan: Drag</span>
            </div>
        </div>
    </div>

    <!-- Market Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Gainers</h3>
            <div class="space-y-3" id="topGainers">
                <!-- Top gainers will be populated by JavaScript -->
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Losers</h3>
            <div class="space-y-3" id="topLosers">
                <!-- Top losers will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // OANDA API Configuration
    const OANDA_ENABLED = {{ $oandaEnabled ?? false ? 'true' : 'false' }};
    const API_BASE_URL = '{{ url("/api") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    
    // Default instruments to fetch
    const defaultInstruments = [
        'EUR_USD', 'GBP_USD', 'USD_JPY', 'USD_CHF', 'AUD_USD', 'USD_CAD', 'NZD_USD', // Major
        'EUR_GBP', 'EUR_JPY', 'GBP_JPY', 'EUR_CHF', 'AUD_JPY', 'CAD_JPY', // Minor
        'USD_ZAR', 'USD_TRY', 'USD_SEK', 'USD_NOK', 'USD_MXN' // Exotic
    ];

    let marketData = {};
    let previousPrices = {};

    // Fetch market data from OANDA API
    async function fetchMarketData() {
        if (!OANDA_ENABLED) {
            // Fallback to simulated data if OANDA not configured
            useSimulatedData();
            return;
        }

        try {
            const response = await fetch(`${API_BASE_URL}/market/prices?instruments=${defaultInstruments.join(',')}`);
            const result = await response.json();

            if (result.success && result.data) {
                result.data.forEach(price => {
                    const instrument = price.instrument.replace('_', '');
                    const bid = parseFloat(price.bids[0].price);
                    const ask = parseFloat(price.asks[0].price);
                    const spread = ask - bid;
                    
                    // Calculate change from previous price
                    let change = 0;
                    let changePercent = 0;
                    if (previousPrices[instrument]) {
                        change = bid - previousPrices[instrument].bid;
                        changePercent = (change / previousPrices[instrument].bid) * 100;
                    }

                    // Determine category
                    let category = 'major';
                    if (['EUR_GBP', 'EUR_JPY', 'GBP_JPY', 'EUR_CHF', 'AUD_JPY', 'CAD_JPY'].includes(price.instrument)) {
                        category = 'minor';
                    } else if (['USD_ZAR', 'USD_TRY', 'USD_SEK', 'USD_NOK', 'USD_MXN'].includes(price.instrument)) {
                        category = 'exotic';
                    }

                    marketData[instrument] = {
                        pair: instrument,
                        category: category,
                        bid: bid,
                        ask: ask,
                        spread: spread,
                        change: change,
                        changePercent: changePercent,
                        high: previousPrices[instrument] ? Math.max(previousPrices[instrument].high, bid) : bid,
                        low: previousPrices[instrument] ? Math.min(previousPrices[instrument].low, bid) : bid,
                        lastUpdate: new Date(price.time || new Date())
                    };

                    // Store previous price for change calculation
                    previousPrices[instrument] = {
                        bid: bid,
                        high: marketData[instrument].high,
                        low: marketData[instrument].low
                    };
                });

                renderMarketData();
                updateTopGainersLosers();
                updateTimestamp();
                document.getElementById('activePairs').textContent = Object.keys(marketData).length;
            } else {
                console.error('Failed to fetch market data:', result.message);
                // Fallback to simulated data on error
                if (Object.keys(marketData).length === 0) {
                    useSimulatedData();
                }
            }
        } catch (error) {
            console.error('Error fetching market data:', error);
            // Fallback to simulated data on error
            if (Object.keys(marketData).length === 0) {
                useSimulatedData();
            }
        }
    }

    // Fallback simulated data (when OANDA not configured)
    function useSimulatedData() {
        const majorPairs = [
            { pair: 'EURUSD', category: 'major', basePrice: 1.0850 },
            { pair: 'GBPUSD', category: 'major', basePrice: 1.2650 },
            { pair: 'USDJPY', category: 'major', basePrice: 149.50 },
            { pair: 'USDCHF', category: 'major', basePrice: 0.8750 },
            { pair: 'AUDUSD', category: 'major', basePrice: 0.6550 },
            { pair: 'USDCAD', category: 'major', basePrice: 1.3450 },
            { pair: 'NZDUSD', category: 'major', basePrice: 0.6050 },
        ];

        const minorPairs = [
            { pair: 'EURGBP', category: 'minor', basePrice: 0.8575 },
            { pair: 'EURJPY', category: 'minor', basePrice: 162.25 },
            { pair: 'GBPJPY', category: 'minor', basePrice: 189.15 },
            { pair: 'EURCHF', category: 'minor', basePrice: 0.9500 },
            { pair: 'AUDJPY', category: 'minor', basePrice: 97.85 },
            { pair: 'CADJPY', category: 'minor', basePrice: 111.15 },
        ];

        const exoticPairs = [
            { pair: 'USDZAR', category: 'exotic', basePrice: 18.7500 },
            { pair: 'USDTRY', category: 'exotic', basePrice: 32.1500 },
            { pair: 'USDSEK', category: 'exotic', basePrice: 10.8500 },
            { pair: 'USDNOK', category: 'exotic', basePrice: 10.6500 },
            { pair: 'USDMXN', category: 'exotic', basePrice: 17.2500 },
        ];

        const allPairs = [...majorPairs, ...minorPairs, ...exoticPairs];

        allPairs.forEach(p => {
            const spread = p.basePrice * 0.0001;
            if (!marketData[p.pair]) {
                marketData[p.pair] = {
                    ...p,
                    bid: p.basePrice,
                    ask: p.basePrice + spread,
                    spread: spread,
                    change: 0,
                    changePercent: 0,
                    high: p.basePrice,
                    low: p.basePrice,
                    lastUpdate: new Date()
                };
            }
        });
    }

    // Update timestamp
    function updateTimestamp() {
        const now = new Date();
        document.getElementById('lastUpdate').textContent = 
            now.toLocaleTimeString('en-US', { hour12: false });
    }

    // Update prices (fetch from API or simulate)
    function updatePrices() {
        if (OANDA_ENABLED) {
            fetchMarketData();
        } else {
            // Simulated price movement (fallback)
            Object.keys(marketData).forEach(pair => {
                const data = marketData[pair];
                const basePrice = data.bid || data.basePrice || 1.0;
                const volatility = basePrice * 0.0001; // 0.01% volatility
                const change = (Math.random() - 0.5) * volatility * 2;
                
                const newBid = (data.bid || basePrice) + change;
                const spread = newBid * 0.0001;
                
                data.bid = newBid;
                data.ask = newBid + spread;
                data.spread = spread;
                data.change = change;
                data.changePercent = (change / basePrice) * 100;
                
                if (newBid > data.high) data.high = newBid;
                if (newBid < data.low) data.low = newBid;
                
                data.lastUpdate = new Date();
            });
            
            renderMarketData();
            updateTopGainersLosers();
            updateTimestamp();
        }
    }

    // Render market data table
    function renderMarketData(filteredData = null) {
        const data = filteredData || Object.values(marketData);
        const tbody = document.getElementById('marketData');
        
        tbody.innerHTML = data.map(pair => {
            const changeColor = pair.change >= 0 
                ? TradingColors.candles.bullish 
                : TradingColors.candles.bearish;
            const changeSign = pair.change >= 0 ? '+' : '';
            const changeClass = pair.change >= 0 ? 'text-green-600' : 'text-red-600';
            
            const actionButton = OANDA_ENABLED ? `
                <td>
                    <button onclick="openTradeModal('${pair.pair}', ${pair.bid}, ${pair.ask})" 
                            class="text-sm px-3 py-1 rounded-lg font-medium transition-colors"
                            style="background-color: ${TradingColors.entryExit.takeProfit}; color: white;"
                            onmouseover="this.style.opacity='0.9'"
                            onmouseout="this.style.opacity='1'">
                        Trade
                    </button>
                </td>
            ` : '';
            
            return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="font-medium">${pair.pair}</td>
                    <td class="font-mono">${pair.bid.toFixed(5)}</td>
                    <td class="font-mono">${pair.ask.toFixed(5)}</td>
                    <td class="font-mono text-gray-600">${(pair.spread * 10000).toFixed(1)}</td>
                    <td class="font-mono ${changeClass}">${changeSign}${pair.change.toFixed(5)}</td>
                    <td class="font-mono ${changeClass}">${changeSign}${pair.changePercent.toFixed(2)}%</td>
                    <td class="font-mono text-gray-600">${pair.high.toFixed(5)}</td>
                    <td class="font-mono text-gray-600">${pair.low.toFixed(5)}</td>
                    <td class="text-sm text-gray-500">${pair.lastUpdate.toLocaleTimeString()}</td>
                    ${actionButton}
                </tr>
            `;
        }).join('');
    }

    // Trade execution functions
    function openTradeModal(instrument, bid, ask) {
        document.getElementById('tradeInstrument').value = instrument;
        document.getElementById('tradeUnits').value = 1000;
        document.getElementById('tradeStopLoss').value = '';
        document.getElementById('tradeTakeProfit').value = '';
        document.getElementById('tradeError').classList.add('hidden');
        document.getElementById('tradeSuccess').classList.add('hidden');
        document.getElementById('tradeModal').classList.remove('hidden');
    }

    function closeTradeModal() {
        document.getElementById('tradeModal').classList.add('hidden');
    }

    // Trade form submission
    if (OANDA_ENABLED) {
        document.getElementById('tradeForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const errorDiv = document.getElementById('tradeError');
            const successDiv = document.getElementById('tradeSuccess');
            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');
            
            const formData = {
                instrument: document.getElementById('tradeInstrument').value,
                units: parseInt(document.getElementById('tradeUnits').value),
                side: document.getElementById('tradeSide').value,
                stop_loss: document.getElementById('tradeStopLoss').value || null,
                take_profit: document.getElementById('tradeTakeProfit').value || null
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
                    setTimeout(() => {
                        closeTradeModal();
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

        document.getElementById('closeTradeModal').addEventListener('click', closeTradeModal);
        document.getElementById('cancelTrade').addEventListener('click', closeTradeModal);
    }

    // Update top gainers and losers
    function updateTopGainersLosers() {
        const sorted = Object.values(marketData)
            .sort((a, b) => b.changePercent - a.changePercent);
        
        const gainers = sorted.slice(0, 5);
        const losers = sorted.slice(-5).reverse();
        
        document.getElementById('topGainers').innerHTML = gainers.map(pair => `
            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900">${pair.pair}</p>
                    <p class="text-sm text-gray-600">${pair.bid.toFixed(5)}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold" style="color: ${TradingColors.candles.bullish}">
                        +${pair.changePercent.toFixed(2)}%
                    </p>
                </div>
            </div>
        `).join('');
        
        document.getElementById('topLosers').innerHTML = losers.map(pair => `
            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900">${pair.pair}</p>
                    <p class="text-sm text-gray-600">${pair.bid.toFixed(5)}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold" style="color: ${TradingColors.candles.bearish}">
                        ${pair.changePercent.toFixed(2)}%
                    </p>
                </div>
            </div>
        `).join('');
    }

    // Search and filter functionality
    document.getElementById('searchPairs').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const category = document.getElementById('filterCategory').value;
        
        let filtered = Object.values(marketData);
        
        if (category !== 'all') {
            filtered = filtered.filter(p => p.category === category);
        }
        
        if (searchTerm) {
            filtered = filtered.filter(p => p.pair.toLowerCase().includes(searchTerm));
        }
        
        renderMarketData(filtered);
        document.getElementById('activePairs').textContent = filtered.length;
    });
    
    document.getElementById('filterCategory').addEventListener('change', function(e) {
        const searchTerm = document.getElementById('searchPairs').value.toLowerCase();
        const category = e.target.value;
        
        let filtered = Object.values(marketData);
        
        if (category !== 'all') {
            filtered = filtered.filter(p => p.category === category);
        }
        
        if (searchTerm) {
            filtered = filtered.filter(p => p.pair.toLowerCase().includes(searchTerm));
        }
        
        renderMarketData(filtered);
        document.getElementById('activePairs').textContent = filtered.length;
    });

    // Refresh button
    document.getElementById('refreshMarket').addEventListener('click', function() {
        updatePrices();
    });

    // XAUUSD Advanced Chart
    let xauusdChart = null;
    let xauusdData = {
        labels: [],
        candles: [],
        ema9: [],
        ema21: [],
        ema200: []
    };
    let lastPrice = null;
    let updateInterval = null;

    // Calculate EMA
    function calculateEMA(prices, period) {
        if (prices.length < period) return null;
        const multiplier = 2 / (period + 1);
        let ema = prices.slice(0, period).reduce((a, b) => a + b, 0) / period;
        for (let i = period; i < prices.length; i++) {
            ema = (prices[i] - ema) * multiplier + ema;
        }
        return ema;
    }

    // Calculate all EMAs
    function calculateEMAs() {
        const closes = xauusdData.candles.map(c => c.close);
        xauusdData.ema9 = [];
        xauusdData.ema21 = [];
        xauusdData.ema200 = [];

        for (let i = 0; i < closes.length; i++) {
            if (i >= 8) {
                xauusdData.ema9.push(calculateEMA(closes.slice(0, i + 1), 9));
            } else {
                xauusdData.ema9.push(null);
            }
            if (i >= 20) {
                xauusdData.ema21.push(calculateEMA(closes.slice(0, i + 1), 21));
            } else {
                xauusdData.ema21.push(null);
            }
            if (i >= 199) {
                xauusdData.ema200.push(calculateEMA(closes.slice(0, i + 1), 200));
            } else {
                xauusdData.ema200.push(null);
            }
        }
    }

    async function loadXAUUSDChart(granularity = 'H1') {
        try {
            const response = await fetch(`${API_BASE_URL}/market/xauusd/candles?granularity=${granularity}&count=500`);
            const result = await response.json();

            if (result.success && result.data) {
                xauusdData.labels = [];
                xauusdData.candles = [];

                result.data.forEach((candle) => {
                    if (candle.complete) {
                        const time = new Date(candle.time);
                        xauusdData.labels.push(time.toLocaleTimeString('en-US', { hour12: false }));
                        xauusdData.candles.push({
                            open: parseFloat(candle.mid.o),
                            high: parseFloat(candle.mid.h),
                            low: parseFloat(candle.mid.l),
                            close: parseFloat(candle.mid.c),
                            time: time
                        });
                    }
                });

                calculateEMAs();
                updatePriceDisplay();
                renderXAUUSDChart();
                
                // Start live updates
                startLiveUpdates(granularity);
            } else {
                generateSampleXAUUSDData();
            }
        } catch (error) {
            console.error('Error loading XAUUSD chart:', error);
            generateSampleXAUUSDData();
        }
    }

    function generateSampleXAUUSDData() {
        const basePrice = 2650;
        const now = new Date();
        xauusdData.labels = [];
        xauusdData.candles = [];

        for (let i = 499; i >= 0; i--) {
            const time = new Date(now.getTime() - i * 60 * 60 * 1000);
            xauusdData.labels.push(time.toLocaleTimeString('en-US', { hour12: false }));
            
            const volatility = basePrice * 0.01;
            const open = basePrice + (Math.random() - 0.5) * volatility;
            const close = open + (Math.random() - 0.5) * volatility * 0.5;
            const high = Math.max(open, close) + Math.random() * volatility * 0.3;
            const low = Math.min(open, close) - Math.random() * volatility * 0.3;

            xauusdData.candles.push({
                open: open,
                high: high,
                low: low,
                close: close,
                time: time
            });
        }

        calculateEMAs();
        updatePriceDisplay();
        renderXAUUSDChart();
        
        // Simulate live updates
        startLiveUpdates('H1');
    }

    function updatePriceDisplay(newPrice = null) {
        if (xauusdData.candles.length === 0) return;
        
        const lastCandle = xauusdData.candles[xauusdData.candles.length - 1];
        const price = newPrice || lastCandle.close;
        const change = price - lastCandle.open;
        const changePercent = (change / lastCandle.open) * 100;
        const high = Math.max(...xauusdData.candles.slice(-24).map(c => c.high));
        const low = Math.min(...xauusdData.candles.slice(-24).map(c => c.low));

        const priceElement = document.getElementById('xauusdPrice');
        const changeElement = document.getElementById('xauusdChange');

        // Determine price direction
        if (lastPrice !== null) {
            if (price > lastPrice) {
                priceChangeDirection = 1;
                priceElement.className = 'text-xl font-bold text-green-400 ml-2 transition-all duration-300 animate-pulse';
            } else if (price < lastPrice) {
                priceChangeDirection = -1;
                priceElement.className = 'text-xl font-bold text-red-400 ml-2 transition-all duration-300 animate-pulse';
            } else {
                priceChangeDirection = 0;
                priceElement.className = 'text-xl font-bold text-white ml-2 transition-all duration-300';
            }
        } else {
            priceElement.className = 'text-xl font-bold text-white ml-2 transition-all duration-300';
        }

        // Update price with animation
        priceElement.textContent = `$${price.toFixed(2)}`;
        
        // Update change with color coding
        const changeText = `${change >= 0 ? '+' : ''}${change.toFixed(2)} (${changePercent >= 0 ? '+' : ''}${changePercent.toFixed(2)}%)`;
        changeElement.textContent = changeText;
        changeElement.className = `text-sm font-semibold ml-2 transition-all duration-300 ${change >= 0 ? 'text-green-400' : 'text-red-400'}`;
        
        document.getElementById('xauusdHigh').textContent = `$${high.toFixed(2)}`;
        document.getElementById('xauusdLow').textContent = `$${low.toFixed(2)}`;

        // Reset color after animation
        setTimeout(() => {
            if (priceChangeDirection !== 0) {
                priceElement.className = 'text-xl font-bold text-white ml-2 transition-all duration-300';
            }
        }, 1000);

        lastPrice = price;
        lastUpdateTime = new Date();
    }

    function renderXAUUSDChart() {
        const ctx = document.getElementById('xauusdChart');
        if (!ctx) return;

        if (xauusdChart) {
            xauusdChart.destroy();
        }

        // Prepare datasets - only essential moving averages
        const datasets = [];
        
        // EMA 200 - Long-term trend (most important)
        if (xauusdData.ema200.length > 0) {
            datasets.push({
                label: 'EMA 200',
                data: xauusdData.ema200,
                borderColor: TradingColors.movingAverages.ema200,
                backgroundColor: 'transparent',
                borderWidth: 2.5,
                pointRadius: 0,
                pointHoverRadius: 4,
                tension: 0.1,
                fill: false,
                borderDash: [0, 0]
            });
        }

        // EMA 21 - Medium-term trend
        if (xauusdData.ema21.length > 0) {
            datasets.push({
                label: 'EMA 21',
                data: xauusdData.ema21,
                borderColor: TradingColors.movingAverages.ema21,
                backgroundColor: 'transparent',
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 3,
                tension: 0.1,
                fill: false
            });
        }

        // EMA 9 - Short-term trend
        if (xauusdData.ema9.length > 0) {
            datasets.push({
                label: 'EMA 9',
                data: xauusdData.ema9,
                borderColor: TradingColors.movingAverages.ema9,
                backgroundColor: 'transparent',
                borderWidth: 1.5,
                pointRadius: 0,
                pointHoverRadius: 3,
                tension: 0.1,
                fill: false
            });
        }

        // Invisible line for tooltip positioning (always last)
        datasets.push({
            label: 'Price',
            data: xauusdData.candles.map(c => c.close),
            borderColor: 'transparent',
            backgroundColor: 'transparent',
            pointRadius: 0,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'rgba(255, 255, 255, 0.8)',
            pointHoverBorderColor: '#FFFFFF',
            pointHoverBorderWidth: 2
        });

        xauusdChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: xauusdData.labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#FFFFFF',
                            font: { size: 11 },
                            usePointStyle: true,
                            padding: 15
                        },
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(11, 28, 45, 0.95)',
                        titleColor: '#FFFFFF',
                        bodyColor: '#FFFFFF',
                        borderColor: TradingColors.movingAverages.ema21,
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            title: (items) => items[0].label,
                            label: (context) => {
                                const index = context.dataIndex;
                                const candle = xauusdData.candles[index];
                                if (candle && context.datasetIndex === datasets.length - 1) {
                                    return [
                                        `Open: $${candle.open.toFixed(2)}`,
                                        `High: $${candle.high.toFixed(2)}`,
                                        `Low: $${candle.low.toFixed(2)}`,
                                        `Close: $${candle.close.toFixed(2)}`,
                                        `Change: ${((candle.close - candle.open) / candle.open * 100).toFixed(2)}%`
                                    ];
                                }
                                return context.dataset.label + ': $' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            display: true
                        },
                        ticks: {
                            color: '#FFFFFF',
                            font: { size: 10 },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        position: 'right',
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            display: true
                        },
                        ticks: {
                            color: '#FFFFFF',
                            font: { size: 11 },
                            callback: function(value) {
                                return '$' + value.toFixed(2);
                            }
                        }
                    }
                }
            },
            plugins: [{
                id: 'candlestick',
                beforeDraw: (chart) => {
                    drawCandlesticks(chart);
                }
            }]
        });
    }

    function drawCandlesticks(chart) {
        const ctx = chart.ctx;
        const meta = chart.getDatasetMeta(chart.data.datasets.length - 1);
        const scale = chart.scales.y;
        const xScale = chart.scales.x;

        const candleWidth = Math.max(4, (xScale.width / chart.data.labels.length) * 0.7);

        xauusdData.candles.forEach((candle, index) => {
            if (!meta.data[index]) return;

            const x = meta.data[index].x;
            const openY = scale.getPixelForValue(candle.open);
            const closeY = scale.getPixelForValue(candle.close);
            const highY = scale.getPixelForValue(candle.high);
            const lowY = scale.getPixelForValue(candle.low);

            const isBullish = candle.close >= candle.open;
            const bullishColor = '#00C853'; // Bright green
            const bearishColor = '#D50000'; // Bright red
            const color = isBullish ? bullishColor : bearishColor;
            
            const bodyTop = Math.min(openY, closeY);
            const bodyBottom = Math.max(openY, closeY);
            const bodyHeight = bodyBottom - bodyTop;

            // Draw wick with gradient effect
            ctx.strokeStyle = isBullish ? 'rgba(0, 200, 83, 0.8)' : 'rgba(213, 0, 0, 0.8)';
            ctx.lineWidth = 1.5;
            ctx.beginPath();
            ctx.moveTo(x, highY);
            ctx.lineTo(x, lowY);
            ctx.stroke();

            // Draw body with gradient
            const gradient = ctx.createLinearGradient(x - candleWidth / 2, bodyTop, x + candleWidth / 2, bodyBottom);
            if (isBullish) {
                gradient.addColorStop(0, '#00E676'); // Lighter green
                gradient.addColorStop(1, '#00C853'); // Standard green
            } else {
                gradient.addColorStop(0, '#FF5252'); // Lighter red
                gradient.addColorStop(1, '#D50000'); // Standard red
            }
            
            ctx.fillStyle = gradient;
            ctx.fillRect(x - candleWidth / 2, bodyTop, candleWidth, Math.max(3, bodyHeight));

            // Draw body border with highlight
            ctx.strokeStyle = isBullish ? '#00E676' : '#FF5252';
            ctx.lineWidth = 1;
            ctx.strokeRect(x - candleWidth / 2, bodyTop, candleWidth, Math.max(3, bodyHeight));
            
            // Add subtle shadow for depth
            ctx.shadowColor = isBullish ? 'rgba(0, 200, 83, 0.3)' : 'rgba(213, 0, 0, 0.3)';
            ctx.shadowBlur = 2;
            ctx.shadowOffsetX = 0;
            ctx.shadowOffsetY = 1;
        });
        
        // Reset shadow
        ctx.shadowColor = 'transparent';
        ctx.shadowBlur = 0;
        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 0;
    }

    function startLiveUpdates(granularity) {
        if (updateInterval) {
            clearInterval(updateInterval);
        }
        if (priceUpdateInterval) {
            clearInterval(priceUpdateInterval);
        }

        const updateFrequency = granularity === 'M1' ? 10000 : 
                               granularity === 'M5' ? 30000 :
                               granularity === 'M15' ? 60000 :
                               granularity === 'H1' ? 300000 : 3600000;

        // Fast price updates (every 2 seconds for live price display)
        priceUpdateInterval = setInterval(async () => {
            if (OANDA_ENABLED) {
                try {
                    const response = await fetch(`${API_BASE_URL}/market/prices?instruments=XAU_USD`);
                    const result = await response.json();
                    
                    if (result.success && result.data && result.data.length > 0) {
                        const price = parseFloat(result.data[0].bids[0].price);
                        const ask = parseFloat(result.data[0].asks[0].price);
                        
                        // Always update price display for live changes
                        updatePriceDisplay(price);
                        
                        // Update last candle if price changed significantly
                        if (lastPrice && Math.abs(price - lastPrice) > 0.01 && xauusdData.candles.length > 0) {
                            const lastCandle = xauusdData.candles[xauusdData.candles.length - 1];
                            lastCandle.close = price;
                            lastCandle.high = Math.max(lastCandle.high, price);
                            lastCandle.low = Math.min(lastCandle.low, price);
                            
                            if (xauusdChart) {
                                xauusdChart.update('none');
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error updating XAUUSD price:', error);
                }
            } else {
                // Simulate live price updates
                if (xauusdData.candles.length > 0) {
                    const lastCandle = xauusdData.candles[xauusdData.candles.length - 1];
                    const volatility = lastCandle.close * 0.0005; // Smaller volatility for smoother updates
                    const change = (Math.random() - 0.5) * volatility * 2;
                    const newPrice = lastCandle.close + change;
                    
                    updatePriceDisplay(newPrice);
                    
                    lastCandle.close = newPrice;
                    lastCandle.high = Math.max(lastCandle.high, newPrice);
                    lastCandle.low = Math.min(lastCandle.low, newPrice);
                    
                    if (xauusdChart) {
                        xauusdChart.update('none');
                    }
                }
            }
        }, 2000); // Update every 2 seconds for live price

        // Slower chart updates (full reload based on timeframe)
        updateInterval = setInterval(async () => {
            if (OANDA_ENABLED) {
                const timeframe = document.getElementById('chartTimeframe')?.value || granularity;
                loadXAUUSDChart(timeframe);
            } else {
                // Recalculate EMAs periodically
                if (xauusdData.candles.length > 0) {
                    calculateEMAs();
                    if (xauusdChart) {
                        xauusdChart.update('none');
                    }
                }
            }
        }, updateFrequency);
    }

    // Timeframe change handler
    if (document.getElementById('chartTimeframe')) {
        document.getElementById('chartTimeframe').addEventListener('change', function(e) {
            if (updateInterval) {
                clearInterval(updateInterval);
            }
            if (priceUpdateInterval) {
                clearInterval(priceUpdateInterval);
            }
            loadXAUUSDChart(e.target.value);
        });
    }

    // Initial load
    updatePrices();
    if (document.getElementById('xauusdChart')) {
        loadXAUUSDChart('H1');
    }

    // Auto-update prices every second
    setInterval(updatePrices, 1000);
});
</script>
@endpush
@endsection

