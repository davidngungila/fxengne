<?php

namespace App\Http\Controllers;

use App\Services\EconomicCalendarService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MarketToolsController extends Controller
{
    protected $calendarService;

    public function __construct(EconomicCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function index()
    {
        return view('market-tools.index');
    }

    public function economicCalendar()
    {
        // Sample events data (in production, fetch from Forex Factory API)
        $events = [
            [
                'id' => 1,
                'date' => Carbon::today(),
                'time' => '9:30pm',
                'country' => 'US',
                'event' => 'CPI m/m',
                'impact' => 'high',
                'previous' => '0.3%',
                'forecast' => '0.3%',
                'actual' => '0.3%',
                'currency' => 'USD',
            ],
            [
                'id' => 2,
                'date' => Carbon::today(),
                'time' => '9:30pm',
                'country' => 'US',
                'event' => 'Core CPI m/m',
                'impact' => 'high',
                'previous' => '0.2%',
                'forecast' => '0.2%',
                'actual' => '0.3%',
                'currency' => 'USD',
            ],
        ];

        // Process events with signals
        $processedEvents = [];
        foreach ($events as $event) {
            $signal = $this->calendarService->generateTradingSignal($event);
            $processedEvents[] = array_merge($event, ['signal_data' => $signal]);
        }

        // Calculate Gold Sensitivity Score
        $goldSensitivityScore = $this->calendarService->calculateGoldSensitivityScore($events);
        
        // Get most important event
        $mostImportantEvent = $this->calendarService->getMostImportantEvent($events);

        return view('market-tools.economic-calendar', [
            'events' => $processedEvents,
            'goldSensitivityScore' => $goldSensitivityScore,
            'mostImportantEvent' => $mostImportantEvent,
        ]);
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


