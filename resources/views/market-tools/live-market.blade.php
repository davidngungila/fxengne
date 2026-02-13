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

    <!-- LAYER 4: XAUUSD Live Chart (QOS WebSocket) -->
    <div class="card">
        <div class="flex flex-col space-y-4 mb-4">
            <!-- Header with timeframe buttons -->
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">XAUUSD Live Chart</h2>
                <div class="flex items-center space-x-4">
                    @if($qosEnabled ?? false)
                    <div class="flex items-center space-x-2 px-3 py-1 bg-green-100 rounded-lg">
                        <div class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse" id="qosStatus"></div>
                        <span class="text-xs font-medium text-green-700">QOS Connected</span>
                    </div>
                    @else
                    <div class="flex items-center space-x-2 px-3 py-1 bg-yellow-100 rounded-lg">
                        <div class="w-2.5 h-2.5 bg-yellow-500 rounded-full"></div>
                        <span class="text-xs font-medium text-yellow-700">QOS Not Configured</span>
                    </div>
                    @endif
                    <div class="flex items-center space-x-4 px-5 py-3 bg-gradient-to-r from-gray-800 to-gray-900 rounded-lg border border-gray-700">
                        <div class="flex items-center space-x-2">
                            <div class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs font-medium text-green-400">Live</span>
                        </div>
                        <div class="border-l border-gray-600 pl-4 flex items-center space-x-2">
                            <span class="text-xs text-gray-400">Price:</span>
                            <span class="text-xl font-bold text-white transition-all duration-300" id="xauusdPrice">${{ number_format($currentPrice, 2) }}</span>
                            <span id="priceMovement" class="text-lg transition-all duration-300"></span>
                        </div>
                        <div class="border-l border-gray-600 pl-4">
                            <span class="text-xs text-gray-400">Change:</span>
                            <span class="text-sm font-semibold ml-2" id="priceChange">--</span>
                        </div>
                        <div class="border-l border-gray-600 pl-4">
                            <span class="text-xs text-gray-400">Bid:</span>
                            <span class="text-sm font-semibold text-green-400 ml-2" id="xauusdBid">--</span>
                        </div>
                        <div class="border-l border-gray-600 pl-4">
                            <span class="text-xs text-gray-400">Ask:</span>
                            <span class="text-sm font-semibold text-red-400 ml-2" id="xauusdAsk">--</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Timeframe Selector -->
            <div class="flex items-center space-x-2 bg-gray-100 rounded-lg p-1">
                <span class="text-xs font-medium text-gray-600 px-3">Timeframe:</span>
                <button class="timeframe-btn px-3 py-1.5 text-xs font-medium rounded transition-all duration-200 bg-blue-600 text-white" data-timeframe="M1">1M</button>
                <button class="timeframe-btn px-3 py-1.5 text-xs font-medium rounded transition-all duration-200 text-gray-700 hover:bg-gray-200" data-timeframe="M5">5M</button>
                <button class="timeframe-btn px-3 py-1.5 text-xs font-medium rounded transition-all duration-200 text-gray-700 hover:bg-gray-200" data-timeframe="M15">15M</button>
                <button class="timeframe-btn px-3 py-1.5 text-xs font-medium rounded transition-all duration-200 text-gray-700 hover:bg-gray-200" data-timeframe="M30">30M</button>
                <button class="timeframe-btn px-3 py-1.5 text-xs font-medium rounded transition-all duration-200 text-gray-700 hover:bg-gray-200" data-timeframe="H1">1H</button>
                <button class="timeframe-btn px-3 py-1.5 text-xs font-medium rounded transition-all duration-200 text-gray-700 hover:bg-gray-200" data-timeframe="H4">4H</button>
                <button class="timeframe-btn px-3 py-1.5 text-xs font-medium rounded transition-all duration-200 text-gray-700 hover:bg-gray-200" data-timeframe="D">1D</button>
                <button class="timeframe-btn px-3 py-1.5 text-xs font-medium rounded transition-all duration-200 text-gray-700 hover:bg-gray-200" data-timeframe="W">1W</button>
            </div>
        </div>
        <!-- Chart Toolbar (MT5-like) -->
        <div class="flex items-center justify-between mb-2 px-2 py-1 bg-gray-800 rounded-t-lg border-b border-gray-700">
            <div class="flex items-center space-x-2">
                <button id="chartFullscreen" class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded transition-colors" title="Full Screen">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                    </svg>
                </button>
                <button id="chartCapture" class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded transition-colors" title="Capture Image">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </button>
                <div class="h-4 w-px bg-gray-600"></div>
                <button id="chartZoomIn" class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded transition-colors" title="Zoom In">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                    </svg>
                </button>
                <button id="chartZoomOut" class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded transition-colors" title="Zoom Out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path>
                    </svg>
                </button>
                <button id="chartResetZoom" class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded transition-colors" title="Reset Zoom">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
                <div class="h-4 w-px bg-gray-600"></div>
                <button id="chartCrosshair" class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded transition-colors bg-blue-600 text-white" title="Crosshair">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </button>
                <button id="chartPan" class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded transition-colors" title="Pan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </button>
                <div class="h-4 w-px bg-gray-600"></div>
                <div class="relative">
                    <button id="chartAnalyze" class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded transition-colors" title="Analysis Tools">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </button>
                    <!-- Analysis Tools Dropdown -->
                    <div id="analyzeDropdown" class="hidden absolute right-0 mt-2 w-64 bg-gray-800 border border-gray-700 rounded-lg shadow-xl z-50">
                        <div class="p-2">
                            <div class="text-xs text-gray-400 px-3 py-2 border-b border-gray-700">Drawing Tools</div>
                            <button class="analyze-tool w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded flex items-center space-x-2" data-tool="trendline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 8l-9 9m-5-5l9-9m0 0l5 5m-5-5l-5 5"></path>
                                </svg>
                                <span>Trend Line</span>
                            </button>
                            <button class="analyze-tool w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded flex items-center space-x-2" data-tool="fibonacci">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Fibonacci Retracement</span>
                            </button>
                            <button class="analyze-tool w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded flex items-center space-x-2" data-tool="horizontal">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"></path>
                                </svg>
                                <span>Horizontal Line</span>
                            </button>
                            <button class="analyze-tool w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded flex items-center space-x-2" data-tool="vertical">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14"></path>
                                </svg>
                                <span>Vertical Line</span>
                            </button>
                            <button class="analyze-tool w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded flex items-center space-x-2" data-tool="support">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <span>Support/Resistance</span>
                            </button>
                            <div class="text-xs text-gray-400 px-3 py-2 border-t border-gray-700 mt-2">Actions</div>
                            <button class="analyze-tool w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded flex items-center space-x-2" data-tool="clear">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Clear All Drawings</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2 text-xs text-gray-400">
                <span>Scroll to zoom | Drag to pan (always enabled)</span>
            </div>
        </div>
        <div class="relative" style="height: 500px; background-color: var(--trading-bg-dark-navy); border-radius: 0 0 8px 8px;" id="chartContainer">
            <canvas id="xauusdChart"></canvas>
            <svg id="drawingOverlay" class="drawing-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></svg>
        </div>
    </div>
    
    <!-- Full Screen Chart Modal -->
    <div id="fullscreenChartModal" class="fixed inset-0 bg-black z-50 hidden flex flex-col">
        <div class="flex items-center justify-between p-2 bg-gray-900 border-b border-gray-800">
            <h3 class="text-white font-semibold">XAUUSD Chart - Full Screen</h3>
            <button id="exitFullscreen" class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="flex-1 relative" id="fullscreenChartContainer">
            <canvas id="fullscreenChart"></canvas>
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

</div>

@push('styles')
<style>
    .timeframe-btn.active,
    .timeframe-btn.bg-blue-600 {
        background-color: #2563eb !important;
        color: white !important;
    }
    #chartContainer canvas {
        cursor: grab;
    }
    #chartContainer canvas:active {
        cursor: grabbing;
    }
    #chartContainer.drawing-mode canvas {
        cursor: crosshair;
    }
    .drawing-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 10;
    }
    .drawing-overlay.active {
        pointer-events: none; /* Don't block chart interactions */
    }
    #chartContainer.drawing-mode .drawing-overlay {
        pointer-events: none; /* Allow chart interactions even in drawing mode */
    }
    .drawing-line {
        stroke-width: 2;
        stroke-dasharray: none;
    }
    .drawing-line.trendline {
        stroke: #00E5FF;
    }
    .drawing-line.fibonacci {
        stroke: #FFD600;
    }
    .drawing-line.horizontal, .drawing-line.vertical {
        stroke: #FFFFFF;
        stroke-dasharray: 5,5;
    }
    .drawing-line.support {
        stroke: #00C853;
    }
    .drawing-line.resistance {
        stroke: #D50000;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Register zoom plugin - try multiple ways to load it
    if (typeof zoomPlugin !== 'undefined') {
        Chart.register(zoomPlugin);
    } else if (typeof window.ChartZoom !== 'undefined') {
        Chart.register(window.ChartZoom);
    } else if (typeof Chart !== 'undefined' && Chart.registry && Chart.registry.get('zoom')) {
        // Plugin already registered
    } else {
        // Load plugin from CDN if not available
        console.warn('Zoom plugin not found, attempting to load...');
    }
    const API_BASE_URL = '{{ url("/api") }}';
    const currentPrice = {{ $currentPrice }};
    const entryZones = @json($entryZones);
    const stopLoss = {{ $postNewsHigh }};
    const takeProfit1 = {{ $postNewsLow }};
    const takeProfit2 = {{ $postNewsLow - 20 }};
    
    // QOS WebSocket Configuration
    const qosEnabled = {{ ($qosEnabled ?? false) ? 'true' : 'false' }};
    const qosWsUrl = @json($qosWsUrl ?? null);
    let qosWebSocket = null;
    let qosConnected = false;
    
    // Chart variables
    let xauusdChart = null;
    let xauusdData = {
        labels: [],
        candles: [],
        ema9: [],
        ema21: [],
        ema200: []
    };
    let currentTimeframe = 'M1';
    let previousPrice = currentPrice;
    let priceChange = 0;
    let priceChangePercent = 0;
    
    // Update countdown timer
    function updateCountdown() {
        const timerEl = document.getElementById('countdownTimer');
        if (!timerEl) return;
        
        // Get event time from server (if available)
        const eventTime = @json($cpiEvent ? $cpiEvent['time'] : null);
        const eventDate = @json($cpiEvent ? ($cpiEvent['date'] instanceof \Carbon\Carbon ? $cpiEvent['date']->format('Y-m-d') : $cpiEvent['date']) : null);
        
        if (!eventTime || !eventDate) {
            timerEl.textContent = '--:--';
            return;
        }
        
        // Parse event time (format: "9:30pm")
        const timeMatch = eventTime.match(/(\d+):(\d+)(am|pm)/i);
        if (!timeMatch) {
            timerEl.textContent = '--:--';
            return;
        }
        
        let hours = parseInt(timeMatch[1]);
        const minutes = parseInt(timeMatch[2]);
        const period = timeMatch[3].toLowerCase();
        
        if (period === 'pm' && hours < 12) hours += 12;
        if (period === 'am' && hours === 12) hours = 0;
        
        // Create event datetime
        const eventDateTime = new Date(eventDate + 'T' + String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0') + ':00');
        
        // Calculate difference
        const now = new Date();
        const diff = eventDateTime - now;
        
        if (diff <= 0) {
            timerEl.textContent = '00:00';
            return;
        }
        
        const totalSeconds = Math.floor(diff / 1000);
        const mins = Math.floor(totalSeconds / 60);
        const secs = totalSeconds % 60;
        
        timerEl.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }
    
    // Update countdown every second
    setInterval(updateCountdown, 1000);
    
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
    
    // Update price display with movement indicators
    function updatePrice(newPrice) {
        const priceEl = document.getElementById('xauusdPrice');
        const movementEl = document.getElementById('priceMovement');
        const changeEl = document.getElementById('priceChange');
        
        if (priceEl) {
            const oldPrice = previousPrice;
            priceEl.textContent = '$' + newPrice.toFixed(2);
            
            // Calculate price change
            priceChange = newPrice - oldPrice;
            priceChangePercent = oldPrice > 0 ? ((priceChange / oldPrice) * 100) : 0;
            
            // Update price change display
            if (changeEl) {
                const sign = priceChange >= 0 ? '+' : '';
                changeEl.textContent = `${sign}${priceChange.toFixed(2)} (${sign}${priceChangePercent.toFixed(2)}%)`;
                changeEl.className = `text-sm font-semibold ml-2 ${priceChange >= 0 ? 'text-green-400' : 'text-red-400'}`;
            }
            
            // Update movement indicator
            if (movementEl) {
                if (newPrice > oldPrice) {
                    movementEl.innerHTML = '<svg class="w-5 h-5 text-green-400 animate-bounce" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>';
                    priceEl.classList.remove('text-white', 'text-red-400');
                    priceEl.classList.add('text-green-400');
                    setTimeout(() => {
                        priceEl.classList.remove('text-green-400');
                        priceEl.classList.add('text-white');
                    }, 500);
                } else if (newPrice < oldPrice) {
                    movementEl.innerHTML = '<svg class="w-5 h-5 text-red-400 animate-bounce" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>';
                    priceEl.classList.remove('text-white', 'text-green-400');
                    priceEl.classList.add('text-red-400');
                    setTimeout(() => {
                        priceEl.classList.remove('text-red-400');
                        priceEl.classList.add('text-white');
                    }, 500);
                } else {
                    movementEl.innerHTML = '';
                    priceEl.classList.remove('text-green-400', 'text-red-400');
                    priceEl.classList.add('text-white');
                }
            }
            
            previousPrice = newPrice;
        }
        updatePriceLadder(newPrice);
        
        // Update chart with tick
        if (xauusdChart && xauusdData && xauusdData.candles.length > 0) {
            updateChartWithTick(newPrice);
        }
    }
    
    // Initialize QOS WebSocket Connection
    function initQosWebSocket() {
        if (!qosEnabled || !qosWsUrl) {
            console.log('QOS not configured');
            return;
        }

        try {
            qosWebSocket = new WebSocket(qosWsUrl);

            qosWebSocket.onopen = function() {
                console.log('QOS WebSocket connected');
                qosConnected = true;
                updateQosStatus(true);
                
                // Subscribe to XAUUSD
                const subscribeMessage = {
                    action: 'subscribe',
                    symbol: 'XAUUSD',
                    type: 'tick'
                };
                qosWebSocket.send(JSON.stringify(subscribeMessage));
                
                addJournalEntry(new Date().toLocaleTimeString(), 'QOS CONNECTED', 'QOS WebSocket connection established', 'success');
            };

            qosWebSocket.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);
                    handleQosData(data);
                } catch (e) {
                    console.error('Error parsing QOS data:', e);
                }
            };

            qosWebSocket.onerror = function(error) {
                console.error('QOS WebSocket error:', error);
                updateQosStatus(false);
                qosConnected = false;
            };

            qosWebSocket.onclose = function() {
                console.log('QOS WebSocket disconnected');
                qosConnected = false;
                updateQosStatus(false);
                
                // Attempt to reconnect after 5 seconds
                setTimeout(initQosWebSocket, 5000);
            };
        } catch (error) {
            console.error('Error initializing QOS WebSocket:', error);
        }
    }

    // Handle QOS data
    function handleQosData(data) {
        if (data.symbol === 'XAUUSD' || data.symbol === 'XAU/USD' || data.instrument === 'XAUUSD' || data.symbol === 'XAU' || data.instrument === 'XAU') {
            let price = null;
            
            // Update price from QOS tick data
            if (data.bid && data.ask) {
                const bid = parseFloat(data.bid);
                const ask = parseFloat(data.ask);
                price = (bid + ask) / 2;
                
                updatePrice(price);
                const bidEl = document.getElementById('xauusdBid');
                const askEl = document.getElementById('xauusdAsk');
                if (bidEl) bidEl.textContent = '$' + bid.toFixed(2);
                if (askEl) askEl.textContent = '$' + ask.toFixed(2);
            } else if (data.price) {
                price = parseFloat(data.price);
                updatePrice(price);
            } else if (data.last) {
                price = parseFloat(data.last);
                updatePrice(price);
            } else if (data.close) {
                price = parseFloat(data.close);
                updatePrice(price);
            }
            
            // Update chart
            if (price !== null) {
                if (data.type === 'candle' || (data.open && data.high && data.low && data.close)) {
                    // Full candle data
                    updateChartWithCandle(data);
                } else {
                    // Tick data - update current candle
                    updateChartWithTick(price);
                }
            }
        }
    }

    // Update QOS connection status
    function updateQosStatus(connected) {
        const statusEl = document.getElementById('qosStatus');
        if (statusEl) {
            if (connected) {
                statusEl.className = 'w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse';
            } else {
                statusEl.className = 'w-2.5 h-2.5 bg-red-500 rounded-full';
            }
        }
    }

    // Update chart with new candle
    function updateChartWithCandle(candleData) {
        if (!xauusdChart || !xauusdData) return;
        
        const newCandle = {
            open: parseFloat(candleData.open || candleData.o),
            high: parseFloat(candleData.high || candleData.h),
            low: parseFloat(candleData.low || candleData.l),
            close: parseFloat(candleData.close || candleData.c),
            time: new Date(candleData.time || candleData.t)
        };
        
        // Update last candle or add new one
        if (xauusdData.candles.length > 0) {
            const lastCandle = xauusdData.candles[xauusdData.candles.length - 1];
            const lastTime = new Date(lastCandle.time);
            const newTime = newCandle.time;
            
            // Check if same minute (update existing candle)
            if (lastTime.getFullYear() === newTime.getFullYear() &&
                lastTime.getMonth() === newTime.getMonth() &&
                lastTime.getDate() === newTime.getDate() &&
                lastTime.getHours() === newTime.getHours() &&
                lastTime.getMinutes() === newTime.getMinutes()) {
                // Update existing candle
                Object.assign(lastCandle, newCandle);
            } else {
                // Add new candle
                xauusdData.candles.push(newCandle);
                xauusdData.labels.push(newCandle.time.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit' }));
                
                // Keep only last 500 candles
                if (xauusdData.candles.length > 500) {
                    xauusdData.candles.shift();
                    xauusdData.labels.shift();
                }
            }
        } else {
            xauusdData.candles.push(newCandle);
            xauusdData.labels.push(newCandle.time.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit' }));
        }
        
        // Recalculate EMAs
        calculateEMAs();
        
        // Update chart datasets
        if (xauusdChart.data.datasets.length >= 4) {
            xauusdChart.data.datasets[0].data = xauusdData.candles.map(c => c.close);
            xauusdChart.data.datasets[1].data = xauusdData.ema9;
            xauusdChart.data.datasets[2].data = xauusdData.ema21;
            xauusdChart.data.datasets[3].data = xauusdData.ema200;
        }
        
        xauusdChart.data.labels = xauusdData.labels;
        xauusdChart.update('none');
    }
    
    // Update chart with tick price (create/update current candle)
    function updateChartWithTick(price) {
        if (!xauusdChart || !xauusdData || xauusdData.candles.length === 0) return;
        
        const now = new Date();
        const lastCandle = xauusdData.candles[xauusdData.candles.length - 1];
        const lastTime = new Date(lastCandle.time);
        
        // Check if same minute
        if (now.getFullYear() === lastTime.getFullYear() &&
            now.getMonth() === lastTime.getMonth() &&
            now.getDate() === lastTime.getDate() &&
            now.getHours() === lastTime.getHours() &&
            now.getMinutes() === lastTime.getMinutes()) {
            // Update current candle
            lastCandle.close = price;
            lastCandle.high = Math.max(lastCandle.high, price);
            lastCandle.low = Math.min(lastCandle.low, price);
        } else {
            // Create new candle
            const newCandle = {
                open: lastCandle.close,
                high: price,
                low: price,
                close: price,
                time: now
            };
            xauusdData.candles.push(newCandle);
            xauusdData.labels.push(now.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit' }));
            
            // Keep only last 500 candles
            if (xauusdData.candles.length > 500) {
                xauusdData.candles.shift();
                xauusdData.labels.shift();
            }
            
            // Recalculate EMAs
            calculateEMAs();
        }
        
        // Update chart
        if (xauusdChart.data.datasets.length >= 4) {
            xauusdChart.data.datasets[0].data = xauusdData.candles.map(c => c.close);
            xauusdChart.data.datasets[1].data = xauusdData.ema9;
            xauusdChart.data.datasets[2].data = xauusdData.ema21;
            xauusdChart.data.datasets[3].data = xauusdData.ema200;
        }
        
        xauusdChart.data.labels = xauusdData.labels;
        xauusdChart.update('none');
    }

    // Fetch live XAUUSD price (fallback if QOS not available)
    async function fetchLivePrice() {
        if (qosConnected) {
            // QOS is handling updates, skip API call
            return;
        }
        
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
    
    // Full Screen Chart
    let fullscreenChart = null;
    
    document.getElementById('chartFullscreen')?.addEventListener('click', function() {
        const modal = document.getElementById('fullscreenChartModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Create fullscreen chart
        setTimeout(() => {
            const ctx = document.getElementById('fullscreenChart');
            if (ctx && !fullscreenChart) {
                // Clone chart data
                const fullscreenData = {
                    labels: [...xauusdData.labels],
                    candles: [...xauusdData.candles],
                    ema9: [...xauusdData.ema9],
                    ema21: [...xauusdData.ema21],
                    ema200: [...xauusdData.ema200]
                };
                
                fullscreenChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: fullscreenData.labels,
                        datasets: [
                            {
                                label: 'Price',
                                data: fullscreenData.candles.map(c => c.close),
                                borderColor: 'rgba(255, 255, 255, 0.3)',
                                backgroundColor: 'transparent',
                                borderWidth: 1,
                                pointRadius: 0,
                                order: 5
                            },
                            {
                                label: 'EMA 9',
                                data: fullscreenData.ema9,
                                borderColor: '#FFD600',
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                pointRadius: 0,
                                tension: 0.1,
                                order: 4
                            },
                            {
                                label: 'EMA 21',
                                data: fullscreenData.ema21,
                                borderColor: '#00E5FF',
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                pointRadius: 0,
                                tension: 0.1,
                                order: 3
                            },
                            {
                                label: 'EMA 200',
                                data: fullscreenData.ema200,
                                borderColor: '#FFFFFF',
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                pointRadius: 0,
                                tension: 0.1,
                                order: 2
                            }
                        ]
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
                                position: 'top',
                                labels: {
                                    color: '#FFFFFF',
                                    font: { size: 12 },
                                    usePointStyle: true,
                                    padding: 15
                                }
                            },
                            tooltip: {
                                enabled: true,
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#FFFFFF',
                                bodyColor: '#FFFFFF',
                                borderColor: '#333',
                                borderWidth: 1
                            },
                            zoom: {
                                zoom: {
                                    wheel: { enabled: true, speed: 0.1 },
                                    pinch: { enabled: true },
                                    mode: 'x'
                                },
                                pan: {
                                    enabled: true,
                                    mode: 'x'
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#FFFFFF',
                                    font: { size: 11 }
                                }
                            },
                            y: {
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#FFFFFF',
                                    font: { size: 12 },
                                    callback: function(value) {
                                        return '$' + value.toFixed(2);
                                    }
                                },
                                position: 'right'
                            }
                        }
                    },
                    plugins: [{
                        id: 'candlestick',
                        beforeDraw: function(chart) {
                            const ctx = chart.ctx;
                            const chartArea = chart.chartArea;
                            const meta = chart.getDatasetMeta(0);
                            
                            ctx.save();
                            
                            fullscreenData.candles.forEach((candle, index) => {
                                if (!meta.data[index]) return;
                                
                                const x = meta.data[index].x;
                                const isBullish = candle.close >= candle.open;
                                const color = isBullish ? '#00C853' : '#D50000';
                                const wickColor = 'rgba(255, 255, 255, 0.6)';
                                
                                const openY = chart.scales.y.getPixelForValue(candle.open);
                                const closeY = chart.scales.y.getPixelForValue(candle.close);
                                const highY = chart.scales.y.getPixelForValue(candle.high);
                                const lowY = chart.scales.y.getPixelForValue(candle.low);
                                
                                const bodyTop = Math.min(openY, closeY);
                                const bodyBottom = Math.max(openY, closeY);
                                const bodyHeight = bodyBottom - bodyTop;
                                const bodyWidth = Math.max(3, (chartArea.right - chartArea.left) / fullscreenData.candles.length * 0.6);
                                
                                ctx.strokeStyle = wickColor;
                                ctx.lineWidth = 1;
                                ctx.beginPath();
                                ctx.moveTo(x, highY);
                                ctx.lineTo(x, lowY);
                                ctx.stroke();
                                
                                ctx.fillStyle = color;
                                ctx.fillRect(x - bodyWidth / 2, bodyTop, bodyWidth, Math.max(1, bodyHeight));
                            });
                            
                            ctx.restore();
                        }
                    }]
                });
            }
        }, 100);
    });
    
    document.getElementById('exitFullscreen')?.addEventListener('click', function() {
        const modal = document.getElementById('fullscreenChartModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        
        if (fullscreenChart) {
            fullscreenChart.destroy();
            fullscreenChart = null;
        }
    });
    
    // Capture Chart Image
    document.getElementById('chartCapture')?.addEventListener('click', function() {
        const canvas = document.getElementById('xauusdChart');
        if (!canvas) return;
        
        const link = document.createElement('a');
        link.download = `XAUUSD_Chart_${currentTimeframe}_${new Date().toISOString().slice(0, 10)}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
    
    // Chart Zoom Controls
    document.getElementById('chartZoomIn')?.addEventListener('click', function() {
        if (xauusdChart) {
            const currentZoom = xauusdChart.getZoomLevel();
            xauusdChart.zoom(1.2);
        }
    });
    
    document.getElementById('chartZoomOut')?.addEventListener('click', function() {
        if (xauusdChart) {
            xauusdChart.zoom(0.8);
        }
    });
    
    document.getElementById('chartResetZoom')?.addEventListener('click', function() {
        if (xauusdChart) {
            xauusdChart.resetZoom();
        }
    });
    
    // Crosshair Toggle
    let crosshairEnabled = true;
    document.getElementById('chartCrosshair')?.addEventListener('click', function() {
        crosshairEnabled = !crosshairEnabled;
        this.classList.toggle('active', crosshairEnabled);
        this.classList.toggle('bg-blue-600', crosshairEnabled);
    });
    
    // Pan Mode Toggle (visual indicator only, pan is always enabled)
    let panMode = false;
    const chartContainer = document.getElementById('chartContainer');
    document.getElementById('chartPan')?.addEventListener('click', function() {
        panMode = !panMode;
        this.classList.toggle('bg-blue-600', panMode);
        this.classList.toggle('text-white', panMode);
        // Pan is always enabled, this is just a visual indicator
    });
    
    // Drawing Tools
    let currentDrawingTool = null;
    let drawings = [];
    let isDrawing = false;
    let drawingStart = null;
    const drawingOverlay = document.getElementById('drawingOverlay');
    
    // Analyze Button Dropdown
    document.getElementById('chartAnalyze')?.addEventListener('click', function(e) {
        e.stopPropagation();
        const dropdown = document.getElementById('analyzeDropdown');
        dropdown.classList.toggle('hidden');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('analyzeDropdown');
        const button = document.getElementById('chartAnalyze');
        if (dropdown && !dropdown.contains(e.target) && !button.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
    
    // Drawing Tool Selection
    document.querySelectorAll('.analyze-tool').forEach(btn => {
        btn.addEventListener('click', function() {
            const tool = this.getAttribute('data-tool');
            
            if (tool === 'clear') {
                drawings = [];
                drawingOverlay.innerHTML = '';
                currentDrawingTool = null;
                chartContainer.classList.remove('drawing-mode');
                document.getElementById('analyzeDropdown').classList.add('hidden');
                return;
            }
            
            currentDrawingTool = tool;
            chartContainer.classList.add('drawing-mode');
            document.getElementById('analyzeDropdown').classList.add('hidden');
            
            // Update button states
            document.querySelectorAll('.analyze-tool').forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white');
            });
            this.classList.add('bg-blue-600', 'text-white');
        });
    });
    
    // Drawing on Chart - Use chart container instead of canvas to avoid blocking zoom/pan
    let drawingStartPos = null;
    const chartContainer = document.getElementById('chartContainer');
    
    chartContainer.addEventListener('mousedown', function(e) {
        // Only start drawing if tool is selected and not right-click
        if (!currentDrawingTool || e.button !== 0) return;
        
        // Check if we're clicking on the chart area (not toolbar)
        const canvas = document.getElementById('xauusdChart');
        if (!canvas) return;
        
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        // Only draw if within canvas bounds
        if (x < 0 || y < 0 || x > rect.width || y > rect.height) return;
        
        isDrawing = true;
        e.stopPropagation(); // Prevent chart pan when drawing
        drawingStartPos = { x, y };
    });
    
    chartContainer.addEventListener('mousemove', function(e) {
        if (!isDrawing || !currentDrawingTool || !drawingStartPos) return;
        
        const canvas = document.getElementById('xauusdChart');
        if (!canvas) return;
        
        const rect = canvas.getBoundingClientRect();
        const currentPos = {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
        
        e.stopPropagation(); // Prevent chart pan when drawing
        updateDrawingPreview(drawingStartPos, currentPos);
    });
    
    chartContainer.addEventListener('mouseup', function(e) {
        if (!isDrawing || !currentDrawingTool || !drawingStartPos) return;
        
        const canvas = document.getElementById('xauusdChart');
        if (!canvas) return;
        
        const rect = canvas.getBoundingClientRect();
        const endPos = {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
        
        e.stopPropagation(); // Prevent chart pan when drawing
        finishDrawing(drawingStartPos, endPos);
        isDrawing = false;
        drawingStartPos = null;
    });
    
    // Also handle mouse leave to cancel drawing
    chartContainer.addEventListener('mouseleave', function() {
        if (isDrawing) {
            isDrawing = false;
            drawingStartPos = null;
            const preview = drawingOverlay.querySelector('#drawing-preview');
            if (preview) preview.remove();
        }
    });
    
    function updateDrawingPreview(start, current) {
        // Clear preview
        const preview = drawingOverlay.querySelector('#drawing-preview');
        if (preview) preview.remove();
        
        if (currentDrawingTool === 'trendline') {
            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.id = 'drawing-preview';
            line.setAttribute('x1', start.x);
            line.setAttribute('y1', start.y);
            line.setAttribute('x2', current.x);
            line.setAttribute('y2', current.y);
            line.classList.add('drawing-line', 'trendline');
            drawingOverlay.appendChild(line);
        } else if (currentDrawingTool === 'horizontal') {
            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.id = 'drawing-preview';
            line.setAttribute('x1', 0);
            line.setAttribute('y1', start.y);
            line.setAttribute('x2', drawingOverlay.getAttribute('width') || '100%');
            line.setAttribute('y2', start.y);
            line.classList.add('drawing-line', 'horizontal');
            drawingOverlay.appendChild(line);
        } else if (currentDrawingTool === 'vertical') {
            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.id = 'drawing-preview';
            line.setAttribute('x1', start.x);
            line.setAttribute('y1', 0);
            line.setAttribute('x2', start.x);
            line.setAttribute('y2', drawingOverlay.getAttribute('height') || '100%');
            line.classList.add('drawing-line', 'vertical');
            drawingOverlay.appendChild(line);
        }
    }
    
    function finishDrawing(start, end) {
        const drawing = {
            id: Date.now(),
            tool: currentDrawingTool,
            start: { ...start },
            end: { ...end }
        };
        
        drawings.push(drawing);
        updateDrawingOverlay();
    }
    
    function updateDrawingOverlay() {
        if (!xauusdChart || !drawingOverlay) return;
        
        // Get chart dimensions
        const canvas = document.getElementById('xauusdChart');
        if (canvas) {
            const rect = canvas.getBoundingClientRect();
            drawingOverlay.setAttribute('width', rect.width);
            drawingOverlay.setAttribute('height', rect.height);
        }
        
        // Clear existing drawings (except preview)
        const existing = drawingOverlay.querySelectorAll('line, rect, text');
        existing.forEach(el => {
            if (el.id !== 'drawing-preview') el.remove();
        });
        
        // Redraw all drawings
        drawings.forEach(drawing => {
            if (drawing.tool === 'trendline') {
                const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                line.setAttribute('x1', drawing.start.x);
                line.setAttribute('y1', drawing.start.y);
                line.setAttribute('x2', drawing.end.x);
                line.setAttribute('y2', drawing.end.y);
                line.classList.add('drawing-line', 'trendline');
                drawingOverlay.appendChild(line);
            } else if (drawing.tool === 'horizontal') {
                const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                line.setAttribute('x1', 0);
                line.setAttribute('y1', drawing.start.y);
                line.setAttribute('x2', '100%');
                line.setAttribute('y2', drawing.start.y);
                line.classList.add('drawing-line', 'horizontal');
                drawingOverlay.appendChild(line);
            } else if (drawing.tool === 'vertical') {
                const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                line.setAttribute('x1', drawing.start.x);
                line.setAttribute('y1', 0);
                line.setAttribute('x2', drawing.start.x);
                line.setAttribute('y2', '100%');
                line.classList.add('drawing-line', 'vertical');
                drawingOverlay.appendChild(line);
            } else if (drawing.tool === 'fibonacci') {
                // Draw Fibonacci retracement levels
                const high = Math.min(drawing.start.y, drawing.end.y);
                const low = Math.max(drawing.start.y, drawing.end.y);
                const diff = low - high;
                const levels = [0, 0.236, 0.382, 0.5, 0.618, 0.786, 1.0];
                
                levels.forEach(level => {
                    const y = high + (diff * level);
                    const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                    line.setAttribute('x1', 0);
                    line.setAttribute('y1', y);
                    line.setAttribute('x2', '100%');
                    line.setAttribute('y2', y);
                    line.classList.add('drawing-line', 'fibonacci');
                    drawingOverlay.appendChild(line);
                    
                    // Add label
                    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                    text.setAttribute('x', '10');
                    text.setAttribute('y', y - 5);
                    text.setAttribute('fill', '#FFD600');
                    text.setAttribute('font-size', '10');
                    text.textContent = (level * 100).toFixed(1) + '%';
                    drawingOverlay.appendChild(text);
                });
            } else if (drawing.tool === 'support') {
                const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                line.setAttribute('x1', 0);
                line.setAttribute('y1', drawing.start.y);
                line.setAttribute('x2', '100%');
                line.setAttribute('y2', drawing.start.y);
                line.classList.add('drawing-line', 'support');
                drawingOverlay.appendChild(line);
            }
        });
    }
    
    // Load chart data for timeframe
    async function loadChartData(timeframe) {
        try {
            const response = await fetch(`${API_BASE_URL}/market/xauusd/candles?granularity=${timeframe}&count=500`);
            const result = await response.json();
            
            if (result.success && result.data) {
                const candles = result.data.filter(c => c.complete).map(c => ({
                    open: parseFloat(c.mid.o),
                    high: parseFloat(c.mid.h),
                    low: parseFloat(c.mid.l),
                    close: parseFloat(c.mid.c),
                    time: new Date(c.time)
                }));
                
                xauusdData.candles = candles;
                xauusdData.labels = candles.map(c => formatTimeLabel(c.time, timeframe));
                
                // Calculate EMAs
                calculateEMAs();
                
                // Update chart
                if (xauusdChart) {
                    updateChartData();
                } else {
                    initXAUUSDChart();
                }
            } else {
                // Fallback to generated data
                generateSampleData(timeframe);
                initXAUUSDChart();
            }
        } catch (error) {
            console.error('Error loading chart data:', error);
            // Fallback to generated data
            generateSampleData(timeframe);
            initXAUUSDChart();
        }
    }
    
    // Generate sample data for fallback
    function generateSampleData(timeframe) {
        const now = new Date();
        const initialCandles = [];
        const initialLabels = [];
        let basePrice = currentPrice;
        
        // Calculate milliseconds per candle based on timeframe
        const msPerCandle = getMsPerCandle(timeframe);
        const count = 500;
        
        for (let i = count - 1; i >= 0; i--) {
            const time = new Date(now.getTime() - i * msPerCandle);
            const change = (Math.random() - 0.5) * 2; // Random price movement
            const open = basePrice;
            const close = basePrice + change;
            const high = Math.max(open, close) + Math.random() * 0.5;
            const low = Math.min(open, close) - Math.random() * 0.5;
            
            initialCandles.push({ open, high, low, close, time });
            initialLabels.push(formatTimeLabel(time, timeframe));
            basePrice = close;
        }
        
        xauusdData.candles = initialCandles;
        xauusdData.labels = initialLabels;
    }
    
    // Get milliseconds per candle for timeframe
    function getMsPerCandle(timeframe) {
        const map = {
            'M1': 60000,      // 1 minute
            'M5': 300000,     // 5 minutes
            'M15': 900000,    // 15 minutes
            'M30': 1800000,   // 30 minutes
            'H1': 3600000,    // 1 hour
            'H4': 14400000,   // 4 hours
            'D': 86400000,    // 1 day
            'W': 604800000    // 1 week
        };
        return map[timeframe] || 60000;
    }
    
    // Format time label based on timeframe
    function formatTimeLabel(time, timeframe) {
        const date = new Date(time);
        if (timeframe === 'W') {
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        } else if (timeframe === 'D') {
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        } else if (timeframe === 'H4' || timeframe === 'H1') {
            return date.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit' });
        } else {
            return date.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit' });
        }
    }
    
    // Update chart data
    function updateChartData() {
        if (!xauusdChart) return;
        
        xauusdChart.data.labels = xauusdData.labels;
        if (xauusdChart.data.datasets.length >= 4) {
            xauusdChart.data.datasets[0].data = xauusdData.candles.map(c => c.close);
            xauusdChart.data.datasets[1].data = xauusdData.ema9;
            xauusdChart.data.datasets[2].data = xauusdData.ema21;
            xauusdChart.data.datasets[3].data = xauusdData.ema200;
        }
        xauusdChart.update('none');
    }
    
    // Initialize XAUUSD Chart
    function initXAUUSDChart() {
        const ctx = document.getElementById('xauusdChart');
        if (!ctx) return;
        
        // Calculate EMAs if not already calculated
        if (xauusdData.ema9.length === 0) {
            calculateEMAs();
        }
        
        // Create datasets
        const datasets = [
            // Price line (for reference)
            {
                label: 'Price',
                data: xauusdData.candles.map(c => c.close),
                borderColor: 'rgba(255, 255, 255, 0.3)',
                backgroundColor: 'transparent',
                borderWidth: 1,
                pointRadius: 0,
                order: 5
            },
            // EMA 9
            {
                label: 'EMA 9',
                data: xauusdData.ema9,
                borderColor: '#FFD600',
                backgroundColor: 'transparent',
                borderWidth: 2,
                pointRadius: 0,
                tension: 0.1,
                order: 4
            },
            // EMA 21
            {
                label: 'EMA 21',
                data: xauusdData.ema21,
                borderColor: '#00E5FF',
                backgroundColor: 'transparent',
                borderWidth: 2,
                pointRadius: 0,
                tension: 0.1,
                order: 3
            },
            // EMA 200
            {
                label: 'EMA 200',
                data: xauusdData.ema200,
                borderColor: '#FFFFFF',
                backgroundColor: 'transparent',
                borderWidth: 2,
                pointRadius: 0,
                tension: 0.1,
                order: 2
            }
        ];
        
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
                        position: 'top',
                        labels: {
                            color: '#FFFFFF',
                            font: { size: 11 },
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        enabled: true,
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#FFFFFF',
                        bodyColor: '#FFFFFF',
                        borderColor: '#333',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                const candle = xauusdData.candles[index];
                                if (!candle) return context.dataset.label + ': ' + context.parsed.y.toFixed(2);
                                
                                if (context.datasetIndex === 0) {
                                    return [
                                        'O: ' + candle.open.toFixed(2),
                                        'H: ' + candle.high.toFixed(2),
                                        'L: ' + candle.low.toFixed(2),
                                        'C: ' + candle.close.toFixed(2)
                                    ];
                                }
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(2);
                            }
                        }
                    },
                    zoom: {
                        zoom: {
                            wheel: {
                                enabled: true,
                                speed: 0.1
                            },
                            pinch: {
                                enabled: true
                            },
                            mode: 'x',
                            drag: {
                                enabled: false
                            }
                        },
                        pan: {
                            enabled: true,
                            mode: 'x',
                            modifierKey: null,
                            threshold: 10,
                            speed: 10,
                            onPanStart: function({chart, event}) {
                                // Allow panning even when drawing tools are active (unless actively drawing)
                                return !isDrawing;
                            }
                        },
                        limits: {
                            x: { min: 0, max: xauusdData.labels.length }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#FFFFFF',
                            font: { size: 10 },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#FFFFFF',
                            font: { size: 11 },
                            callback: function(value) {
                                return '$' + value.toFixed(2);
                            }
                        },
                        position: 'right'
                    }
                }
            },
            plugins: [{
                id: 'candlestick',
                beforeDraw: function(chart) {
                    const ctx = chart.ctx;
                    const chartArea = chart.chartArea;
                    const meta = chart.getDatasetMeta(0);
                    
                    ctx.save();
                    
                    xauusdData.candles.forEach((candle, index) => {
                        if (!meta.data[index]) return;
                        
                        const x = meta.data[index].x;
                        const isBullish = candle.close >= candle.open;
                        const color = isBullish ? '#00C853' : '#D50000';
                        const wickColor = 'rgba(255, 255, 255, 0.6)';
                        
                        const openY = chart.scales.y.getPixelForValue(candle.open);
                        const closeY = chart.scales.y.getPixelForValue(candle.close);
                        const highY = chart.scales.y.getPixelForValue(candle.high);
                        const lowY = chart.scales.y.getPixelForValue(candle.low);
                        
                        const bodyTop = Math.min(openY, closeY);
                        const bodyBottom = Math.max(openY, closeY);
                        const bodyHeight = bodyBottom - bodyTop;
                        const bodyWidth = Math.max(3, (chartArea.right - chartArea.left) / xauusdData.candles.length * 0.6);
                        
                        // Draw wick
                        ctx.strokeStyle = wickColor;
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(x, highY);
                        ctx.lineTo(x, lowY);
                        ctx.stroke();
                        
                        // Draw body
                        ctx.fillStyle = color;
                        ctx.fillRect(x - bodyWidth / 2, bodyTop, bodyWidth, Math.max(1, bodyHeight));
                    });
                    
                    ctx.restore();
                }
            }]
        });
    }
    
    // Handle timeframe change
    document.querySelectorAll('.timeframe-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const timeframe = this.getAttribute('data-timeframe');
            
            // Update active button
            document.querySelectorAll('.timeframe-btn').forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white');
                b.classList.add('text-gray-700', 'hover:bg-gray-200');
            });
            this.classList.remove('text-gray-700', 'hover:bg-gray-200');
            this.classList.add('bg-blue-600', 'text-white');
            
            // Update timeframe
            currentTimeframe = timeframe;
            
            // Reload chart data
            loadChartData(timeframe);
            
            // Update QOS subscription if connected
            if (qosWebSocket && qosWebSocket.readyState === WebSocket.OPEN) {
                const subscribeMessage = {
                    action: 'subscribe',
                    symbol: 'XAUUSD',
                    type: 'tick',
                    timeframe: timeframe
                };
                qosWebSocket.send(JSON.stringify(subscribeMessage));
            }
        });
    });
    
    // Calculate EMAs
    function calculateEMAs() {
        const closes = xauusdData.candles.map(c => c.close);
        
        // EMA 9
        xauusdData.ema9 = calculateEMA(closes, 9);
        
        // EMA 21
        xauusdData.ema21 = calculateEMA(closes, 21);
        
        // EMA 200
        xauusdData.ema200 = calculateEMA(closes, 200);
    }
    
    // Calculate EMA helper
    function calculateEMA(data, period) {
        const multiplier = 2 / (period + 1);
        const ema = [];
        let sum = 0;
        
        for (let i = 0; i < data.length; i++) {
            if (i < period) {
                sum += data[i];
                if (i === period - 1) {
                    ema.push(sum / period);
                } else {
                    ema.push(null);
                }
            } else {
                ema.push((data[i] - ema[ema.length - 1]) * multiplier + ema[ema.length - 1]);
            }
        }
        
        return ema;
    }
    
    // Initialize
    updatePriceLadder(currentPrice);
    updateCountdown();
    loadChartData(currentTimeframe);
    
    // Update every 2 seconds
    setInterval(fetchLivePrice, 2000);
    
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
    
    // Initialize QOS WebSocket if enabled
    if (qosEnabled) {
        initQosWebSocket();
    } else {
        // Fallback to API polling every 2 seconds
        setInterval(fetchLivePrice, 2000);
    }
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (qosWebSocket && qosWebSocket.readyState === WebSocket.OPEN) {
            const unsubscribeMessage = {
                action: 'unsubscribe',
                symbol: 'XAUUSD'
            };
            qosWebSocket.send(JSON.stringify(unsubscribeMessage));
            qosWebSocket.close();
        }
    });
});
</script>
@endpush
@endsection
