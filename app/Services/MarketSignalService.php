<?php

namespace App\Services;

use App\Services\OandaService;
use App\Services\StrategyEngine;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MarketSignalService
{
    protected $oandaService;
    protected $strategyEngine;
    protected $instruments = [
        'EUR_USD', 'GBP_USD', 'USD_JPY', 'USD_CHF',
        'AUD_USD', 'USD_CAD', 'NZD_USD', 'XAU_USD'
    ];

    public function __construct(OandaService $oandaService, StrategyEngine $strategyEngine)
    {
        $this->oandaService = $oandaService;
        $this->strategyEngine = $strategyEngine;
    }

    /**
     * Generate signals based on market analysis
     */
    public function generateSignals(array $strategies = [])
    {
        try {
            // Pull live market data
            $marketData = $this->pullMarketData();

            if (empty($marketData)) {
                return [];
            }

            // Run strategies
            $signals = $this->strategyEngine->analyze($marketData, $strategies);

            // Enhance signals with additional analysis
            $enhancedSignals = $this->enhanceSignals($signals, $marketData);

            // Store active signals
            $this->storeActiveSignals($enhancedSignals);

            return $enhancedSignals;

        } catch (\Exception $e) {
            Log::error('Error generating signals', [
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Get active signals
     */
    public function getActiveSignals()
    {
        $signals = Cache::get('active_signals', []);
        
        // Filter expired signals (older than 1 hour)
        $activeSignals = array_filter($signals, function($signal) {
            $createdAt = $signal['created_at'] ?? now()->subHour();
            return now()->diffInMinutes($createdAt) < 60;
        });

        // Update cache
        Cache::put('active_signals', $activeSignals, 3600);

        return array_values($activeSignals);
    }

    /**
     * Get signal history
     */
    public function getSignalHistory($limit = 100)
    {
        $history = Cache::get('signal_history', []);
        
        // Sort by created_at descending
        usort($history, function($a, $b) {
            $timeA = $a['created_at'] ?? now();
            $timeB = $b['created_at'] ?? now();
            return $timeB <=> $timeA;
        });

        return array_slice($history, 0, $limit);
    }

    /**
     * Store signal in history
     */
    public function storeSignalHistory($signal)
    {
        $history = Cache::get('signal_history', []);
        
        $history[] = [
            ...$signal,
            'created_at' => now()->toDateTimeString(),
            'id' => uniqid('signal_', true)
        ];

        // Keep only last 1000 signals
        if (count($history) > 1000) {
            $history = array_slice($history, -1000);
        }

        Cache::put('signal_history', $history, 86400 * 30); // 30 days
    }

    /**
     * Pull live market data
     */
    protected function pullMarketData()
    {
        $data = $this->oandaService->getPrices($this->instruments);

        if ($data && isset($data['prices'])) {
            return $this->formatMarketData($data['prices']);
        }

        return [];
    }

    /**
     * Format market data
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
     * Enhance signals with additional analysis
     */
    protected function enhanceSignals(array $signals, array $marketData)
    {
        $enhanced = [];

        foreach ($signals as $signal) {
            $instrument = $signal['instrument'];
            $marketInfo = $marketData[$instrument] ?? null;

            if (!$marketInfo) {
                continue;
            }

            // Add market context
            $enhanced[] = [
                ...$signal,
                'current_price' => $marketInfo['bid'],
                'spread' => $marketInfo['spread'],
                'spread_pips' => $this->calculatePips($marketInfo['spread'], $instrument),
                'strength' => $this->calculateSignalStrength($signal),
                'timeframe' => $this->getRecommendedTimeframe($signal),
                'risk_level' => $this->assessRiskLevel($signal, $marketInfo),
                'created_at' => now()->toDateTimeString(),
                'expires_at' => now()->addHour()->toDateTimeString(),
                'id' => uniqid('signal_', true)
            ];
        }

        return $enhanced;
    }

    /**
     * Store active signals
     */
    protected function storeActiveSignals(array $signals)
    {
        // Store current active signals
        Cache::put('active_signals', $signals, 3600);

        // Store in history
        foreach ($signals as $signal) {
            $this->storeSignalHistory($signal);
        }
    }

    /**
     * Calculate pips
     */
    protected function calculatePips($spread, $instrument)
    {
        // For most pairs, 1 pip = 0.0001
        // For JPY pairs, 1 pip = 0.01
        // For XAU_USD, 1 pip = 0.01
        
        if (strpos($instrument, 'JPY') !== false || $instrument === 'XAU_USD') {
            return $spread * 100;
        }
        
        return $spread * 10000;
    }

    /**
     * Calculate signal strength
     */
    protected function calculateSignalStrength($signal)
    {
        $confidence = $signal['confidence'] ?? 0.5;
        
        if ($confidence >= 0.8) {
            return 'Strong';
        } elseif ($confidence >= 0.6) {
            return 'Moderate';
        } else {
            return 'Weak';
        }
    }

    /**
     * Get recommended timeframe
     */
    protected function getRecommendedTimeframe($signal)
    {
        $strategy = $signal['strategy'] ?? '';
        
        $timeframeMap = [
            'ema_crossover' => 'H1',
            'rsi_oversold_overbought' => 'M15',
            'macd_crossover' => 'H1',
            'support_resistance' => 'H4',
            'bollinger_bands' => 'M15',
            'moving_average_convergence' => 'D'
        ];

        return $timeframeMap[$strategy] ?? 'H1';
    }

    /**
     * Assess risk level
     */
    protected function assessRiskLevel($signal, $marketInfo)
    {
        $spreadPercent = ($marketInfo['spread'] / $marketInfo['bid']) * 100;
        $confidence = $signal['confidence'] ?? 0.5;

        if ($spreadPercent > 0.15 || $confidence < 0.5) {
            return 'High';
        } elseif ($spreadPercent > 0.1 || $confidence < 0.7) {
            return 'Medium';
        } else {
            return 'Low';
        }
    }

    /**
     * Get signals by instrument
     */
    public function getSignalsByInstrument($instrument)
    {
        $signals = $this->getActiveSignals();
        
        return array_filter($signals, function($signal) use ($instrument) {
            return $signal['instrument'] === $instrument;
        });
    }

    /**
     * Get signals by strategy
     */
    public function getSignalsByStrategy($strategy)
    {
        $signals = $this->getActiveSignals();
        
        return array_filter($signals, function($signal) use ($strategy) {
            return ($signal['strategy'] ?? '') === $strategy;
        });
    }
}



