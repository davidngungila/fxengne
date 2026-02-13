<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BacktestingController extends Controller
{
    public function index()
    {
        return view('backtesting.index');
    }

    public function run()
    {
        return view('backtesting.run');
    }

    public function historicalData()
    {
        return view('backtesting.historical-data');
    }

    public function reports()
    {
        return view('backtesting.reports');
    }

    public function compare()
    {
        return view('backtesting.compare');
    }
}





