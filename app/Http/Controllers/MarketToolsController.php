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
        $oandaEnabled = !empty(config('services.oanda.api_key'));
        return view('market-tools.spread-monitor', [
            'oandaEnabled' => $oandaEnabled
        ]);
    }

    public function tradingSessions()
    {
        return view('market-tools.trading-sessions');
    }

    public function liveMarket()
    {
        $oandaEnabled = !empty(config('services.oanda.api_key'));
        return view('market-tools.live-market', [
            'oandaEnabled' => $oandaEnabled
        ]);
    }
}


