<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('analytics.index');
    }

    public function performance()
    {
        return view('analytics.performance');
    }

    public function pairPerformance()
    {
        return view('analytics.pair-performance');
    }

    public function timeAnalysis()
    {
        return view('analytics.time-analysis');
    }

    public function winLoss()
    {
        return view('analytics.win-loss');
    }

    public function riskMetrics()
    {
        return view('analytics.risk-metrics');
    }
}


