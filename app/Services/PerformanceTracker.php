<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PerformanceTracker
{
    /**
     * Log a trade execution
     */
    public function logTrade(array $tradeData)
    {
        try {
            // Store in cache for quick access
            $tradeId = uniqid('trade_', true);
            Cache::put("trade_{$tradeId}", $tradeData, 86400 * 30); // 30 days

            // Store in database if table exists
            if (DB::getSchemaBuilder()->hasTable('trades')) {
                DB::table('trades')->insert([
                    'trade_id' => $tradeId,
                    'instrument' => $tradeData['instrument'],
                    'direction' => $tradeData['direction'],
                    'units' => $tradeData['units'],
                    'entry_price' => $tradeData['entry_price'],
                    'stop_loss' => $tradeData['stop_loss'],
                    'take_profit' => $tradeData['take_profit'],
                    'strategy' => $tradeData['strategy'],
                    'signal_data' => json_encode($tradeData['signal'] ?? []),
                    'executed_at' => $tradeData['executed_at'] ?? now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Update performance metrics
            $this->updateMetrics();

            Log::info('Trade logged', ['trade_id' => $tradeId]);

        } catch (\Exception $e) {
            Log::error('Error logging trade', [
                'error' => $e->getMessage(),
                'trade_data' => $tradeData
            ]);
        }
    }

    /**
     * Update performance metrics
     */
    public function updateMetrics()
    {
        try {
            $metrics = [
                'total_trades' => $this->getTotalTrades(),
                'winning_trades' => $this->getWinningTrades(),
                'losing_trades' => $this->getLosingTrades(),
                'win_rate' => $this->getWinRate(),
                'total_profit' => $this->getTotalProfit(),
                'average_profit' => $this->getAverageProfit(),
                'largest_win' => $this->getLargestWin(),
                'largest_loss' => $this->getLargestLoss(),
                'profit_factor' => $this->getProfitFactor(),
                'updated_at' => now()
            ];

            Cache::put('performance_metrics', $metrics, 3600);

            return $metrics;

        } catch (\Exception $e) {
            Log::error('Error updating performance metrics', [
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Get performance summary
     */
    public function getSummary()
    {
        return Cache::get('performance_metrics', [
            'total_trades' => 0,
            'winning_trades' => 0,
            'losing_trades' => 0,
            'win_rate' => 0,
            'total_profit' => 0,
            'average_profit' => 0,
            'largest_win' => 0,
            'largest_loss' => 0,
            'profit_factor' => 0
        ]);
    }

    /**
     * Get total number of trades
     */
    protected function getTotalTrades()
    {
        if (DB::getSchemaBuilder()->hasTable('trades')) {
            return DB::table('trades')->count();
        }
        return 0;
    }

    /**
     * Get winning trades count
     */
    protected function getWinningTrades()
    {
        if (DB::getSchemaBuilder()->hasTable('trades')) {
            return DB::table('trades')
                ->whereNotNull('exit_price')
                ->whereColumn('exit_price', '>', 'entry_price')
                ->count();
        }
        return 0;
    }

    /**
     * Get losing trades count
     */
    protected function getLosingTrades()
    {
        if (DB::getSchemaBuilder()->hasTable('trades')) {
            return DB::table('trades')
                ->whereNotNull('exit_price')
                ->whereColumn('exit_price', '<', 'entry_price')
                ->count();
        }
        return 0;
    }

    /**
     * Get win rate percentage
     */
    protected function getWinRate()
    {
        $total = $this->getTotalTrades();
        if ($total === 0) {
            return 0;
        }

        $winning = $this->getWinningTrades();
        return ($winning / $total) * 100;
    }

    /**
     * Get total profit
     */
    protected function getTotalProfit()
    {
        if (DB::getSchemaBuilder()->hasTable('trades')) {
            return DB::table('trades')
                ->whereNotNull('profit')
                ->sum('profit') ?? 0;
        }
        return 0;
    }

    /**
     * Get average profit
     */
    protected function getAverageProfit()
    {
        $total = $this->getTotalTrades();
        if ($total === 0) {
            return 0;
        }

        return $this->getTotalProfit() / $total;
    }

    /**
     * Get largest win
     */
    protected function getLargestWin()
    {
        if (DB::getSchemaBuilder()->hasTable('trades')) {
            return DB::table('trades')
                ->whereNotNull('profit')
                ->where('profit', '>', 0)
                ->max('profit') ?? 0;
        }
        return 0;
    }

    /**
     * Get largest loss
     */
    protected function getLargestLoss()
    {
        if (DB::getSchemaBuilder()->hasTable('trades')) {
            return DB::table('trades')
                ->whereNotNull('profit')
                ->where('profit', '<', 0)
                ->min('profit') ?? 0;
        }
        return 0;
    }

    /**
     * Get profit factor
     */
    protected function getProfitFactor()
    {
        $totalProfit = $this->getTotalProfit();
        $totalLoss = abs($this->getLargestLoss() * $this->getLosingTrades());

        if ($totalLoss === 0) {
            return $totalProfit > 0 ? 999 : 0;
        }

        return $totalProfit / $totalLoss;
    }

    /**
     * Get trades by strategy
     */
    public function getTradesByStrategy($strategy = null)
    {
        if (!DB::getSchemaBuilder()->hasTable('trades')) {
            return [];
        }

        $query = DB::table('trades');

        if ($strategy) {
            $query->where('strategy', $strategy);
        }

        return $query->orderBy('executed_at', 'desc')->get()->toArray();
    }
}



