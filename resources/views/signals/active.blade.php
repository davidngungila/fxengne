@extends('layouts.app')

@section('title', 'Active Signals - FxEngne')
@section('page-title', 'Active Signals')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Active Trading Signals</h2>
            <p class="text-sm text-gray-600 mt-1">Real-time signals based on market analysis</p>
        </div>
        <div class="flex items-center space-x-3">
            <button id="generateSignals" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Generate Signals
            </button>
            <button id="refreshSignals" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Signals</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalSignals">0</p>
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
                    <p class="text-sm text-gray-600">Buy Signals</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="buySignals">0</p>
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
                    <p class="text-sm text-gray-600">Sell Signals</p>
                    <p class="text-2xl font-bold text-red-600 mt-1" id="sellSignals">0</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Strong Signals</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1" id="strongSignals">0</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="flex items-center space-x-4">
            <select id="filterInstrument" class="form-input text-sm">
                <option value="all">All Instruments</option>
                <option value="EUR_USD">EUR/USD</option>
                <option value="GBP_USD">GBP/USD</option>
                <option value="USD_JPY">USD/JPY</option>
                <option value="XAU_USD">XAU/USD</option>
            </select>
            <select id="filterDirection" class="form-input text-sm">
                <option value="all">All Directions</option>
                <option value="BUY">Buy</option>
                <option value="SELL">Sell</option>
            </select>
            <select id="filterStrength" class="form-input text-sm">
                <option value="all">All Strengths</option>
                <option value="Strong">Strong</option>
                <option value="Moderate">Moderate</option>
                <option value="Weak">Weak</option>
            </select>
            <select id="filterStrategy" class="form-input text-sm">
                <option value="all">All Strategies</option>
                <option value="ema_crossover">EMA Crossover</option>
                <option value="rsi_oversold_overbought">RSI</option>
                <option value="macd_crossover">MACD</option>
                <option value="support_resistance">Support/Resistance</option>
            </select>
        </div>
    </div>

    <!-- Signals Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4" id="signalsGrid">
        <!-- Signals will be populated by JavaScript -->
        <div class="col-span-2 text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <p>No active signals. Click "Generate Signals" to analyze the market.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';
    let signals = [];

    // Load active signals
    async function loadSignals() {
        try {
            const response = await fetch(`${API_BASE_URL}/signals/active`);
            const result = await response.json();

            if (result.success) {
                signals = result.data || [];
                updateStatistics();
                renderSignals();
            }
        } catch (error) {
            console.error('Error loading signals:', error);
        }
    }

    // Generate new signals
    async function generateSignals() {
        const btn = document.getElementById('generateSignals');
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating...';

        try {
            const response = await fetch(`${API_BASE_URL}/signals/generate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    strategies: ['ema_crossover', 'rsi_oversold_overbought', 'macd_crossover', 'support_resistance']
                })
            });

            const result = await response.json();

            if (result.success) {
                await loadSignals();
                alert(`Generated ${result.count} new signals!`);
            }
        } catch (error) {
            console.error('Error generating signals:', error);
            alert('Error generating signals. Please try again.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>Generate Signals';
        }
    }

    // Update statistics
    function updateStatistics() {
        const total = signals.length;
        const buy = signals.filter(s => s.direction === 'BUY').length;
        const sell = signals.filter(s => s.direction === 'SELL').length;
        const strong = signals.filter(s => s.strength === 'Strong').length;

        document.getElementById('totalSignals').textContent = total;
        document.getElementById('buySignals').textContent = buy;
        document.getElementById('sellSignals').textContent = sell;
        document.getElementById('strongSignals').textContent = strong;
    }

    // Render signals
    function renderSignals(filteredSignals = null) {
        const grid = document.getElementById('signalsGrid');
        const displaySignals = filteredSignals || signals;

        if (displaySignals.length === 0) {
            grid.innerHTML = `
                <div class="col-span-2 text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p>No signals match your filters.</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = displaySignals.map(signal => {
            const directionColor = signal.direction === 'BUY' ? 'green' : 'red';
            const strengthColors = {
                'Strong': 'bg-purple-100 text-purple-800 border-purple-300',
                'Moderate': 'bg-yellow-100 text-yellow-800 border-yellow-300',
                'Weak': 'bg-gray-100 text-gray-800 border-gray-300'
            };
            const riskColors = {
                'Low': 'text-green-600',
                'Medium': 'text-yellow-600',
                'High': 'text-red-600'
            };

            const strategyNames = {
                'ema_crossover': 'EMA Crossover',
                'rsi_oversold_overbought': 'RSI Strategy',
                'macd_crossover': 'MACD Crossover',
                'support_resistance': 'Support/Resistance',
                'bollinger_bands': 'Bollinger Bands',
                'moving_average_convergence': 'MA Convergence'
            };

            return `
                <div class="card hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center ${directionColor === 'green' ? 'bg-green-100' : 'bg-red-100'}">
                                <span class="text-2xl font-bold ${directionColor === 'green' ? 'text-green-600' : 'text-red-600'}">
                                    ${signal.direction === 'BUY' ? '↑' : '↓'}
                                </span>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900">${signal.instrument.replace('_', '/')}</h3>
                                <p class="text-sm text-gray-600">${strategyNames[signal.strategy] || signal.strategy}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium border ${strengthColors[signal.strength] || strengthColors['Weak']}">
                            ${signal.strength}
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-gray-600">Entry Price</p>
                                <p class="font-semibold text-gray-900">$${parseFloat(signal.entry_price || signal.current_price).toFixed(5)}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Confidence</p>
                                <p class="font-semibold text-gray-900">${(signal.confidence * 100).toFixed(1)}%</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Spread</p>
                                <p class="font-semibold text-gray-900">${signal.spread_pips?.toFixed(1) || '--'} pips</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Risk Level</p>
                                <p class="font-semibold ${riskColors[signal.risk_level] || 'text-gray-600'}">${signal.risk_level}</p>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-1">Signal Reason</p>
                            <p class="text-sm text-gray-700">${signal.reason || 'Market analysis signal'}</p>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <span class="text-xs text-gray-500">Timeframe: ${signal.timeframe || 'H1'}</span>
                            <span class="text-xs text-gray-500">${signal.created_at || 'Just now'}</span>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Filter handlers
    function applyFilters() {
        const instrument = document.getElementById('filterInstrument').value;
        const direction = document.getElementById('filterDirection').value;
        const strength = document.getElementById('filterStrength').value;
        const strategy = document.getElementById('filterStrategy').value;

        let filtered = signals;

        if (instrument !== 'all') {
            filtered = filtered.filter(s => s.instrument === instrument);
        }
        if (direction !== 'all') {
            filtered = filtered.filter(s => s.direction === direction);
        }
        if (strength !== 'all') {
            filtered = filtered.filter(s => s.strength === strength);
        }
        if (strategy !== 'all') {
            filtered = filtered.filter(s => s.strategy === strategy);
        }

        renderSignals(filtered);
    }

    // Event listeners
    document.getElementById('generateSignals').addEventListener('click', generateSignals);
    document.getElementById('refreshSignals').addEventListener('click', loadSignals);
    document.getElementById('filterInstrument').addEventListener('change', applyFilters);
    document.getElementById('filterDirection').addEventListener('change', applyFilters);
    document.getElementById('filterStrength').addEventListener('change', applyFilters);
    document.getElementById('filterStrategy').addEventListener('change', applyFilters);

    // Initial load
    loadSignals();

    // Auto-refresh every 30 seconds
    setInterval(loadSignals, 30000);
});
</script>
@endpush
@endsection

