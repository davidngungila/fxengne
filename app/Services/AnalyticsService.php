<?php

namespace App\Services;

use App\Models\Trade;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Get comprehensive performance metrics
     */
    public function getPerformanceMetrics($userId, $timeframe = 'all')
    {
        $trades = $this->getTradesForTimeframe($userId, $timeframe);
        $closedTrades = $trades->where('state', 'CLOSED');
        
        if ($closedTrades->count() === 0) {
            return $this->getEmptyMetrics();
        }

        $winningTrades = $closedTrades->filter(function($trade) {
            return $trade->realized_pl > 0;
        });
        $losingTrades = $closedTrades->filter(function($trade) {
            return $trade->realized_pl < 0;
        });
        
        $totalProfit = $closedTrades->sum('realized_pl');
        $totalWins = $winningTrades->sum('realized_pl');
        $totalLosses = abs($losingTrades->sum('realized_pl'));
        
        $winRate = ($winningTrades->count() / $closedTrades->count()) * 100;
        $profitFactor = $totalLosses > 0 ? $totalWins / $totalLosses : ($totalWins > 0 ? 999 : 0);
        
        // Calculate Sharpe Ratio
        $returns = $closedTrades->pluck('realized_pl')->toArray();
        $sharpeRatio = $this->calculateSharpeRatio($returns);
        
        // Calculate Max Drawdown
        $equityCurve = $this->calculateEquityCurve($closedTrades);
        $maxDrawdown = $this->calculateMaxDrawdown($equityCurve);
        
        // Calculate average win/loss
        $avgWin = $winningTrades->count() > 0 ? $winningTrades->avg('realized_pl') : 0;
        $avgLoss = $losingTrades->count() > 0 ? $losingTrades->avg('realized_pl') : 0;
        
        // Calculate expectancy
        $expectancy = ($winRate / 100 * $avgWin) + ((100 - $winRate) / 100 * $avgLoss);
        
        // Calculate R:R ratio
        $riskRewardRatio = $avgLoss != 0 ? abs($avgWin / $avgLoss) : 0;
        
        return [
            'total_trades' => $closedTrades->count(),
            'winning_trades' => $winningTrades->count(),
            'losing_trades' => $losingTrades->count(),
            'win_rate' => round($winRate, 2),
            'total_profit' => round($totalProfit, 2),
            'total_wins' => round($totalWins, 2),
            'total_losses' => round($totalLosses, 2),
            'profit_factor' => round($profitFactor, 2),
            'sharpe_ratio' => round($sharpeRatio, 2),
            'max_drawdown' => round($maxDrawdown, 2),
            'avg_win' => round($avgWin, 2),
            'avg_loss' => round($avgLoss, 2),
            'expectancy' => round($expectancy, 2),
            'risk_reward_ratio' => round($riskRewardRatio, 2),
            'largest_win' => round($winningTrades->max('realized_pl') ?? 0, 2),
            'largest_loss' => round($losingTrades->min('realized_pl') ?? 0, 2),
            'equity_curve' => $equityCurve,
        ];
    }

    /**
     * Get pair performance metrics
     */
    public function getPairPerformance($userId, $timeframe = 'all')
    {
        $trades = $this->getTradesForTimeframe($userId, $timeframe);
        $closedTrades = $trades->where('state', 'CLOSED');
        
        $pairStats = $closedTrades->groupBy('instrument')->map(function ($pairTrades) {
            $winning = $pairTrades->where('realized_pl', '>', 0);
            $losing = $pairTrades->where('realized_pl', '<', 0);
            
            return [
                'total_trades' => $pairTrades->count(),
                'winning_trades' => $winning->count(),
                'losing_trades' => $losing->count(),
                'win_rate' => $pairTrades->count() > 0 ? ($winning->count() / $pairTrades->count()) * 100 : 0,
                'total_profit' => $pairTrades->sum('realized_pl'),
                'avg_profit' => $pairTrades->avg('realized_pl'),
                'total_wins' => $winning->sum('realized_pl'),
                'total_losses' => abs($losing->sum('realized_pl')),
                'profit_factor' => $losing->sum('realized_pl') != 0 
                    ? abs($winning->sum('realized_pl') / $losing->sum('realized_pl'))
                    : ($winning->sum('realized_pl') > 0 ? 999 : 0),
            ];
        })->sortByDesc('total_profit');
        
        return $pairStats;
    }

    /**
     * Get time-based analysis
     */
    public function getTimeAnalysis($userId, $groupBy = 'day')
    {
        $trades = Trade::where('user_id', $userId)
            ->where('state', 'CLOSED')
            ->whereNotNull('closed_at')
            ->orderBy('closed_at')
            ->get();
        
        $grouped = [];
        
        foreach ($trades as $trade) {
            $date = Carbon::parse($trade->closed_at);
            
            switch ($groupBy) {
                case 'hour':
                    $key = $date->format('Y-m-d H:00');
                    break;
                case 'day':
                    $key = $date->format('Y-m-d');
                    break;
                case 'week':
                    $key = $date->format('Y-W');
                    break;
                case 'month':
                    $key = $date->format('Y-m');
                    break;
                default:
                    $key = $date->format('Y-m-d');
            }
            
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'date' => $key,
                    'trades' => 0,
                    'wins' => 0,
                    'losses' => 0,
                    'profit' => 0,
                ];
            }
            
            $grouped[$key]['trades']++;
            $grouped[$key]['profit'] += $trade->realized_pl;
            
            if ($trade->realized_pl > 0) {
                $grouped[$key]['wins']++;
            } else {
                $grouped[$key]['losses']++;
            }
        }
        
        return collect($grouped)->values();
    }

    /**
     * Get win/loss analysis
     */
    public function getWinLossAnalysis($userId, $timeframe = 'all')
    {
        $trades = $this->getTradesForTimeframe($userId, $timeframe);
        $closedTrades = $trades->where('state', 'CLOSED');
        
        $wins = $closedTrades->where('realized_pl', '>', 0)->pluck('realized_pl')->toArray();
        $losses = $closedTrades->where('realized_pl', '<', 0)->pluck('realized_pl')->toArray();
        
        return [
            'winning_trades' => count($wins),
            'losing_trades' => count($losses),
            'win_rate' => $closedTrades->count() > 0 ? (count($wins) / $closedTrades->count()) * 100 : 0,
            'total_wins' => array_sum($wins),
            'total_losses' => abs(array_sum($losses)),
            'avg_win' => count($wins) > 0 ? array_sum($wins) / count($wins) : 0,
            'avg_loss' => count($losses) > 0 ? abs(array_sum($losses) / count($losses)) : 0,
            'largest_win' => count($wins) > 0 ? max($wins) : 0,
            'largest_loss' => count($losses) > 0 ? min($losses) : 0,
            'win_distribution' => $this->getDistribution($wins),
            'loss_distribution' => $this->getDistribution($losses),
            'consecutive_wins' => $this->getConsecutiveWins($closedTrades),
            'consecutive_losses' => $this->getConsecutiveLosses($closedTrades),
        ];
    }

    /**
     * Get risk metrics
     */
    public function getRiskMetrics($userId)
    {
        $trades = Trade::where('user_id', $userId)
            ->where('state', 'CLOSED')
            ->get();
        
        $openTrades = Trade::where('user_id', $userId)
            ->where('state', 'OPEN')
            ->get();
        
        // Calculate VaR (Value at Risk)
        $returns = $trades->pluck('realized_pl')->toArray();
        $var95 = $this->calculateVaR($returns, 0.95);
        $var99 = $this->calculateVaR($returns, 0.99);
        
        // Calculate current exposure
        $currentExposure = $openTrades->sum('margin_used');
        
        // Calculate max exposure
        $maxExposure = $trades->max('margin_used') ?? 0;
        
        // Calculate average risk per trade
        $avgRisk = $trades->whereNotNull('stop_loss')->count() > 0
            ? $trades->whereNotNull('stop_loss')->avg(function ($trade) {
                return abs($trade->entry_price - $trade->stop_loss) * $trade->units;
            })
            : 0;
        
        // Calculate risk/reward ratios
        $riskRewardRatios = $trades->whereNotNull('stop_loss')
            ->whereNotNull('take_profit')
            ->map(function ($trade) {
                $risk = abs($trade->entry_price - $trade->stop_loss);
                $reward = abs($trade->take_profit - $trade->entry_price);
                return $risk > 0 ? $reward / $risk : 0;
            });
        
        $avgRiskReward = $riskRewardRatios->count() > 0 ? $riskRewardRatios->avg() : 0;
        
        // Calculate position sizing consistency
        $positionSizes = $trades->pluck('units')->toArray();
        $positionSizeStdDev = count($positionSizes) > 1 
            ? $this->calculateStandardDeviation($positionSizes)
            : 0;
        
        return [
            'var_95' => round($var95, 2),
            'var_99' => round($var99, 2),
            'current_exposure' => round($currentExposure, 2),
            'max_exposure' => round($maxExposure, 2),
            'avg_risk_per_trade' => round($avgRisk, 2),
            'avg_risk_reward_ratio' => round($avgRiskReward, 2),
            'position_size_consistency' => round($positionSizeStdDev, 2),
            'open_positions' => $openTrades->count(),
            'total_margin_used' => round($openTrades->sum('margin_used'), 2),
            'unrealized_pl' => round($openTrades->sum('unrealized_pl'), 2),
        ];
    }

    /**
     * Helper: Get trades for timeframe
     */
    protected function getTradesForTimeframe($userId, $timeframe)
    {
        $query = Trade::where('user_id', $userId);
        
        switch ($timeframe) {
            case 'today':
                $query->whereDate('opened_at', Carbon::today());
                break;
            case 'week':
                $query->where('opened_at', '>=', Carbon::now()->startOfWeek());
                break;
            case 'month':
                $query->where('opened_at', '>=', Carbon::now()->startOfMonth());
                break;
            case 'year':
                $query->where('opened_at', '>=', Carbon::now()->startOfYear());
                break;
            case 'all':
            default:
                // No filter
                break;
        }
        
        return $query->get();
    }

    /**
     * Calculate Sharpe Ratio
     */
    protected function calculateSharpeRatio($returns, $riskFreeRate = 0.02)
    {
        if (count($returns) < 2) {
            return 0;
        }
        
        $avgReturn = array_sum($returns) / count($returns);
        $stdDev = $this->calculateStandardDeviation($returns);
        
        if ($stdDev == 0) {
            return 0;
        }
        
        return ($avgReturn - $riskFreeRate) / $stdDev;
    }

    /**
     * Calculate Max Drawdown
     */
    protected function calculateMaxDrawdown($equityCurve)
    {
        if (count($equityCurve) < 2) {
            return 0;
        }
        
        $maxDrawdown = 0;
        $peak = $equityCurve[0];
        
        foreach ($equityCurve as $value) {
            if ($value > $peak) {
                $peak = $value;
            }
            
            $drawdown = (($peak - $value) / $peak) * 100;
            if ($drawdown > $maxDrawdown) {
                $maxDrawdown = $drawdown;
            }
        }
        
        return $maxDrawdown;
    }

    /**
     * Calculate Equity Curve
     */
    protected function calculateEquityCurve($trades)
    {
        $equity = 10000; // Starting equity
        $curve = [$equity];
        
        foreach ($trades->sortBy('closed_at') as $trade) {
            $equity += $trade->realized_pl;
            $curve[] = $equity;
        }
        
        return $curve;
    }

    /**
     * Calculate Value at Risk
     */
    protected function calculateVaR($returns, $confidence = 0.95)
    {
        if (count($returns) === 0) {
            return 0;
        }
        
        sort($returns);
        $index = floor((1 - $confidence) * count($returns));
        
        return abs($returns[$index] ?? 0);
    }

    /**
     * Calculate Standard Deviation
     */
    protected function calculateStandardDeviation($values)
    {
        if (count($values) < 2) {
            return 0;
        }
        
        $mean = array_sum($values) / count($values);
        $variance = 0;
        
        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }
        
        return sqrt($variance / (count($values) - 1));
    }

    /**
     * Get distribution buckets
     */
    protected function getDistribution($values)
    {
        if (count($values) === 0) {
            return [];
        }
        
        $min = min($values);
        $max = max($values);
        $buckets = 10;
        $bucketSize = ($max - $min) / $buckets;
        
        $distribution = array_fill(0, $buckets, 0);
        
        foreach ($values as $value) {
            $bucket = min(floor(($value - $min) / $bucketSize), $buckets - 1);
            $distribution[$bucket]++;
        }
        
        return $distribution;
    }

    /**
     * Get consecutive wins
     */
    protected function getConsecutiveWins($trades)
    {
        $maxConsecutive = 0;
        $current = 0;
        
        foreach ($trades->sortBy('closed_at') as $trade) {
            if ($trade->realized_pl > 0) {
                $current++;
                $maxConsecutive = max($maxConsecutive, $current);
            } else {
                $current = 0;
            }
        }
        
        return $maxConsecutive;
    }

    /**
     * Get consecutive losses
     */
    protected function getConsecutiveLosses($trades)
    {
        $maxConsecutive = 0;
        $current = 0;
        
        foreach ($trades->sortBy('closed_at') as $trade) {
            if ($trade->realized_pl < 0) {
                $current++;
                $maxConsecutive = max($maxConsecutive, $current);
            } else {
                $current = 0;
            }
        }
        
        return $maxConsecutive;
    }

    /**
     * Get empty metrics structure
     */
    protected function getEmptyMetrics()
    {
        return [
            'total_trades' => 0,
            'winning_trades' => 0,
            'losing_trades' => 0,
            'win_rate' => 0,
            'total_profit' => 0,
            'total_wins' => 0,
            'total_losses' => 0,
            'profit_factor' => 0,
            'sharpe_ratio' => 0,
            'max_drawdown' => 0,
            'avg_win' => 0,
            'avg_loss' => 0,
            'expectancy' => 0,
            'risk_reward_ratio' => 0,
            'largest_win' => 0,
            'largest_loss' => 0,
            'equity_curve' => [],
        ];
    }
}

