<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TradingController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\RiskManagementController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\SignalController;
use App\Http\Controllers\BacktestingController;
use App\Http\Controllers\BrokerController;
use App\Http\Controllers\MarketToolsController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

// Dashboard
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
});

// Trading
Route::prefix('trading')->name('trading.')->group(function () {
    Route::get('/', [TradingController::class, 'index'])->name('index');
    Route::get('/open-trades', [TradingController::class, 'openTrades'])->name('open-trades');
    Route::get('/history', [TradingController::class, 'history'])->name('history');
    Route::get('/manual-entry', [TradingController::class, 'manualEntry'])->name('manual-entry');
});

// Strategies
Route::prefix('strategies')->name('strategies.')->group(function () {
    Route::get('/', [StrategyController::class, 'index'])->name('index');
    Route::get('/create', [StrategyController::class, 'create'])->name('create');
    Route::get('/backtesting', [StrategyController::class, 'backtesting'])->name('backtesting');
    Route::get('/performance', [StrategyController::class, 'performance'])->name('performance');
});

// Risk Management
Route::prefix('risk')->name('risk.')->group(function () {
    Route::get('/', [RiskManagementController::class, 'index'])->name('index');
    Route::get('/settings', [RiskManagementController::class, 'settings'])->name('settings');
    Route::get('/daily-limits', [RiskManagementController::class, 'dailyLimits'])->name('daily-limits');
    Route::get('/drawdown', [RiskManagementController::class, 'drawdown'])->name('drawdown');
    Route::get('/exposure', [RiskManagementController::class, 'exposure'])->name('exposure');
    Route::get('/lot-calculator', [RiskManagementController::class, 'lotCalculator'])->name('lot-calculator');
});

// Analytics
Route::prefix('analytics')->name('analytics.')->group(function () {
    Route::get('/', [AnalyticsController::class, 'index'])->name('index');
    Route::get('/performance', [AnalyticsController::class, 'performance'])->name('performance');
    Route::get('/pair-performance', [AnalyticsController::class, 'pairPerformance'])->name('pair-performance');
    Route::get('/time-analysis', [AnalyticsController::class, 'timeAnalysis'])->name('time-analysis');
    Route::get('/win-loss', [AnalyticsController::class, 'winLoss'])->name('win-loss');
    Route::get('/risk-metrics', [AnalyticsController::class, 'riskMetrics'])->name('risk-metrics');
});

// Trading Journal
Route::prefix('journal')->name('journal.')->group(function () {
    Route::get('/', [JournalController::class, 'index'])->name('index');
    Route::get('/entries', [JournalController::class, 'entries'])->name('entries');
    Route::get('/add-note', [JournalController::class, 'addNote'])->name('add-note');
    Route::get('/mistakes', [JournalController::class, 'mistakes'])->name('mistakes');
    Route::get('/weekly-review', [JournalController::class, 'weeklyReview'])->name('weekly-review');
});

// Signals
Route::prefix('signals')->name('signals.')->group(function () {
    Route::get('/', [SignalController::class, 'index'])->name('index');
    Route::get('/active', [SignalController::class, 'active'])->name('active');
    Route::get('/history', [SignalController::class, 'history'])->name('history');
    Route::get('/alerts', [SignalController::class, 'alerts'])->name('alerts');
});

// Backtesting
Route::prefix('backtesting')->name('backtesting.')->group(function () {
    Route::get('/', [BacktestingController::class, 'index'])->name('index');
    Route::get('/run', [BacktestingController::class, 'run'])->name('run');
    Route::get('/historical-data', [BacktestingController::class, 'historicalData'])->name('historical-data');
    Route::get('/reports', [BacktestingController::class, 'reports'])->name('reports');
    Route::get('/compare', [BacktestingController::class, 'compare'])->name('compare');
});

// Broker & Execution
Route::prefix('broker')->name('broker.')->group(function () {
    Route::get('/', [BrokerController::class, 'index'])->name('index');
    Route::get('/connection', [BrokerController::class, 'connection'])->name('connection');
    Route::get('/api-settings', [BrokerController::class, 'apiSettings'])->name('api-settings');
    Route::get('/execution-logs', [BrokerController::class, 'executionLogs'])->name('execution-logs');
    Route::get('/vps-status', [BrokerController::class, 'vpsStatus'])->name('vps-status');
});

// Market Tools
Route::prefix('market-tools')->name('market-tools.')->group(function () {
    Route::get('/', [MarketToolsController::class, 'index'])->name('index');
    Route::get('/economic-calendar', [MarketToolsController::class, 'economicCalendar'])->name('economic-calendar');
    Route::get('/spread-monitor', [MarketToolsController::class, 'spreadMonitor'])->name('spread-monitor');
    Route::get('/trading-sessions', [MarketToolsController::class, 'tradingSessions'])->name('trading-sessions');
});

// Profile
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    Route::get('/security', [ProfileController::class, 'security'])->name('security');
    Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications');
    Route::get('/logs', [ProfileController::class, 'logs'])->name('logs');
});
