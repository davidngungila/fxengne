@extends('layouts.app')

@section('title', 'Backtest Reports - FxEngne')
@section('page-title', 'Backtest Reports')

@section('content')
<div class="space-y-6">
    <!-- Header with Filters -->
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Backtest Reports & Analysis</h2>
            <p class="text-sm text-gray-600 mt-1">Comprehensive analysis of your strategy backtesting results</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <select id="strategyFilter" class="form-input text-sm">
                <option value="all">All Strategies</option>
                <option value="ema_crossover">EMA Crossover</option>
                <option value="rsi_strategy">RSI Strategy</option>
                <option value="macd_crossover">MACD Crossover</option>
                <option value="bollinger_bands">Bollinger Bands</option>
            </select>
            <select id="timeframeFilter" class="form-input text-sm">
                <option value="all">All Timeframes</option>
                <option value="1d">Last 24 Hours</option>
                <option value="7d">Last 7 Days</option>
                <option value="30d">Last 30 Days</option>
                <option value="90d">Last 90 Days</option>
                <option value="1y">Last Year</option>
            </select>
            <select id="instrumentFilter" class="form-input text-sm">
                <option value="all">All Instruments</option>
                <option value="EUR_USD">EUR/USD</option>
                <option value="GBP_USD">GBP/USD</option>
                <option value="USD_JPY">USD/JPY</option>
                <option value="XAU_USD">XAU/USD</option>
            </select>
            <button onclick="exportReport()" class="btn btn-secondary text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export
            </button>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card bg-gradient-to-br from-green-50 to-green-100 border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Backtests</p>
                    <p class="text-3xl font-bold text-green-700 mt-1">24</p>
                    <p class="text-xs text-gray-600 mt-1">12 completed this month</p>
                </div>
                <div class="w-14 h-14 bg-green-200 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Avg. Return</p>
                    <p class="text-3xl font-bold text-blue-700 mt-1">+12.5%</p>
                    <p class="text-xs text-gray-600 mt-1">Across all strategies</p>
                </div>
                <div class="w-14 h-14 bg-blue-200 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Best Strategy</p>
                    <p class="text-2xl font-bold text-purple-700 mt-1">EMA Crossover</p>
                    <p class="text-xs text-gray-600 mt-1">+18.2% return</p>
                </div>
                <div class="w-14 h-14 bg-purple-200 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-orange-50 to-orange-100 border-orange-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Trades</p>
                    <p class="text-3xl font-bold text-orange-700 mt-1">1,247</p>
                    <p class="text-xs text-gray-600 mt-1">Across all backtests</p>
                </div>
                <div class="w-14 h-14 bg-orange-200 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Backtest Reports</h3>
            <div class="flex items-center space-x-2">
                <button onclick="toggleView('table')" class="px-3 py-1 text-sm rounded-lg bg-blue-100 text-blue-700 font-medium" id="tableViewBtn">Table</button>
                <button onclick="toggleView('cards')" class="px-3 py-1 text-sm rounded-lg bg-gray-100 text-gray-700 font-medium" id="cardsViewBtn">Cards</button>
            </div>
        </div>

        <!-- Table View -->
        <div id="tableView" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('strategy')">
                            Strategy
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('instrument')">
                            Instrument
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('period')">
                            Period
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('trades')">
                            Trades
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('winRate')">
                            Win Rate
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('return')">
                            Total Return
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('sharpe')">
                            Sharpe Ratio
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('drawdown')">
                            Max Drawdown
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody id="reportsTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Cards View -->
        <div id="cardsView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="reportsCardsContainer">
            <!-- Cards will be populated by JavaScript -->
        </div>
    </div>

    <!-- Detailed Report Modal -->
    <div id="reportModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-gray-900" id="modalTitle">Backtest Report Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="space-y-6">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Sample backtest data
const backtestReports = [
    {
        id: 1,
        strategy: 'EMA Crossover',
        instrument: 'EUR/USD',
        period: '2024-01-01 to 2024-01-31',
        trades: 42,
        winRate: 68.5,
        return: 15.2,
        sharpe: 1.85,
        drawdown: -8.5,
        profitFactor: 2.15,
        status: 'completed',
        date: '2024-01-31',
        avgWin: 45.20,
        avgLoss: -28.50,
        largestWin: 185.50,
        largestLoss: -95.20,
        totalProfit: 1250.00
    },
    {
        id: 2,
        strategy: 'RSI Strategy',
        instrument: 'GBP/USD',
        period: '2024-01-15 to 2024-02-15',
        trades: 38,
        winRate: 72.3,
        return: 18.2,
        sharpe: 1.92,
        drawdown: -5.8,
        profitFactor: 2.28,
        status: 'completed',
        date: '2024-02-15',
        avgWin: 48.30,
        avgLoss: -25.80,
        largestWin: 195.00,
        largestLoss: -88.50,
        totalProfit: 1520.00
    },
    {
        id: 3,
        strategy: 'MACD Crossover',
        instrument: 'USD/JPY',
        period: '2024-02-01 to 2024-02-29',
        trades: 23,
        winRate: 65.2,
        return: 12.8,
        sharpe: 1.45,
        drawdown: -9.1,
        profitFactor: 1.95,
        status: 'completed',
        date: '2024-02-29',
        avgWin: 42.15,
        avgLoss: -32.20,
        largestWin: 165.00,
        largestLoss: -105.00,
        totalProfit: 890.00
    },
    {
        id: 4,
        strategy: 'Bollinger Bands',
        instrument: 'XAU/USD',
        period: '2024-03-01 to 2024-03-31',
        trades: 31,
        winRate: 70.1,
        return: 16.5,
        sharpe: 1.68,
        drawdown: -7.2,
        profitFactor: 2.22,
        status: 'completed',
        date: '2024-03-31',
        avgWin: 52.10,
        avgLoss: -30.20,
        largestWin: 220.00,
        largestLoss: -98.50,
        totalProfit: 1620.00
    },
    {
        id: 5,
        strategy: 'EMA Crossover',
        instrument: 'AUD/USD',
        period: '2024-03-15 to 2024-04-15',
        trades: 28,
        winRate: 64.3,
        return: 11.5,
        sharpe: 1.52,
        drawdown: -8.9,
        profitFactor: 1.88,
        status: 'completed',
        date: '2024-04-15',
        avgWin: 38.50,
        avgLoss: -29.80,
        largestWin: 155.00,
        largestLoss: -92.00,
        totalProfit: 780.00
    },
    {
        id: 6,
        strategy: 'RSI Strategy',
        instrument: 'EUR/USD',
        period: '2024-04-01 to 2024-04-30',
        trades: 35,
        winRate: 71.4,
        return: 14.8,
        sharpe: 1.78,
        drawdown: -6.5,
        profitFactor: 2.15,
        status: 'running',
        date: '2024-04-30',
        avgWin: 44.20,
        avgLoss: -26.50,
        largestWin: 180.00,
        largestLoss: -85.00,
        totalProfit: 1120.00
    }
];

let currentView = 'table';
let filteredReports = [...backtestReports];

document.addEventListener('DOMContentLoaded', function() {
    renderReports();
    
    // Filter handlers
    document.getElementById('strategyFilter')?.addEventListener('change', applyFilters);
    document.getElementById('timeframeFilter')?.addEventListener('change', applyFilters);
    document.getElementById('instrumentFilter')?.addEventListener('change', applyFilters);
});

function renderReports() {
    if (currentView === 'table') {
        renderTableView();
    } else {
        renderCardsView();
    }
}

function renderTableView() {
    const tbody = document.getElementById('reportsTableBody');
    if (!tbody) return;

    tbody.innerHTML = filteredReports.map(report => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="w-2 h-2 rounded-full bg-blue-500 mr-2"></div>
                    <span class="font-medium text-gray-900">${report.strategy}</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${report.instrument}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${report.period}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${report.trades}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-green-600 font-semibold">${report.winRate}%</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-green-600 font-semibold">+${report.return}%</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${report.sharpe.toFixed(2)}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-red-600">${report.drawdown}%</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 rounded text-xs font-medium ${report.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                    ${report.status === 'completed' ? 'Completed' : 'Running'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
                <button onclick="viewReport(${report.id})" class="text-blue-600 hover:text-blue-700 font-medium">View Details</button>
            </td>
        </tr>
    `).join('');
}

function renderCardsView() {
    const container = document.getElementById('reportsCardsContainer');
    if (!container) return;

    container.innerHTML = filteredReports.map(report => `
        <div class="card hover:shadow-lg transition-shadow cursor-pointer" onclick="viewReport(${report.id})">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="font-semibold text-gray-900">${report.strategy}</h4>
                    <p class="text-sm text-gray-600">${report.instrument}</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-medium ${report.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                    ${report.status === 'completed' ? 'Completed' : 'Running'}
                </span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Return</span>
                    <span class="text-lg font-bold text-green-600">+${report.return}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Win Rate</span>
                    <span class="text-sm font-semibold text-green-600">${report.winRate}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Trades</span>
                    <span class="text-sm text-gray-900">${report.trades}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Sharpe Ratio</span>
                    <span class="text-sm text-gray-900">${report.sharpe.toFixed(2)}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Max Drawdown</span>
                    <span class="text-sm text-red-600">${report.drawdown}%</span>
                </div>
                <div class="pt-3 border-t border-gray-200">
                    <p class="text-xs text-gray-500">${report.period}</p>
                </div>
            </div>
        </div>
    `).join('');
}

function toggleView(view) {
    currentView = view;
    
    const tableView = document.getElementById('tableView');
    const cardsView = document.getElementById('cardsCardsContainer');
    const tableViewBtn = document.getElementById('tableViewBtn');
    const cardsViewBtn = document.getElementById('cardsViewBtn');
    
    if (view === 'table') {
        tableView?.classList.remove('hidden');
        cardsView?.classList.add('hidden');
        tableViewBtn?.classList.remove('bg-gray-100', 'text-gray-700');
        tableViewBtn?.classList.add('bg-blue-100', 'text-blue-700');
        cardsViewBtn?.classList.remove('bg-blue-100', 'text-blue-700');
        cardsViewBtn?.classList.add('bg-gray-100', 'text-gray-700');
    } else {
        tableView?.classList.add('hidden');
        cardsView?.classList.remove('hidden');
        cardsViewBtn?.classList.remove('bg-gray-100', 'text-gray-700');
        cardsViewBtn?.classList.add('bg-blue-100', 'text-blue-700');
        tableViewBtn?.classList.remove('bg-blue-100', 'text-blue-700');
        tableViewBtn?.classList.add('bg-gray-100', 'text-gray-700');
    }
    
    renderReports();
}

function applyFilters() {
    const strategy = document.getElementById('strategyFilter')?.value || 'all';
    const timeframe = document.getElementById('timeframeFilter')?.value || 'all';
    const instrument = document.getElementById('instrumentFilter')?.value || 'all';
    
    filteredReports = backtestReports.filter(report => {
        const matchStrategy = strategy === 'all' || report.strategy.toLowerCase().includes(strategy.toLowerCase());
        const matchInstrument = instrument === 'all' || report.instrument === instrument;
        // Timeframe filtering would be implemented based on report.date
        return matchStrategy && matchInstrument;
    });
    
    renderReports();
}

function viewReport(id) {
    const report = backtestReports.find(r => r.id === id);
    if (!report) return;
    
    const modal = document.getElementById('reportModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    
    modalTitle.textContent = `${report.strategy} - ${report.instrument} Backtest Report`;
    
    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="card bg-green-50 border-green-200">
                <p class="text-sm text-gray-600">Total Return</p>
                <p class="text-2xl font-bold text-green-600 mt-1">+${report.return}%</p>
            </div>
            <div class="card bg-blue-50 border-blue-200">
                <p class="text-sm text-gray-600">Win Rate</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">${report.winRate}%</p>
            </div>
            <div class="card bg-purple-50 border-purple-200">
                <p class="text-sm text-gray-600">Sharpe Ratio</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">${report.sharpe.toFixed(2)}</p>
            </div>
            <div class="card bg-red-50 border-red-200">
                <p class="text-sm text-gray-600">Max Drawdown</p>
                <p class="text-2xl font-bold text-red-600 mt-1">${report.drawdown}%</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card">
                <h4 class="font-semibold text-gray-900 mb-4">Performance Metrics</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Trades</span>
                        <span class="font-medium">${report.trades}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Profit Factor</span>
                        <span class="font-medium">${report.profitFactor.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Average Win</span>
                        <span class="font-medium text-green-600">$${report.avgWin.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Average Loss</span>
                        <span class="font-medium text-red-600">$${Math.abs(report.avgLoss).toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Largest Win</span>
                        <span class="font-medium text-green-600">$${report.largestWin.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Largest Loss</span>
                        <span class="font-medium text-red-600">$${Math.abs(report.largestLoss).toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Profit</span>
                        <span class="font-medium text-green-600">$${report.totalProfit.toFixed(2)}</span>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <h4 class="font-semibold text-gray-900 mb-4">Test Parameters</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Strategy</span>
                        <span class="font-medium">${report.strategy}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Instrument</span>
                        <span class="font-medium">${report.instrument}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Period</span>
                        <span class="font-medium">${report.period}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Completed Date</span>
                        <span class="font-medium">${report.date}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="px-2 py-1 rounded text-xs font-medium ${report.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                            ${report.status === 'completed' ? 'Completed' : 'Running'}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex space-x-3 pt-4 border-t border-gray-200">
            <button onclick="exportSingleReport(${report.id})" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Report
            </button>
            <button onclick="compareReport(${report.id})" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Compare
            </button>
            <button onclick="closeModal()" class="btn btn-primary ml-auto">Close</button>
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('reportModal')?.classList.add('hidden');
}

function sortTable(column) {
    // Sort logic would be implemented here
    console.log('Sort by:', column);
}

function exportReport() {
    alert('Export functionality will be implemented');
}

function exportSingleReport(id) {
    alert(`Exporting report ${id}`);
}

function compareReport(id) {
    window.location.href = `/backtesting/compare?report=${id}`;
}
</script>
@endpush
@endsection
