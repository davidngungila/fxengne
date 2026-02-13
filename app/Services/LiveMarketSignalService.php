<?php

namespace App\Services;

use App\Models\MLModel;
use App\Services\EconomicCalendarService;
use Carbon\Carbon;

class LiveMarketSignalService
{
    protected $calendarService;

    public function __construct(EconomicCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * Get Gold Alpha Signal (TFT + 8-State Model combined)
     */
    public function getGoldAlphaSignal(float $currentPrice, ?array $tftModel = null, ?array $event = null): array
    {
        $baseSignal = [
            'direction' => 'FLAT',
            'confidence' => 0,
            'alpha_score' => 0,
            'model_state' => 'AWAITING_TRIGGER',
            'countdown' => null,
            'reason' => 'No active signals',
        ];

        // Get TFT model signal if available
        $tftSignal = null;
        if ($tftModel) {
            $tftSignal = $this->getTFTModelSignal($tftModel, $currentPrice);
        }

        // Get 8-State Model signal from event
        $eventSignal = null;
        if ($event) {
            $eventSignal = $this->calendarService->generateTradingSignal($event);
            $baseSignal['alpha_score'] = $this->calendarService->calculateGoldSensitivityScore([$event]);
            $baseSignal['model_state'] = $eventSignal['state'];
            $baseSignal['countdown'] = $this->calculateCountdown($event);
        }

        // Combine signals
        if ($tftSignal && $eventSignal) {
            // Both models active - combine with weighted average
            $tftWeight = 0.6; // TFT gets 60% weight
            $eventWeight = 0.4; // Event gets 40% weight

            $combinedConfidence = ($tftSignal['confidence'] * $tftWeight) + ($eventSignal['confidence'] * $eventWeight);
            
            // Determine direction (both must agree or TFT takes precedence)
            if ($tftSignal['direction'] === $eventSignal['signal']) {
                $baseSignal['direction'] = $tftSignal['direction'];
                $baseSignal['confidence'] = min(100, $combinedConfidence * 100);
            } elseif ($tftSignal['confidence'] > 0.65) {
                $baseSignal['direction'] = $tftSignal['direction'];
                $baseSignal['confidence'] = $tftSignal['confidence'] * 100;
            } else {
                $baseSignal['direction'] = $eventSignal['signal'];
                $baseSignal['confidence'] = $eventSignal['confidence'] * 100;
            }

            $baseSignal['reason'] = "TFT: {$tftSignal['direction']} ({$tftSignal['confidence']*100}%) + Event: {$eventSignal['signal']} ({$eventSignal['confidence']*100}%)";
        } elseif ($tftSignal) {
            // Only TFT model
            $baseSignal['direction'] = $tftSignal['direction'];
            $baseSignal['confidence'] = $tftSignal['confidence'] * 100;
            $baseSignal['reason'] = "TFT Model: {$tftSignal['reason']}";
        } elseif ($eventSignal) {
            // Only event signal
            $baseSignal['direction'] = $eventSignal['signal'];
            $baseSignal['confidence'] = $eventSignal['confidence'] * 100;
            $baseSignal['reason'] = $eventSignal['reason'];
        }

        return $baseSignal;
    }

    /**
     * Get TFT model signal
     */
    protected function getTFTModelSignal(array $model, float $currentPrice): ?array
    {
        // In production, this would call the actual TFT model
        // For now, return a placeholder based on model status
        if (!isset($model['status']) || $model['status'] !== 'active') {
            return null;
        }

        // Simulated TFT output (replace with actual model inference)
        return [
            'direction' => 'SELL', // BUY, SELL, or HOLD
            'confidence' => 0.78, // 0-1
            'predicted_price' => $currentPrice * 0.995, // 0.5% down
            'reason' => 'TFT quantile output indicates bearish momentum',
        ];
    }

    /**
     * Calculate countdown to next state change
     */
    protected function calculateCountdown(array $event): ?string
    {
        if (!isset($event['time']) || !isset($event['date'])) {
            return null;
        }

        $eventTime = $this->parseEventTime($event['date'], $event['time']);
        $now = now();
        $secondsUntil = $now->diffInSeconds($eventTime, false);

        if ($secondsUntil > 0) {
            $minutes = floor($secondsUntil / 60);
            $seconds = $secondsUntil % 60;
            return sprintf('%02d:%02d', $minutes, $seconds);
        }

        // After event, calculate time until next state
        $minutesSince = abs($now->diffInMinutes($eventTime));
        
        if ($minutesSince < 1) {
            return '00:00'; // VOLATILITY_LOCK
        } elseif ($minutesSince < 5) {
            $remaining = 5 - $minutesSince;
            return sprintf('00:%02d', $remaining * 60);
        } elseif ($minutesSince < 20) {
            $remaining = 20 - $minutesSince;
            return sprintf('%02d:%02d', floor($remaining), ($remaining - floor($remaining)) * 60);
        }

        return null;
    }

    /**
     * Parse event time
     */
    protected function parseEventTime($date, string $time): Carbon
    {
        if ($date instanceof Carbon) {
            $carbon = $date->copy();
        } else {
            $carbon = Carbon::parse($date);
        }
        
        if (preg_match('/(\d+):(\d+)(am|pm)?/i', $time, $matches)) {
            $hour = (int) $matches[1];
            $minute = (int) $matches[2];
            $period = strtolower($matches[3] ?? '');
            
            if ($period === 'pm' && $hour < 12) {
                $hour += 12;
            } elseif ($period === 'am' && $hour === 12) {
                $hour = 0;
            }
            
            $carbon->setTime($hour, $minute, 0);
        }
        
        return $carbon;
    }

    /**
     * Calculate entry zones based on Fibonacci retracement
     */
    public function calculateEntryZones(float $preNewsPrice, float $postNewsLow, float $postNewsHigh): array
    {
        $range = $postNewsHigh - $postNewsLow;
        
        return [
            '38.2' => $postNewsLow + ($range * 0.382),
            '50.0' => $postNewsLow + ($range * 0.50),
            '61.8' => $postNewsLow + ($range * 0.618),
        ];
    }

    /**
     * Calculate position size based on risk and volatility
     */
    public function calculatePositionSize(float $accountEquity, float $riskPercent, float $entryPrice, float $stopLoss, float $atr): array
    {
        $dollarRisk = $accountEquity * ($riskPercent / 100);
        $priceRisk = abs($entryPrice - $stopLoss);
        
        // For XAUUSD, 1 lot = 100 oz, pip value = $10 per lot
        $pipValue = 10; // $10 per lot per pip
        $pipsRisk = $priceRisk * 100; // Convert to pips (assuming 2 decimal places)
        
        $lotSize = $dollarRisk / ($pipsRisk * $pipValue);
        
        // Adjust for volatility regime
        $volatilityMultiplier = $atr > 20 ? 0.8 : ($atr > 15 ? 0.9 : 1.0);
        $adjustedLotSize = $lotSize * $volatilityMultiplier;
        
        return [
            'lot_size' => round($adjustedLotSize, 2),
            'dollar_risk' => $dollarRisk,
            'pips_risk' => round($pipsRisk, 1),
            'risk_reward_ratio' => $this->calculateRiskReward($entryPrice, $stopLoss, $atr),
        ];
    }

    /**
     * Calculate risk/reward ratio
     */
    protected function calculateRiskReward(float $entry, float $stopLoss, float $atr): float
    {
        $risk = abs($entry - $stopLoss);
        $reward = $atr * 1.5; // Target 1.5x ATR
        
        return $risk > 0 ? round($reward / $risk, 2) : 0;
    }
}

