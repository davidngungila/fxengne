<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SignalDetector
{
    protected $minConfidence = 0.6;
    protected $maxSignalsPerInstrument = 1;
    protected $cooldownPeriod = 300; // 5 minutes in seconds

    /**
     * Validate and filter trading signals
     */
    public function validate(array $signals, array $marketData)
    {
        $validSignals = [];
        $instrumentSignals = [];

        foreach ($signals as $signal) {
            $instrument = $signal['instrument'];

            // Check minimum confidence
            if (($signal['confidence'] ?? 0) < $this->minConfidence) {
                continue;
            }

            // Check cooldown period
            if ($this->isInCooldown($instrument)) {
                continue;
            }

            // Check spread (don't trade if spread is too wide)
            if (isset($marketData[$instrument])) {
                $spread = $marketData[$instrument]['spread'] ?? 0;
                $price = $marketData[$instrument]['bid'] ?? 0;
                
                if ($price > 0 && ($spread / $price) > 0.001) { // Spread > 0.1%
                    continue;
                }
            }

            // Group by instrument
            if (!isset($instrumentSignals[$instrument])) {
                $instrumentSignals[$instrument] = [];
            }

            $instrumentSignals[$instrument][] = $signal;
        }

        // Select best signal per instrument
        foreach ($instrumentSignals as $instrument => $instSignals) {
            // Sort by confidence
            usort($instSignals, function($a, $b) {
                return ($b['confidence'] ?? 0) <=> ($a['confidence'] ?? 0);
            });

            // Take top signal(s)
            $topSignals = array_slice($instSignals, 0, $this->maxSignalsPerInstrument);
            
            foreach ($topSignals as $signal) {
                $validSignals[] = $signal;
                $this->setCooldown($instrument);
            }
        }

        return $validSignals;
    }

    /**
     * Check if instrument is in cooldown period
     */
    protected function isInCooldown($instrument)
    {
        $lastTradeTime = Cache::get("signal_cooldown_{$instrument}");
        
        if (!$lastTradeTime) {
            return false;
        }

        return (time() - $lastTradeTime) < $this->cooldownPeriod;
    }

    /**
     * Set cooldown for instrument
     */
    protected function setCooldown($instrument)
    {
        Cache::put("signal_cooldown_{$instrument}", time(), $this->cooldownPeriod);
    }

    /**
     * Set minimum confidence threshold
     */
    public function setMinConfidence($confidence)
    {
        $this->minConfidence = max(0, min(1, $confidence));
    }

    /**
     * Set cooldown period
     */
    public function setCooldownPeriod($seconds)
    {
        $this->cooldownPeriod = max(60, $seconds);
    }
}

