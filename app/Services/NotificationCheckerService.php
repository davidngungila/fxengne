<?php

namespace App\Services;

use App\Models\Notification;
use App\Services\OandaService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Cache;

class NotificationCheckerService
{
    protected $oandaService;
    protected $notificationService;
    protected $lastTradeCheck;
    protected $lastSignalCheck;

    public function __construct(OandaService $oandaService, NotificationService $notificationService)
    {
        $this->oandaService = $oandaService;
        $this->notificationService = $notificationService;
        $this->lastTradeCheck = Cache::get('last_trade_check', now()->subHour());
        $this->lastSignalCheck = Cache::get('last_signal_check', now()->subHour());
    }

    /**
     * Check for new trades and create notifications
     */
    public function checkNewTrades()
    {
        try {
            $response = $this->oandaService->getOpenTrades();
            
            if ($response && isset($response['trades'])) {
                $trades = $response['trades'] ?? [];
                
                foreach ($trades as $trade) {
                    $tradeId = $trade['id'] ?? null;
                    if (!$tradeId) continue;
                    
                    $cacheKey = "trade_notified_{$tradeId}";
                    
                    // Check if we've already notified about this trade
                    if (!Cache::has($cacheKey)) {
                        $this->notificationService->sendTradeNotification([
                            'instrument' => $trade['instrument'] ?? 'N/A',
                            'direction' => ($trade['currentUnits'] ?? 0) > 0 ? 'BUY' : 'SELL',
                            'units' => abs($trade['currentUnits'] ?? 0),
                            'entry_price' => $trade['openPrice'] ?? 0,
                            'stop_loss' => $trade['stopLossOrder']['price'] ?? 'Not set',
                            'take_profit' => $trade['takeProfitOrder']['price'] ?? 'Not set',
                            'strategy' => 'Manual',
                        ]);
                        
                        // Cache for 1 hour
                        Cache::put($cacheKey, true, now()->addHour());
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error checking new trades for notifications', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check for closed trades
     */
    public function checkClosedTrades()
    {
        try {
            // Get transactions instead of trades for closed trades
            $response = $this->oandaService->getTradeHistory(now()->subDay()->toIso8601String(), now()->toIso8601String(), 50);
            
            if ($response && isset($response['trades'])) {
                $trades = $response['trades'] ?? [];
                
                foreach ($trades as $trade) {
                    $state = $trade['state'] ?? 'OPEN';
                    if ($state === 'CLOSED') {
                        $tradeId = $trade['id'] ?? null;
                        if (!$tradeId) continue;
                        
                        $cacheKey = "trade_closed_notified_{$tradeId}";
                        
                        if (!Cache::has($cacheKey)) {
                            $this->notificationService->sendTradeClosedNotification([
                                'instrument' => $trade['instrument'] ?? 'N/A',
                                'realized_pl' => $trade['realizedPL'] ?? 0,
                                'close_time' => $trade['closeTime'] ?? now(),
                            ]);
                            
                            Cache::put($cacheKey, true, now()->addDay());
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error checking closed trades for notifications', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check for risk alerts
     */
    public function checkRiskAlerts()
    {
        try {
            $accountResponse = $this->oandaService->getAccountSummary();
            
            if ($accountResponse && isset($accountResponse['account'])) {
                $account = $accountResponse['account'];
                $balance = floatval($account['balance'] ?? 10000);
                $equity = floatval($account['NAV'] ?? $account['balance'] ?? 10000);
                $marginUsed = floatval($account['marginUsed'] ?? 0);
                $marginAvailable = floatval($account['marginAvailable'] ?? 10000);
                
                // Check drawdown
                if ($balance > 0) {
                    $drawdown = (($balance - $equity) / $balance) * 100;
                    if ($drawdown > 5) {
                        $cacheKey = "drawdown_alert_" . date('Y-m-d-H');
                        if (!Cache::has($cacheKey)) {
                            $this->notificationService->sendDrawdownAlert([
                                'drawdown' => number_format($drawdown, 2),
                                'limit' => 10,
                                'equity' => $equity,
                                'balance' => $balance,
                            ]);
                            Cache::put($cacheKey, true, now()->addHour());
                        }
                    }
                }
                
                // Check margin level
                $marginLevel = $account['marginCallMarginUsed'] ?? 0;
                if ($marginLevel > 0 && $marginLevel < 200) {
                    $cacheKey = "margin_alert_" . date('Y-m-d-H');
                    if (!Cache::has($cacheKey)) {
                        $this->notificationService->sendRiskAlert([
                            'message' => "Low margin level: {$marginLevel}%",
                            'margin_level' => $marginLevel,
                            'margin_used' => $marginUsed,
                        ]);
                        Cache::put($cacheKey, true, now()->addHour());
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error checking risk alerts', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check for new signals
     */
    public function checkNewSignals()
    {
        try {
            // This would integrate with your signal service
            // For now, we'll check if there are any new signals generated
            $recentSignals = Notification::where('type', 'signal_generated')
                ->where('created_at', '>', now()->subMinutes(5))
                ->count();
            
            // If signals are being generated, they should already create notifications
            // This is just a placeholder for additional signal checking logic
        } catch (\Exception $e) {
            \Log::error('Error checking new signals', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Run all checks
     */
    public function runAllChecks()
    {
        $this->checkNewTrades();
        $this->checkClosedTrades();
        $this->checkRiskAlerts();
        $this->checkNewSignals();
    }
}

