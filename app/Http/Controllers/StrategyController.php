<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StrategyController extends Controller
{
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

    public function performance()
    {
        return view('strategies.performance');
    }
}


