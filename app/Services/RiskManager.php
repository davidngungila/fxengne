<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class RiskManager
{
    protected $defaultStopLossPercent = 0.01; // 1%
    protected $defaultTakeProfitPercent = 0.02; // 2% (2:1 risk/reward)
    protected $maxRiskPerTrade = 0.02; // 2% of account

    /**
     * Calculate stop loss and take profit levels
     */
    public function calculateRisk($instrument, $direction, $marketData = null, $signal = [])
    {
        try {
            if (!$marketData) {
                return $this->getDefaultRisk($instrument, $direction);
            }

            $entryPrice = floatval($marketData['bid'] ?? $marketData['ask'] ?? 0);
            
            if ($direction === 'BUY') {
                $entryPrice = floatval($marketData['ask'] ?? $entryPrice);
            } else {
                $entryPrice = floatval($marketData['bid'] ?? $entryPrice);
            }

            if ($entryPrice <= 0) {
                return $this->getDefaultRisk($instrument, $direction);
            }

            // Calculate based on volatility
            $volatility = $this->getInstrumentVolatility($instrument);
            $stopLossDistance = $entryPrice * $this->defaultStopLossPercent * $volatility;
            $takeProfitDistance = $entryPrice * $this->defaultTakeProfitPercent * $volatility;

            // Adjust based on signal confidence
            $confidence = $signal['confidence'] ?? 0.5;
            $takeProfitDistance *= (1 + $confidence); // Higher confidence = wider TP

            if ($direction === 'BUY') {
                $stopLoss = $entryPrice - $stopLossDistance;
                $takeProfit = $entryPrice + $takeProfitDistance;
            } else {
                $stopLoss = $entryPrice + $stopLossDistance;
                $takeProfit = $entryPrice - $takeProfitDistance;
            }

            // Ensure stop loss and take profit are valid
            $stopLoss = max(0.00001, $stopLoss);
            $takeProfit = max(0.00001, $takeProfit);

            return [
                'stop_loss' => round($stopLoss, 5),
                'take_profit' => round($takeProfit, 5),
                'risk_reward_ratio' => $takeProfitDistance / $stopLossDistance
            ];

        } catch (\Exception $e) {
            Log::error('Error calculating risk', [
                'instrument' => $instrument,
                'error' => $e->getMessage()
            ]);

            return $this->getDefaultRisk($instrument, $direction);
        }
    }

    /**
     * Get default risk parameters
     */
    protected function getDefaultRisk($instrument, $direction)
    {
        // This would use a default price if market data is unavailable
        // For now, return percentage-based values
        return [
            'stop_loss' => null, // Will be calculated at execution
            'take_profit' => null,
            'risk_reward_ratio' => 2.0
        ];
    }

    /**
     * Get instrument volatility multiplier
     */
    protected function getInstrumentVolatility($instrument)
    {
        $volatilityMap = [
            'XAU_USD' => 1.5,
            'GBP_JPY' => 1.3,
            'EUR_USD' => 1.0,
            'USD_JPY' => 1.0,
            'GBP_USD' => 1.1,
            'AUD_USD' => 1.0,
            'USD_CAD' => 1.0,
            'NZD_USD' => 1.1,
        ];

        return $volatilityMap[$instrument] ?? 1.0;
    }

    /**
     * Validate risk parameters before trade execution
     */
    public function validateRisk($stopLoss, $takeProfit, $entryPrice, $direction)
    {
        $isValid = true;
        $errors = [];

        if ($direction === 'BUY') {
            if ($stopLoss >= $entryPrice) {
                $isValid = false;
                $errors[] = 'Stop loss must be below entry price for BUY orders';
            }
            if ($takeProfit <= $entryPrice) {
                $isValid = false;
                $errors[] = 'Take profit must be above entry price for BUY orders';
            }
        } else {
            if ($stopLoss <= $entryPrice) {
                $isValid = false;
                $errors[] = 'Stop loss must be above entry price for SELL orders';
            }
            if ($takeProfit >= $entryPrice) {
                $isValid = false;
                $errors[] = 'Take profit must be below entry price for SELL orders';
            }
        }

        return [
            'valid' => $isValid,
            'errors' => $errors
        ];
    }
}



