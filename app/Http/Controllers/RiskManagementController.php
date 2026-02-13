<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RiskManagementController extends Controller
{
    public function index()
    {
        return view('risk.index');
    }

    public function settings()
    {
        return view('risk.settings');
    }

    public function dailyLimits()
    {
        return view('risk.daily-limits');
    }

    public function drawdown()
    {
        return view('risk.drawdown');
    }

    public function exposure()
    {
        return view('risk.exposure');
    }

    public function lotCalculator()
    {
        return view('risk.lot-calculator');
    }
}




