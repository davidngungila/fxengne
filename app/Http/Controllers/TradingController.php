<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TradingController extends Controller
{
    public function index()
    {
        return view('trading.index');
    }

    public function openTrades()
    {
        return view('trading.open-trades');
    }

    public function history()
    {
        return view('trading.history');
    }

    public function manualEntry()
    {
        return view('trading.manual-entry');
    }
}


