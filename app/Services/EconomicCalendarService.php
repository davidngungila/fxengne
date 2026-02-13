<?php

namespace App\Services;

use Carbon\Carbon;

class EconomicCalendarService
{
    /**
     * Calculate Gold Sensitivity Score (Alpha Score) for a day
     * Range: 0-10
     */
    public function calculateGoldSensitivityScore(array $events): float
    {
        $score = 0;
        $highImpactUSD = 0;
        $highImpactOther = 0;
        $beatsForecast = 0;
        
        foreach ($events as $event) {
            if ($event['impact'] === 'high') {
                if ($event['currency'] === 'USD') {
                    $highImpactUSD++;
                    $score += 3; // High impact USD events are critical for gold
                } else {
                    $highImpactOther++;
                    $score += 1.5;
                }
            } elseif ($event['impact'] === 'medium') {
                if ($event['currency'] === 'USD') {
                    $score += 1;
                } else {
                    $score += 0.5;
                }
            }
            
            // Check if actual beats forecast (for USD, this is bearish for gold)
            if (isset($event['actual']) && isset($event['forecast'])) {
                $actual = $this->parseValue($event['actual']);
                $forecast = $this->parseValue($event['forecast']);
                
                if ($actual !== null && $forecast !== null) {
                    if ($event['currency'] === 'USD' && $actual > $forecast) {
                        $beatsForecast++;
                        $score += 1.5; // USD beating forecast = stronger USD = bearish gold
                    }
                }
            }
        }
        
        // Cap at 10
        return min(10, round($score, 1));
    }

    /**
     * Apply 8-State Decision Model to generate trading signal
     */
    public function generateTradingSignal(array $event): array
    {
        if (!isset($event['actual']) || !isset($event['forecast'])) {
            return [
                'signal' => 'HOLD',
                'confidence' => 0,
                'reason' => 'Waiting for actual data',
                'state' => 'AWAITING_TRIGGER'
            ];
        }

        $actual = $this->parseValue($event['actual']);
        $forecast = $this->parseValue($event['forecast']);
        $previous = isset($event['previous']) ? $this->parseValue($event['previous']) : null;
        
        if ($actual === null || $forecast === null) {
            return [
                'signal' => 'HOLD',
                'confidence' => 0,
                'reason' => 'Invalid data format',
                'state' => 'AWAITING_TRIGGER'
            ];
        }

        // Determine comparison state
        $diff = abs($actual - $forecast);
        $percentDiff = $forecast != 0 ? ($diff / abs($forecast)) * 100 : 0;
        
        if ($percentDiff < 0.1) {
            $comparison = 'equal'; // In line
        } elseif ($actual > $forecast) {
            $comparison = 'hotter'; // Better/Hotter
        } else {
            $comparison = 'worse'; // Much worse
        }

        // Currency-specific logic
        $currency = $event['currency'] ?? 'USD';
        $isUSD = $currency === 'USD';
        
        // For USD events: Better = Stronger USD = Bearish Gold
        // For other currencies: Logic may differ
        $usdReaction = $this->getUSDReaction($comparison, $currency, $actual, $forecast);
        $xauusdReaction = $this->getXAUUSDReaction($usdReaction, $isUSD);
        
        // Generate signal based on 8-State Model
        $signal = $this->getSignalFromReaction($xauusdReaction, $comparison);
        $confidence = $this->calculateConfidence($event['impact'], $percentDiff, $comparison);
        
        return [
            'signal' => $signal,
            'confidence' => $confidence,
            'comparison' => $comparison,
            'usd_reaction' => $usdReaction,
            'xauusd_reaction' => $xauusdReaction,
            'entry_logic' => $this->getEntryLogic($signal, $comparison),
            'stop_loss' => $this->getStopLoss($signal, $comparison),
            'take_profit_1' => $this->getTakeProfit1($signal, $comparison),
            'take_profit_2' => $this->getTakeProfit2($signal, $comparison),
            'invalidation' => $this->getInvalidation($signal, $comparison),
            'state' => $this->getModelState($event),
            'reason' => $this->getSignalReason($event, $comparison, $actual, $forecast),
        ];
    }

    /**
     * Get USD reaction based on comparison
     */
    protected function getUSDReaction(string $comparison, string $currency, ?float $actual, ?float $forecast): string
    {
        if ($currency === 'USD') {
            switch ($comparison) {
                case 'worse':
                    return 'bearish'; // Weaker USD
                case 'hotter':
                    return 'bullish'; // Stronger USD
                case 'equal':
                    return 'neutral';
            }
        } else {
            // For non-USD currencies, inverse logic for gold
            switch ($comparison) {
                case 'worse':
                    return 'bullish'; // Weaker non-USD = Stronger USD
                case 'hotter':
                    return 'bearish'; // Stronger non-USD = Weaker USD
                case 'equal':
                    return 'neutral';
            }
        }
        
        return 'neutral';
    }

    /**
     * Get XAUUSD reaction based on USD reaction
     */
    protected function getXAUUSDReaction(string $usdReaction, bool $isUSD): string
    {
        // Gold moves inverse to USD
        switch ($usdReaction) {
            case 'bullish': // Strong USD
                return 'bearish'; // Weak Gold
            case 'bearish': // Weak USD
                return 'bullish'; // Strong Gold
            default:
                return 'neutral';
        }
    }

    /**
     * Get trading signal from XAUUSD reaction
     */
    protected function getSignalFromReaction(string $xauusdReaction, string $comparison): string
    {
        if ($comparison === 'equal') {
            return 'HOLD'; // Wait for clearer signal
        }
        
        switch ($xauusdReaction) {
            case 'bullish':
                return 'BUY';
            case 'bearish':
                return 'SELL';
            default:
                return 'HOLD';
        }
    }

    /**
     * Calculate confidence level (0-1)
     */
    protected function calculateConfidence(string $impact, float $percentDiff, string $comparison): float
    {
        $baseConfidence = [
            'high' => 0.7,
            'medium' => 0.5,
            'low' => 0.3,
        ][$impact] ?? 0.3;
        
        // Increase confidence if significant deviation
        if ($percentDiff > 5) {
            $baseConfidence += 0.2;
        } elseif ($percentDiff > 2) {
            $baseConfidence += 0.1;
        }
        
        // Reduce confidence if in line
        if ($comparison === 'equal') {
            $baseConfidence *= 0.5;
        }
        
        return min(1.0, max(0.0, $baseConfidence));
    }

    /**
     * Get entry logic based on 8-State Model
     */
    protected function getEntryLogic(string $signal, string $comparison): string
    {
        if ($signal === 'HOLD') {
            return 'Fade the Move. Wait 5min, trade opposite of knee-jerk.';
        }
        
        switch ($comparison) {
            case 'worse':
                return 'Pullback Buy. Wait for USD strength to fade.';
            case 'hotter':
                return 'Breakout Sell. Sell on USD momentum.';
            default:
                return 'Wait for retracement, then enter on confirmation.';
        }
    }

    /**
     * Get stop loss recommendation
     */
    protected function getStopLoss(string $signal, string $comparison): string
    {
        if ($comparison === 'equal') {
            return '2x ATR from entry';
        }
        
        return $signal === 'BUY' 
            ? 'Below recent swing low' 
            : 'Above recent swing high';
    }

    /**
     * Get take profit 1
     */
    protected function getTakeProfit1(string $signal, string $comparison): string
    {
        if ($comparison === 'equal') {
            return '1.5x ATR';
        }
        
        return $signal === 'BUY'
            ? 'Previous resistance becomes support'
            : 'Previous support becomes resistance';
    }

    /**
     * Get take profit 2
     */
    protected function getTakeProfit2(string $signal, string $comparison): string
    {
        if ($comparison === 'equal') {
            return '2.5x ATR';
        }
        
        return $signal === 'BUY'
            ? '50% retracement of recent drop'
            : '50% retracement of recent rally';
    }

    /**
     * Get invalidation condition
     */
    protected function getInvalidation(string $signal, string $comparison): string
    {
        if ($comparison === 'equal') {
            return 'Price returns to pre-news level';
        }
        
        return $signal === 'BUY'
            ? 'USD regains strength >15min'
            : 'USD weakens >15min';
    }

    /**
     * Get model state based on event timing
     */
    protected function getModelState(array $event): string
    {
        if (!isset($event['time']) || !isset($event['date'])) {
            return 'AWAITING_TRIGGER';
        }
        
        $eventTime = $this->parseEventTime($event['date'], $event['time']);
        $now = now();
        $minutesUntil = $now->diffInMinutes($eventTime, false);
        
        if ($minutesUntil > 15) {
            return 'AWAITING_TRIGGER';
        } elseif ($minutesUntil > 0 && $minutesUntil <= 15) {
            return 'PRE_NEWS';
        } elseif ($minutesUntil <= 0 && $minutesUntil >= -1) {
            return 'VOLATILITY_LOCK';
        } elseif ($minutesUntil >= -5 && $minutesUntil < -1) {
            return 'INITIAL_REACTION';
        } elseif ($minutesUntil >= -20 && $minutesUntil < -5) {
            return 'TRADE_EXECUTION_WINDOW';
        } else {
            return 'POST_NEWS';
        }
    }

    /**
     * Get signal reason
     */
    protected function getSignalReason(array $event, string $comparison, ?float $actual, ?float $forecast): string
    {
        $currency = $event['currency'] ?? 'USD';
        $eventName = $event['event'] ?? 'Event';
        
        if ($comparison === 'equal') {
            return "{$eventName} came in line with forecast. Wait for clearer direction.";
        }
        
        $direction = $comparison === 'hotter' ? 'hotter' : 'weaker';
        $usdImpact = $currency === 'USD' 
            ? ($comparison === 'hotter' ? 'strengthens' : 'weakens')
            : 'mixed impact';
        
        return "{$eventName} came {$direction} than forecast. USD {$usdImpact}, Gold reacts inversely.";
    }

    /**
     * Parse numeric value from string
     */
    protected function parseValue(?string $value): ?float
    {
        if (empty($value)) {
            return null;
        }
        
        // Remove % signs and other non-numeric characters except decimal point and minus
        $cleaned = preg_replace('/[^0-9.\-]/', '', $value);
        
        return is_numeric($cleaned) ? (float) $cleaned : null;
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
        
        // Parse time (format: "9:30pm" or "21:30")
        $timeParts = preg_match('/(\d+):(\d+)(am|pm)?/i', $time, $matches);
        
        if ($timeParts) {
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
     * Get most important event for today
     */
    public function getMostImportantEvent(array $events): ?array
    {
        $today = now()->format('Y-m-d');
        $todayEvents = array_filter($events, function($event) use ($today) {
            $eventDate = $event['date'] instanceof Carbon 
                ? $event['date']->format('Y-m-d')
                : Carbon::parse($event['date'])->format('Y-m-d');
            return $eventDate === $today && $event['impact'] === 'high';
        });
        
        if (empty($todayEvents)) {
            return null;
        }
        
        // Sort by time and return the earliest high-impact event
        usort($todayEvents, function($a, $b) {
            $timeA = $this->parseEventTime($a['date'], $a['time'] ?? '00:00');
            $timeB = $this->parseEventTime($b['date'], $b['time'] ?? '00:00');
            return $timeA <=> $timeB;
        });
        
        return reset($todayEvents);
    }
}

