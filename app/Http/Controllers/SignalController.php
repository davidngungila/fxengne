<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignalController extends Controller
{
    public function index()
    {
        return view('signals.index');
    }

    public function active()
    {
        return view('signals.active');
    }

    public function history()
    {
        return view('signals.history');
    }

    public function alerts()
    {
        return view('signals.alerts');
    }
}

