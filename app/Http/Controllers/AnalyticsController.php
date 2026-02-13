<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        $userId = Auth::id();
        $metrics = $this->analyticsService->getPerformanceMetrics($userId, 'all');
        
        return view('analytics.index', [
            'metrics' => $metrics,
        ]);
    }

    public function performance(Request $request)
    {
        $userId = Auth::id();
        $timeframe = $request->get('timeframe', 'all');
        
        $metrics = $this->analyticsService->getPerformanceMetrics($userId, $timeframe);
        $timeAnalysis = $this->analyticsService->getTimeAnalysis($userId, 'day');
        
        return view('analytics.performance', [
            'metrics' => $metrics,
            'timeAnalysis' => $timeAnalysis,
            'timeframe' => $timeframe,
        ]);
    }

    public function pairPerformance(Request $request)
    {
        $userId = Auth::id();
        $timeframe = $request->get('timeframe', 'all');
        
        $pairStats = $this->analyticsService->getPairPerformance($userId, $timeframe);
        
        return view('analytics.pair-performance', [
            'pairStats' => $pairStats,
            'timeframe' => $timeframe,
        ]);
    }

    public function timeAnalysis(Request $request)
    {
        $userId = Auth::id();
        $groupBy = $request->get('group_by', 'day');
        
        $hourly = $this->analyticsService->getTimeAnalysis($userId, 'hour');
        $daily = $this->analyticsService->getTimeAnalysis($userId, 'day');
        $weekly = $this->analyticsService->getTimeAnalysis($userId, 'week');
        $monthly = $this->analyticsService->getTimeAnalysis($userId, 'month');
        
        return view('analytics.time-analysis', [
            'hourly' => $hourly,
            'daily' => $daily,
            'weekly' => $weekly,
            'monthly' => $monthly,
            'groupBy' => $groupBy,
        ]);
    }

    public function winLoss(Request $request)
    {
        $userId = Auth::id();
        $timeframe = $request->get('timeframe', 'all');
        
        $analysis = $this->analyticsService->getWinLossAnalysis($userId, $timeframe);
        
        return view('analytics.win-loss', [
            'analysis' => $analysis,
            'timeframe' => $timeframe,
        ]);
    }

    public function riskMetrics()
    {
        $userId = Auth::id();
        
        $riskMetrics = $this->analyticsService->getRiskMetrics($userId);
        $performanceMetrics = $this->analyticsService->getPerformanceMetrics($userId, 'all');
        
        return view('analytics.risk-metrics', [
            'riskMetrics' => $riskMetrics,
            'performanceMetrics' => $performanceMetrics,
        ]);
    }
}






