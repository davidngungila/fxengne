<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class StrategyEngine
{
    protected $strategies = [];

    public function __construct()
    {
        $this->registerStrategies();
    }

    /**
     * Register all available strategies
     */
    protected function registerStrategies()
    {
        $this->strategies = [
            'ema_crossover' => [$this, 'emaCrossover'],
            'rsi_oversold_overbought' => [$this, 'rsiStrategy'],
            'macd_crossover' => [$this, 'macdCrossover'],
            'support_resistance' => [$this, 'supportResistance'],
            'bollinger_bands' => [$this, 'bollingerBands'],
            'moving_average_convergence' => [$this, 'movingAverageConvergence']
        ];
    }

    /**
     * Analyze market data with multiple strategies
     */
    public function analyze(array $marketData, array $strategyNames = [])
    {
        $signals = [];
        $strategiesToRun = empty($strategyNames) ? array_keys($this->strategies) : $strategyNames;

        foreach ($strategiesToRun as $strategyName) {
            if (!isset($this->strategies[$strategyName])) {
                Log::warning("Strategy not found: {$strategyName}");
                continue;
            }

            try {
                $strategySignals = call_user_func($this->strategies[$strategyName], $marketData);
                
                foreach ($strategySignals as $signal) {
                    $signal['strategy'] = $strategyName;
                    $signals[] = $signal;
                }
            } catch (\Exception $e) {
                Log::error("Error running strategy {$strategyName}", [
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $signals;
    }

    /**
     * EMA Crossover Strategy
     * Buy when fast EMA crosses above slow EMA
     * Sell when fast EMA crosses below slow EMA
     */
    protected function emaCrossover(array $marketData)
    {
        $signals = [];

        foreach ($marketData as $instrument => $data) {
            // Get historical data for EMA calculation
            $candles = $this->getCandles($instrument, 200);
            
            if (count($candles) < 50) {
                continue;
            }

            $closes = array_column($candles, 'close');
            $ema9 = $this->calculateEMA($closes, 9);
            $ema21 = $this->calculateEMA($closes, 21);

            if (count($ema9) < 2 || count($ema21) < 2) {
                continue;
            }

            $currentEma9 = end($ema9);
            $previousEma9 = $ema9[count($ema9) - 2];
            $currentEma21 = end($ema21);
            $previousEma21 = $ema21[count($ema21) - 2];

            // Bullish crossover
            if ($previousEma9 <= $previousEma21 && $currentEma9 > $currentEma21) {
                $signals[] = [
                    'instrument' => $instrument,
                    'direction' => 'BUY',
                    'confidence' => 0.7,
                    'entry_price' => $data['bid'],
                    'reason' => 'EMA 9 crossed above EMA 21'
                ];
            }

            // Bearish crossover
            if ($previousEma9 >= $previousEma21 && $currentEma9 < $currentEma21) {
                $signals[] = [
                    'instrument' => $instrument,
                    'direction' => 'SELL',
                    'confidence' => 0.7,
                    'entry_price' => $data['bid'],
                    'reason' => 'EMA 9 crossed below EMA 21'
                ];
            }
        }

        return $signals;
    }

    /**
     * RSI Strategy
     * Buy when RSI < 30 (oversold)
     * Sell when RSI > 70 (overbought)
     */
    protected function rsiStrategy(array $marketData)
    {
        $signals = [];

        foreach ($marketData as $instrument => $data) {
            $candles = $this->getCandles($instrument, 50);
            
            if (count($candles) < 14) {
                continue;
            }

            $closes = array_column($candles, 'close');
            $rsi = $this->calculateRSI($closes, 14);

            if (empty($rsi)) {
                continue;
            }

            $currentRsi = end($rsi);

            if ($currentRsi < 30) {
                $signals[] = [
                    'instrument' => $instrument,
                    'direction' => 'BUY',
                    'confidence' => min(0.8, (30 - $currentRsi) / 30),
                    'entry_price' => $data['bid'],
                    'reason' => "RSI oversold ({$currentRsi})"
                ];
            } elseif ($currentRsi > 70) {
                $signals[] = [
                    'instrument' => $instrument,
                    'direction' => 'SELL',
                    'confidence' => min(0.8, ($currentRsi - 70) / 30),
                    'entry_price' => $data['bid'],
                    'reason' => "RSI overbought ({$currentRsi})"
                ];
            }
        }

        return $signals;
    }

    /**
     * MACD Crossover Strategy
     */
    protected function macdCrossover(array $marketData)
    {
        $signals = [];

        foreach ($marketData as $instrument => $data) {
            $candles = $this->getCandles($instrument, 100);
            
            if (count($candles) < 26) {
                continue;
            }

            $closes = array_column($candles, 'close');
            $macd = $this->calculateMACD($closes);

            if (empty($macd['signal'])) {
                continue;
            }

            $currentMacd = end($macd['macd']);
            $currentSignal = end($macd['signal']);
            $previousMacd = $macd['macd'][count($macd['macd']) - 2] ?? null;
            $previousSignal = $macd['signal'][count($macd['signal']) - 2] ?? null;

            if ($previousMacd === null || $previousSignal === null) {
                continue;
            }

            // Bullish crossover
            if ($previousMacd <= $previousSignal && $currentMacd > $currentSignal) {
                $signals[] = [
                    'instrument' => $instrument,
                    'direction' => 'BUY',
                    'confidence' => 0.75,
                    'entry_price' => $data['bid'],
                    'reason' => 'MACD bullish crossover'
                ];
            }

            // Bearish crossover
            if ($previousMacd >= $previousSignal && $currentMacd < $currentSignal) {
                $signals[] = [
                    'instrument' => $instrument,
                    'direction' => 'SELL',
                    'confidence' => 0.75,
                    'entry_price' => $data['bid'],
                    'reason' => 'MACD bearish crossover'
                ];
            }
        }

        return $signals;
    }

    /**
     * Support/Resistance Strategy
     */
    protected function supportResistance(array $marketData)
    {
        $signals = [];

        foreach ($marketData as $instrument => $data) {
            $candles = $this->getCandles($instrument, 100);
            
            if (count($candles) < 20) {
                continue;
            }

            $highs = array_column($candles, 'high');
            $lows = array_column($candles, 'low');
            $closes = array_column($candles, 'close');

            $resistance = max($highs);
            $support = min($lows);
            $currentPrice = $data['bid'];

            $distanceToResistance = abs($currentPrice - $resistance) / $currentPrice;
            $distanceToSupport = abs($currentPrice - $support) / $currentPrice;

            // Near support - potential buy
            if ($distanceToSupport < 0.002 && $currentPrice > $support) {
                $signals[] = [
                    'instrument' => $instrument,
                    'direction' => 'BUY',
                    'confidence' => 0.65,
                    'entry_price' => $data['bid'],
                    'reason' => 'Price near support level'
                ];
            }

            // Near resistance - potential sell
            if ($distanceToResistance < 0.002 && $currentPrice < $resistance) {
                $signals[] = [
                    'instrument' => $instrument,
                    'direction' => 'SELL',
                    'confidence' => 0.65,
                    'entry_price' => $data['bid'],
                    'reason' => 'Price near resistance level'
                ];
            }
        }

        return $signals;
    }

    /**
     * Bollinger Bands Strategy
     */
    protected function bollingerBands(array $marketData)
    {
        $signals = [];

        foreach ($marketData as $instrument => $data) {
            $candles = $this->getCandles($instrument, 50);
            
            if (count($candles) < 20) {
                continue;
            }

            $closes = array_column($candles, 'close');
            $bb = $this->calculateBollingerBands($closes, 20, 2);

            if (empty($bb)) {
                continue;
            }

            $currentPrice = $data['bid'];
            $upperBand = end($bb['upper']);
            $lowerBand = end($bb['lower']);
            $middleBand = end($bb['middle']);

            // Price touches lower band - buy signal
            if ($currentPrice <= $lowerBand) {
                $signals[] = [
                    'instrument' => $instrument,
                    'direction' => 'BUY',
                    'confidence' => 0.7,
                    'entry_price' => $data['bid'],
                    'reason' => 'Price at lower Bollinger Band'
                ];
            }

            // Price touches upper band - sell signal
            if ($currentPrice >= $upperBand) {
                $signals[] = [
                    'instrument' => $instrument,
                    'direction' => 'SELL',
                    'confidence' => 0.7,
                    'entry_price' => $data['bid'],
                    'reason' => 'Price at upper Bollinger Band'
                ];
            }
        }

        return $signals;
    }

    /**
     * Moving Average Convergence Strategy
     */
    protected function movingAverageConvergence(array $marketData)
    {
        $signals = [];

        foreach ($marketData as $instrument => $data) {
            $candles = $this->getCandles($instrument, 200);
            
            if (count($candles) < 50) {
                continue;
            }

            $closes = array_column($candles, 'close');
            $ema50 = $this->calculateEMA($closes, 50);
            $ema200 = $this->calculateEMA($closes, 200);

            if (count($ema50) < 2 || count($ema200) < 2) {
                continue;
            }

            $currentEma50 = end($ema50);
            $currentEma200 = end($ema200);
            $previousEma50 = $ema50[count($ema50) - 2];
            $previousEma200 = $ema200[count($ema200) - 2];

            // Golden cross - bullish
            if ($previousEma50 <= $previousEma200 && $currentEma50 > $currentEma200) {
                $signals[] = [
                    'instrument' => $instrument,
                    'direction' => 'BUY',
                    'confidence' => 0.8,
                    'entry_price' => $data['bid'],
                    'reason' => 'Golden Cross (EMA 50 > EMA 200)'
                ];
            }

            // Death cross - bearish
            if ($previousEma50 >= $previousEma200 && $currentEma50 < $currentEma200) {
                $signals[] = [
                    'instrument' => $instrument,
                    'direction' => 'SELL',
                    'confidence' => 0.8,
                    'entry_price' => $data['bid'],
                    'reason' => 'Death Cross (EMA 50 < EMA 200)'
                ];
            }
        }

        return $signals;
    }

    /**
     * Get candles for an instrument
     */
    protected function getCandles($instrument, $count = 100)
    {
        // This would fetch from OANDA API or cache
        // For now, return empty array - will be implemented with actual data fetching
        return [];
    }

    /**
     * Calculate EMA
     */
    protected function calculateEMA(array $prices, $period)
    {
        if (count($prices) < $period) {
            return [];
        }

        $multiplier = 2 / ($period + 1);
        $ema = [];
        $sma = array_sum(array_slice($prices, 0, $period)) / $period;
        $ema[] = $sma;

        for ($i = $period; $i < count($prices); $i++) {
            $ema[] = ($prices[$i] - end($ema)) * $multiplier + end($ema);
        }

        return $ema;
    }

    /**
     * Calculate RSI
     */
    protected function calculateRSI(array $prices, $period = 14)
    {
        if (count($prices) < $period + 1) {
            return [];
        }

        $rsi = [];
        $gains = [];
        $losses = [];

        for ($i = 1; $i < count($prices); $i++) {
            $change = $prices[$i] - $prices[$i - 1];
            $gains[] = $change > 0 ? $change : 0;
            $losses[] = $change < 0 ? abs($change) : 0;
        }

        for ($i = $period; $i < count($gains); $i++) {
            $avgGain = array_sum(array_slice($gains, $i - $period, $period)) / $period;
            $avgLoss = array_sum(array_slice($losses, $i - $period, $period)) / $period;

            if ($avgLoss == 0) {
                $rsi[] = 100;
            } else {
                $rs = $avgGain / $avgLoss;
                $rsi[] = 100 - (100 / (1 + $rs));
            }
        }

        return $rsi;
    }

    /**
     * Calculate MACD
     */
    protected function calculateMACD(array $prices, $fastPeriod = 12, $slowPeriod = 26, $signalPeriod = 9)
    {
        $emaFast = $this->calculateEMA($prices, $fastPeriod);
        $emaSlow = $this->calculateEMA($prices, $slowPeriod);

        if (count($emaFast) < $slowPeriod || count($emaSlow) < $slowPeriod) {
            return ['macd' => [], 'signal' => []];
        }

        $macd = [];
        $offset = count($emaFast) - count($emaSlow);

        for ($i = 0; $i < count($emaSlow); $i++) {
            $macd[] = $emaFast[$i + $offset] - $emaSlow[$i];
        }

        $signal = $this->calculateEMA($macd, $signalPeriod);

        return [
            'macd' => $macd,
            'signal' => $signal
        ];
    }

    /**
     * Calculate Bollinger Bands
     */
    protected function calculateBollingerBands(array $prices, $period = 20, $stdDev = 2)
    {
        if (count($prices) < $period) {
            return [];
        }

        $middle = [];
        $upper = [];
        $lower = [];

        for ($i = $period - 1; $i < count($prices); $i++) {
            $slice = array_slice($prices, $i - $period + 1, $period);
            $sma = array_sum($slice) / $period;
            $variance = 0;

            foreach ($slice as $price) {
                $variance += pow($price - $sma, 2);
            }

            $std = sqrt($variance / $period);

            $middle[] = $sma;
            $upper[] = $sma + ($stdDev * $std);
            $lower[] = $sma - ($stdDev * $std);
        }

        return [
            'middle' => $middle,
            'upper' => $upper,
            'lower' => $lower
        ];
    }
}




