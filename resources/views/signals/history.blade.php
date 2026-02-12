@extends('layouts.app')

@section('title', 'Signal History - FXEngine')
@section('page-title', 'Signal History')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Signal History</h2>
            <p class="text-sm text-gray-600 mt-1">Historical trading signals and their performance</p>
        </div>
        <div class="flex items-center space-x-3">
            <select id="historyLimit" class="form-input text-sm">
                <option value="50">Last 50</option>
                <option value="100" selected>Last 100</option>
                <option value="200">Last 200</option>
                <option value="500">Last 500</option>
            </select>
            <button id="refreshHistory" class="btn btn-secondary">
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
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalHistorySignals">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Buy Signals</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="buyHistorySignals">0</p>
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
                    <p class="text-2xl font-bold text-red-600 mt-1" id="sellHistorySignals">0</p>
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
                    <p class="text-sm text-gray-600">Avg Confidence</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1" id="avgConfidence">0%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="flex items-center space-x-4">
            <select id="filterHistoryInstrument" class="form-input text-sm">
                <option value="all">All Instruments</option>
                <option value="EUR_USD">EUR/USD</option>
                <option value="GBP_USD">GBP/USD</option>
                <option value="USD_JPY">USD/JPY</option>
                <option value="XAU_USD">XAU/USD</option>
            </select>
            <select id="filterHistoryStrategy" class="form-input text-sm">
                <option value="all">All Strategies</option>
                <option value="ema_crossover">EMA Crossover</option>
                <option value="rsi_oversold_overbought">RSI</option>
                <option value="macd_crossover">MACD</option>
                <option value="support_resistance">Support/Resistance</option>
            </select>
            <input type="date" id="filterDateFrom" class="form-input text-sm">
            <input type="date" id="filterDateTo" class="form-input text-sm">
        </div>
    </div>

    <!-- History Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Instrument</th>
                        <th>Direction</th>
                        <th>Strategy</th>
                        <th>Entry Price</th>
                        <th>Confidence</th>
                        <th>Strength</th>
                        <th>Risk</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody">
                    <!-- History will be populated by JavaScript -->
                    <tr>
                        <td colspan="9" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Loading signal history...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';
    let history = [];

    // Load signal history
    async function loadHistory() {
        const limit = document.getElementById('historyLimit').value;
        
        try {
            const response = await fetch(`${API_BASE_URL}/signals/history?limit=${limit}`);
            const result = await response.json();

            if (result.success) {
                history = result.data || [];
                updateStatistics();
                renderHistory();
            }
        } catch (error) {
            console.error('Error loading history:', error);
        }
    }

    // Update statistics
    function updateStatistics() {
        const total = history.length;
        const buy = history.filter(s => s.direction === 'BUY').length;
        const sell = history.filter(s => s.direction === 'SELL').length;
        const avgConfidence = history.length > 0 
            ? (history.reduce((sum, s) => sum + (s.confidence || 0), 0) / history.length * 100).toFixed(1)
            : 0;

        document.getElementById('totalHistorySignals').textContent = total;
        document.getElementById('buyHistorySignals').textContent = buy;
        document.getElementById('sellHistorySignals').textContent = sell;
        document.getElementById('avgConfidence').textContent = avgConfidence + '%';
    }

    // Render history table
    function renderHistory(filteredHistory = null) {
        const tbody = document.getElementById('historyTableBody');
        const displayHistory = filteredHistory || history;

        if (displayHistory.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-8 text-gray-500">
                        <p>No signal history found.</p>
                    </td>
                </tr>
            `;
            return;
        }

        const strategyNames = {
            'ema_crossover': 'EMA Crossover',
            'rsi_oversold_overbought': 'RSI',
            'macd_crossover': 'MACD',
            'support_resistance': 'S/R',
            'bollinger_bands': 'BB',
            'moving_average_convergence': 'MA Conv'
        };

        tbody.innerHTML = displayHistory.map(signal => {
            const directionColor = signal.direction === 'BUY' ? 'text-green-600' : 'text-red-600';
            const directionBg = signal.direction === 'BUY' ? 'bg-green-100' : 'bg-red-100';
            const strengthColors = {
                'Strong': 'bg-purple-100 text-purple-800',
                'Moderate': 'bg-yellow-100 text-yellow-800',
                'Weak': 'bg-gray-100 text-gray-800'
            };
            const riskColors = {
                'Low': 'text-green-600',
                'Medium': 'text-yellow-600',
                'High': 'text-red-600'
            };

            const time = signal.created_at ? new Date(signal.created_at).toLocaleString() : 'N/A';

            return `
                <tr class="hover:bg-gray-50">
                    <td class="text-sm text-gray-600">${time}</td>
                    <td class="font-medium">${signal.instrument.replace('_', '/')}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs font-medium ${directionBg} ${directionColor}">
                            ${signal.direction}
                        </span>
                    </td>
                    <td class="text-sm">${strategyNames[signal.strategy] || signal.strategy}</td>
                    <td class="font-mono text-sm">$${parseFloat(signal.entry_price || signal.current_price || 0).toFixed(5)}</td>
                    <td>
                        <div class="flex items-center space-x-2">
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: ${(signal.confidence || 0) * 100}%"></div>
                            </div>
                            <span class="text-sm">${((signal.confidence || 0) * 100).toFixed(0)}%</span>
                        </div>
                    </td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs font-medium ${strengthColors[signal.strength] || strengthColors['Weak']}">
                            ${signal.strength || 'N/A'}
                        </span>
                    </td>
                    <td class="text-sm font-medium ${riskColors[signal.risk_level] || 'text-gray-600'}">
                        ${signal.risk_level || 'N/A'}
                    </td>
                    <td class="text-sm text-gray-600">${signal.reason || 'N/A'}</td>
                </tr>
            `;
        }).join('');
    }

    // Apply filters
    function applyFilters() {
        const instrument = document.getElementById('filterHistoryInstrument').value;
        const strategy = document.getElementById('filterHistoryStrategy').value;
        const dateFrom = document.getElementById('filterDateFrom').value;
        const dateTo = document.getElementById('filterDateTo').value;

        let filtered = history;

        if (instrument !== 'all') {
            filtered = filtered.filter(s => s.instrument === instrument);
        }
        if (strategy !== 'all') {
            filtered = filtered.filter(s => s.strategy === strategy);
        }
        if (dateFrom) {
            filtered = filtered.filter(s => {
                const signalDate = new Date(s.created_at);
                return signalDate >= new Date(dateFrom);
            });
        }
        if (dateTo) {
            filtered = filtered.filter(s => {
                const signalDate = new Date(s.created_at);
                return signalDate <= new Date(dateTo + 'T23:59:59');
            });
        }

        renderHistory(filtered);
    }

    // Event listeners
    document.getElementById('refreshHistory').addEventListener('click', loadHistory);
    document.getElementById('historyLimit').addEventListener('change', loadHistory);
    document.getElementById('filterHistoryInstrument').addEventListener('change', applyFilters);
    document.getElementById('filterHistoryStrategy').addEventListener('change', applyFilters);
    document.getElementById('filterDateFrom').addEventListener('change', applyFilters);
    document.getElementById('filterDateTo').addEventListener('change', applyFilters);

    // Initial load
    loadHistory();
});
</script>
@endpush
@endsection

