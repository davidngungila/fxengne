<?php

namespace App\Services;

use App\Services\OandaService;
use App\Services\StrategyEngine;
use App\Services\SignalDetector;
use App\Services\PositionSizer;
use App\Services\RiskManager;
use App\Services\NotificationService;
use App\Services\PerformanceTracker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TradingBotService
{
    protected $oandaService;
    protected $strategyEngine;
    protected $signalDetector;
    protected $positionSizer;
    protected $riskManager;
    protected $notificationService;
    protected $performanceTracker;
    protected $isRunning = false;
    protected $activeStrategies = [];

    public function __construct(
        OandaService $oandaService,
        StrategyEngine $strategyEngine,
        SignalDetector $signalDetector,
        PositionSizer $positionSizer,
        RiskManager $riskManager,
        NotificationService $notificationService,
        PerformanceTracker $performanceTracker
    ) {
        $this->oandaService = $oandaService;
        $this->strategyEngine = $strategyEngine;
        $this->signalDetector = $signalDetector;
        $this->positionSizer = $positionSizer;
        $this->riskManager = $riskManager;
        $this->notificationService = $notificationService;
        $this->performanceTracker = $performanceTracker;
    }

    /**
     * Start the automated trading bot
     */
    public function start(array $strategies = [], array $config = [])
    {
        if ($this->isRunning) {
            Log::warning('Trading bot is already running');
            return false;
        }

        $this->isRunning = true;
        $this->activeStrategies = $strategies ?: $this->getDefaultStrategies();

        Log::info('Trading bot started', [
            'strategies' => count($this->activeStrategies),
            'config' => $config
        ]);

        $this->notificationService->send('🤖 Trading Bot Started', [
            'message' => 'Automated trading system is now active',
            'strategies' => count($this->activeStrategies),
            'time' => now()->toDateTimeString()
        ]);

        return true;
    }

    /**
     * Stop the automated trading bot
     */
    public function stop()
    {
        if (!$this->isRunning) {
            return false;
        }

        $this->isRunning = false;
        $this->activeStrategies = [];

        Log::info('Trading bot stopped');

        $this->notificationService->send('🛑 Trading Bot Stopped', [
            'message' => 'Automated trading system has been stopped',
            'time' => now()->toDateTimeString()
        ]);

        return true;
    }

    /**
     * Execute one trading cycle
     */
    public function executeCycle()
    {
        if (!$this->isRunning) {
            return;
        }

        try {
            // 1. Pull live market data
            $marketData = $this->pullMarketData();

            if (empty($marketData)) {
                Log::warning('No market data available');
                return;
            }

            // 2. Run strategies simultaneously
            $signals = $this->strategyEngine->analyze($marketData, $this->activeStrategies);

            // 3. Detect valid entry signals
            $validSignals = $this->signalDetector->validate($signals, $marketData);

            foreach ($validSignals as $signal) {
                $this->processSignal($signal, $marketData);
            }

            // Update performance metrics
            $this->performanceTracker->updateMetrics();

        } catch (\Exception $e) {
            Log::error('Error in trading bot cycle', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->notificationService->send('⚠️ Trading Bot Error', [
                'error' => $e->getMessage(),
                'time' => now()->toDateTimeString()
            ]);
        }
    }

    /**
     * Pull live market data from API
     */
    protected function pullMarketData()
    {
        $instruments = $this->getMonitoredInstruments();
        $data = $this->oandaService->getPrices($instruments);

        if ($data && isset($data['prices'])) {
            return $this->formatMarketData($data['prices']);
        }

        return [];
    }

    /**
     * Process a trading signal
     */
    protected function processSignal($signal, $marketData)
    {
        try {
            $instrument = $signal['instrument'];
            $direction = $signal['direction']; // 'BUY' or 'SELL'
            $strategy = $signal['strategy'];
            $confidence = $signal['confidence'] ?? 0.5;

            // 4. Calculate position size automatically
            $positionSize = $this->positionSizer->calculate(
                $instrument,
                $direction,
                $confidence,
                $marketData[$instrument] ?? null
            );

            if ($positionSize <= 0) {
                Log::info('Position size is zero, skipping trade', [
                    'instrument' => $instrument,
                    'signal' => $signal
                ]);
                return;
            }

            // 5. Set Stop Loss & Take Profit
            $riskParams = $this->riskManager->calculateRisk(
                $instrument,
                $direction,
                $marketData[$instrument] ?? null,
                $signal
            );

            // 6. Execute trade via Broker API
            $tradeResult = $this->executeTrade([
                'instrument' => $instrument,
                'units' => $positionSize,
                'side' => $direction,
                'stop_loss' => $riskParams['stop_loss'],
                'take_profit' => $riskParams['take_profit'],
                'strategy' => $strategy,
                'signal' => $signal
            ]);

            if ($tradeResult['success']) {
                // 7. Send notification
                $this->notificationService->send('✅ Trade Executed', [
                    'instrument' => $instrument,
                    'direction' => $direction,
                    'units' => $positionSize,
                    'entry_price' => $tradeResult['entry_price'] ?? 'N/A',
                    'stop_loss' => $riskParams['stop_loss'],
                    'take_profit' => $riskParams['take_profit'],
                    'strategy' => $strategy,
                    'time' => now()->toDateTimeString()
                ]);

                // 8. Log performance & analytics
                $this->performanceTracker->logTrade([
                    'instrument' => $instrument,
                    'direction' => $direction,
                    'units' => $positionSize,
                    'entry_price' => $tradeResult['entry_price'] ?? null,
                    'stop_loss' => $riskParams['stop_loss'],
                    'take_profit' => $riskParams['take_profit'],
                    'strategy' => $strategy,
                    'signal' => $signal,
                    'executed_at' => now()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error processing signal', [
                'signal' => $signal,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Execute trade via OANDA API
     */
    protected function executeTrade(array $params)
    {
        try {
            $result = $this->oandaService->createOrder(
                $params['instrument'],
                $params['units'],
                'MARKET',
                $params['side'],
                $params['stop_loss'],
                $params['take_profit']
            );

            if (isset($result['orderFillTransaction'])) {
                return [
                    'success' => true,
                    'order_id' => $result['orderFillTransaction']['id'] ?? null,
                    'entry_price' => $result['orderFillTransaction']['price'] ?? null,
                    'data' => $result
                ];
            }

            return [
                'success' => false,
                'error' => $result['error'] ?? 'Unknown error'
            ];

        } catch (\Exception $e) {
            Log::error('Trade execution failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get monitored instruments
     */
    protected function getMonitoredInstruments()
    {
        return Cache::remember('monitored_instruments', 3600, function () {
            return [
                'EUR_USD', 'GBP_USD', 'USD_JPY', 'USD_CHF',
                'AUD_USD', 'USD_CAD', 'NZD_USD', 'XAU_USD'
            ];
        });
    }

    /**
     * Format market data for strategies
     */
    protected function formatMarketData(array $prices)
    {
        $formatted = [];

        foreach ($prices as $price) {
            $instrument = $price['instrument'];
            $formatted[$instrument] = [
                'instrument' => $instrument,
                'bid' => floatval($price['bids'][0]['price']),
                'ask' => floatval($price['asks'][0]['price']),
                'spread' => floatval($price['asks'][0]['price']) - floatval($price['bids'][0]['price']),
                'time' => $price['time'] ?? now()->toIso8601String()
            ];
        }

        return $formatted;
    }

    /**
     * Get default strategies
     */
    protected function getDefaultStrategies()
    {
        return [
            'ema_crossover',
            'rsi_oversold_overbought',
            'macd_crossover',
            'support_resistance'
        ];
    }

    /**
     * Check if bot is running
     */
    public function isRunning()
    {
        return $this->isRunning;
    }

    /**
     * Get bot status
     */
    public function getStatus()
    {
        return [
            'running' => $this->isRunning,
            'active_strategies' => count($this->activeStrategies),
            'strategies' => $this->activeStrategies,
            'last_update' => Cache::get('trading_bot_last_update'),
            'performance' => $this->performanceTracker->getSummary()
        ];
    }
}



