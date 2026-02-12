<?php

namespace App\Services;

use App\Services\OandaService;
use Illuminate\Support\Facades\Log;

class PositionSizer
{
    protected $oandaService;
    protected $riskPerTrade = 0.02; // 2% of account balance per trade
    protected $maxPositionSize = 10000; // Maximum units
    protected $minPositionSize = 100; // Minimum units

    public function __construct(OandaService $oandaService)
    {
        $this->oandaService = $oandaService;
    }

    /**
     * Calculate position size based on risk management
     */
    public function calculate($instrument, $direction, $confidence, $marketData = null)
    {
        try {
            // Get account balance
            $accountSummary = $this->oandaService->getAccountSummary();
            
            if (!$accountSummary || !isset($accountSummary['account']['balance'])) {
                Log::warning('Could not get account balance for position sizing');
                return 0;
            }

            $accountBalance = floatval($accountSummary['account']['balance']);
            $riskAmount = $accountBalance * $this->riskPerTrade;

            // Adjust risk based on confidence
            $adjustedRisk = $riskAmount * $confidence;

            // Get current price
            if (!$marketData) {
                $prices = $this->oandaService->getPrices([$instrument]);
                if (!$prices || !isset($prices['prices'][0])) {
                    return 0;
                }
                $currentPrice = floatval($prices['prices'][0]['bids'][0]['price']);
            } else {
                $currentPrice = floatval($marketData['bid']);
            }

            // Calculate stop loss distance (in pips or price units)
            $stopLossDistance = $this->calculateStopLossDistance($instrument, $currentPrice, $direction);

            if ($stopLossDistance <= 0) {
                return 0;
            }

            // Calculate position size
            // Position Size = Risk Amount / Stop Loss Distance
            $positionSize = $adjustedRisk / $stopLossDistance;

            // Apply limits
            $positionSize = max($this->minPositionSize, min($this->maxPositionSize, $positionSize));

            // Round to nearest 100 units
            $positionSize = round($positionSize / 100) * 100;

            Log::info('Position size calculated', [
                'instrument' => $instrument,
                'direction' => $direction,
                'confidence' => $confidence,
                'account_balance' => $accountBalance,
                'risk_amount' => $adjustedRisk,
                'stop_loss_distance' => $stopLossDistance,
                'position_size' => $positionSize
            ]);

            return (int)$positionSize;

        } catch (\Exception $e) {
            Log::error('Error calculating position size', [
                'instrument' => $instrument,
                'error' => $e->getMessage()
            ]);

            return 0;
        }
    }

    /**
     * Calculate stop loss distance in price units
     */
    protected function calculateStopLossDistance($instrument, $currentPrice, $direction)
    {
        // Default stop loss: 1% of price
        $defaultStopLossPercent = 0.01;

        // Adjust based on instrument volatility
        $volatilityMultiplier = $this->getVolatilityMultiplier($instrument);
        $stopLossPercent = $defaultStopLossPercent * $volatilityMultiplier;

        return $currentPrice * $stopLossPercent;
    }

    /**
     * Get volatility multiplier for instrument
     */
    protected function getVolatilityMultiplier($instrument)
    {
        // More volatile instruments need wider stop losses
        $volatilityMap = [
            'XAU_USD' => 1.5,  // Gold is more volatile
            'GBP_JPY' => 1.3,  // Volatile pair
            'EUR_USD' => 1.0,  // Standard
            'USD_JPY' => 1.0,
            'GBP_USD' => 1.1,
            'AUD_USD' => 1.0,
            'USD_CAD' => 1.0,
            'NZD_USD' => 1.1,
        ];

        return $volatilityMap[$instrument] ?? 1.0;
    }

    /**
     * Set risk per trade percentage
     */
    public function setRiskPerTrade($percentage)
    {
        $this->riskPerTrade = max(0.01, min(0.1, $percentage / 100));
    }

    /**
     * Set maximum position size
     */
    public function setMaxPositionSize($size)
    {
        $this->maxPositionSize = max(100, $size);
    }
}

