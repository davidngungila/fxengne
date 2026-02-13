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
use App\Http\Controllers\Api\MarketDataController;
use App\Http\Controllers\Api\TradeController;
use App\Http\Controllers\AutomatedTradingController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.index');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Two-Factor Authentication Routes
Route::middleware(['auth'])->prefix('auth/two-factor')->name('auth.two-factor.')->group(function () {
    Route::get('/setup', [\App\Http\Controllers\Auth\TwoFactorController::class, 'showSetup'])->name('setup');
    Route::post('/enable', [\App\Http\Controllers\Auth\TwoFactorController::class, 'enable'])->name('enable');
    Route::get('/verify', [\App\Http\Controllers\Auth\TwoFactorController::class, 'showVerification'])->name('verify');
    Route::post('/verify', [\App\Http\Controllers\Auth\TwoFactorController::class, 'verify'])->name('verify');
    Route::get('/recovery-codes', [\App\Http\Controllers\Auth\TwoFactorController::class, 'showRecoveryCodes'])->name('recovery-codes');
    Route::get('/recovery-codes/download', [\App\Http\Controllers\Auth\TwoFactorController::class, 'downloadRecoveryCodes'])->name('recovery-codes.download');
    Route::post('/recovery-codes/regenerate', [\App\Http\Controllers\Auth\TwoFactorController::class, 'regenerateRecoveryCodes'])->name('recovery-codes.regenerate');
    Route::post('/disable', [\App\Http\Controllers\Auth\TwoFactorController::class, 'disable'])->name('disable');
});

// Protected Routes
Route::middleware(['auth', '2fa'])->group(function () {
    // Dashboard
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
    });

    // Trading
    Route::prefix('trading')->name('trading.')->group(function () {
        Route::get('/', [TradingController::class, 'index'])->name('index');
        Route::get('/open-trades', [TradingController::class, 'openTrades'])->name('open-trades');
        Route::get('/open-trades/export', [TradingController::class, 'exportOpenTrades'])->name('open-trades.export');
        Route::get('/history', [TradingController::class, 'history'])->name('history');
        Route::get('/history/export', [TradingController::class, 'exportHistory'])->name('history.export');
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
        Route::get('/live-market', [MarketToolsController::class, 'liveMarket'])->name('live-market');
        Route::get('/economic-calendar', [MarketToolsController::class, 'economicCalendar'])->name('economic-calendar');
        Route::get('/spread-monitor', [MarketToolsController::class, 'spreadMonitor'])->name('spread-monitor');
        Route::get('/trading-sessions', [MarketToolsController::class, 'tradingSessions'])->name('trading-sessions');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
        Route::get('/security', [ProfileController::class, 'security'])->name('security');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::post('/upload-image', [ProfileController::class, 'uploadProfileImage'])->name('upload-image');
        Route::post('/remove-image', [ProfileController::class, 'removeProfileImage'])->name('remove-image');
        Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications');
        Route::get('/logs', [ProfileController::class, 'logs'])->name('logs');
    });

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('users.update-role');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
});

    // API Routes for OANDA Integration
    Route::prefix('api')->middleware('web')->group(function () {
    // Market Data
    Route::get('/market/prices', [MarketDataController::class, 'getPrices'])->name('api.market.prices');
    Route::get('/market/instruments', [MarketDataController::class, 'getInstruments'])->name('api.market.instruments');
    Route::get('/market/account-summary', [MarketDataController::class, 'getAccountSummary'])->name('api.market.account-summary');
    Route::get('/market/xauusd/candles', [MarketDataController::class, 'getXAUUSDCandles'])->name('api.market.xauusd.candles');
    
    // Trade Execution
    Route::post('/trade/execute', [TradeController::class, 'executeOrder'])->name('api.trade.execute');
    Route::get('/trade/open', [TradeController::class, 'getOpenTrades'])->name('api.trade.open');
    Route::post('/trade/close/{tradeId}', [TradeController::class, 'closeTrade'])->name('api.trade.close');
    Route::get('/trade/history', [TradeController::class, 'getTradeHistory'])->name('api.trade.history');
    
    // Automated Trading Bot
    Route::get('/bot/status', [AutomatedTradingController::class, 'status'])->name('api.bot.status');
    Route::post('/bot/start', [AutomatedTradingController::class, 'start'])->name('api.bot.start');
    Route::post('/bot/stop', [AutomatedTradingController::class, 'stop'])->name('api.bot.stop');
    Route::post('/bot/execute-cycle', [AutomatedTradingController::class, 'executeCycle'])->name('api.bot.execute-cycle');
    
    // Market Signals
    Route::get('/signals/active', [SignalController::class, 'getActiveSignals'])->name('api.signals.active');
    Route::get('/signals/history', [SignalController::class, 'getSignalHistory'])->name('api.signals.history');
    Route::post('/signals/generate', [SignalController::class, 'generateSignals'])->name('api.signals.generate');
    Route::get('/signals/instrument/{instrument}', [SignalController::class, 'getSignalsByInstrument'])->name('api.signals.instrument');
    Route::get('/signals/strategy/{strategy}', [SignalController::class, 'getSignalsByStrategy'])->name('api.signals.strategy');
    
    // Broker & Execution
    Route::get('/broker/test-connection', [BrokerController::class, 'testConnection'])->name('api.broker.test-connection');
    Route::get('/broker/execution-logs', [BrokerController::class, 'getExecutionLogs'])->name('api.broker.execution-logs');
    Route::get('/broker/vps-status', [BrokerController::class, 'getVpsStatus'])->name('api.broker.vps-status');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index'])->name('api.notifications.index');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\Api\NotificationController::class, 'unreadCount'])->name('api.notifications.unread-count');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead'])->name('api.notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead'])->name('api.notifications.read-all');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\Api\NotificationController::class, 'destroy'])->name('api.notifications.destroy');
});

}); // End auth middleware
