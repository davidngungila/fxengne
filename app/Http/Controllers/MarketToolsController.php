<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarketToolsController extends Controller
{
    public function index()
    {
        return view('market-tools.index');
    }

    public function economicCalendar()
    {
        return view('market-tools.economic-calendar');
    }

    public function spreadMonitor()
    {
        return view('market-tools.spread-monitor');
    }

    public function tradingSessions()
    {
        return view('market-tools.trading-sessions');
    }
}

