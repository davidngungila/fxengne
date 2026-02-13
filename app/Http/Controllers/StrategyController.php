<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PerformanceTracker;

class StrategyController extends Controller
{
    protected $performanceTracker;

    public function __construct(PerformanceTracker $performanceTracker)
    {
        $this->performanceTracker = $performanceTracker;
    }

    public function index()
    {
        return view('strategies.index');
    }

    public function create()
    {
        return view('strategies.create');
    }

    public function backtesting()
    {
        return view('strategies.backtesting');
    }

    public function performance(Request $request)
    {
        $strategy = $request->input('strategy', 'all');
        $timeframe = $request->input('timeframe', 'all');
        $instrument = $request->input('instrument', 'all');

        // Get performance data
        $summary = $this->performanceTracker->getSummary();
        
        // Get strategy-specific data
        $strategies = $this->getStrategyPerformance($strategy, $timeframe, $instrument);

        return view('strategies.performance', [
            'summary' => $summary,
            'strategies' => $strategies,
            'filters' => [
                'strategy' => $strategy,
                'timeframe' => $timeframe,
                'instrument' => $instrument,
            ]
        ]);
    }

    /**
     * Get strategy performance data
     */
    protected function getStrategyPerformance($strategy = 'all', $timeframe = 'all', $instrument = 'all')
    {
        // This would query actual database data
        // For now, return sample data structure
        return [
            [
                'name' => 'EMA Crossover',
                'trades' => 42,
                'winRate' => 68.5,
                'profit' => 1250.00,
                'avgProfit' => 29.76,
                'drawdown' => -6.2,
                'sharpe' => 1.75,
                'profitFactor' => 2.15
            ],
            [
                'name' => 'RSI Strategy',
                'trades' => 38,
                'winRate' => 72.3,
                'profit' => 980.50,
                'avgProfit' => 25.80,
                'drawdown' => -5.8,
                'sharpe' => 1.82,
                'profitFactor' => 2.08
            ],
            [
                'name' => 'MACD Crossover',
                'trades' => 23,
                'winRate' => 65.2,
                'profit' => 650.00,
                'avgProfit' => 28.26,
                'drawdown' => -8.5,
                'sharpe' => 1.45,
                'profitFactor' => 1.95
            ],
        ];
    }
}



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PerformanceTracker;

class StrategyController extends Controller
{
    protected $performanceTracker;

    public function __construct(PerformanceTracker $performanceTracker)
    {
        $this->performanceTracker = $performanceTracker;
    }

    public function index()
    {
        return view('strategies.index');
    }

    public function create()
    {
        return view('strategies.create');
    }

    public function backtesting()
    {
        return view('strategies.backtesting');
    }

    public function performance(Request $request)
    {
        $strategy = $request->input('strategy', 'all');
        $timeframe = $request->input('timeframe', 'all');
        $instrument = $request->input('instrument', 'all');

        // Get performance data
        $summary = $this->performanceTracker->getSummary();
        
        // Get strategy-specific data
        $strategies = $this->getStrategyPerformance($strategy, $timeframe, $instrument);

        return view('strategies.performance', [
            'summary' => $summary,
            'strategies' => $strategies,
            'filters' => [
                'strategy' => $strategy,
                'timeframe' => $timeframe,
                'instrument' => $instrument,
            ]
        ]);
    }

    /**
     * Get strategy performance data
     */
    protected function getStrategyPerformance($strategy = 'all', $timeframe = 'all', $instrument = 'all')
    {
        // This would query actual database data
        // For now, return sample data structure
        return [
            [
                'name' => 'EMA Crossover',
                'trades' => 42,
                'winRate' => 68.5,
                'profit' => 1250.00,
                'avgProfit' => 29.76,
                'drawdown' => -6.2,
                'sharpe' => 1.75,
                'profitFactor' => 2.15
            ],
            [
                'name' => 'RSI Strategy',
                'trades' => 38,
                'winRate' => 72.3,
                'profit' => 980.50,
                'avgProfit' => 25.80,
                'drawdown' => -5.8,
                'sharpe' => 1.82,
                'profitFactor' => 2.08
            ],
            [
                'name' => 'MACD Crossover',
                'trades' => 23,
                'winRate' => 65.2,
                'profit' => 650.00,
                'avgProfit' => 28.26,
                'drawdown' => -8.5,
                'sharpe' => 1.45,
                'profitFactor' => 1.95
            ],
        ];
    }
}


