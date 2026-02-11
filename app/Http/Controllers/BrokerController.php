<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrokerController extends Controller
{
    public function index()
    {
        return view('broker.index');
    }

    public function connection()
    {
        return view('broker.connection');
    }

    public function apiSettings()
    {
        return view('broker.api-settings');
    }

    public function executionLogs()
    {
        return view('broker.execution-logs');
    }

    public function vpsStatus()
    {
        return view('broker.vps-status');
    }
}

