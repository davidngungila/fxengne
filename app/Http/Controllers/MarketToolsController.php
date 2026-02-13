<?php

namespace App\Http\Controllers;

use App\Services\EconomicCalendarService;
use App\Services\LiveMarketSignalService;
use App\Models\MLModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MarketToolsController extends Controller
{
    protected $calendarService;
    protected $signalService;

    public function __construct(EconomicCalendarService $calendarService, LiveMarketSignalService $signalService)
    {
        $this->calendarService = $calendarService;
        $this->signalService = $signalService;
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
        
        // Get today's critical event (CPI)
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

        $mostImportantEvent = $this->calendarService->getMostImportantEvent($events);
        $eventSignal = $mostImportantEvent ? $this->calendarService->generateTradingSignal($mostImportantEvent) : null;
        
        // Get active TFT model
        $tftModel = MLModel::where('user_id', Auth::id())
            ->where('type', 'price_direction')
            ->where('architecture', 'TFT')
            ->where('is_active', true)
            ->first();

        // Current price (will be updated via API)
        $currentPrice = 4944.80; // Placeholder, will be fetched live
        
        // Get Gold Alpha Signal
        $goldAlphaSignal = $this->signalService->getGoldAlphaSignal(
            $currentPrice,
            $tftModel ? $tftModel->toArray() : null,
            $mostImportantEvent
        );

        // Calculate entry zones (example values)
        $preNewsPrice = 4975.00;
        $postNewsLow = 4928.50;
        $postNewsHigh = 4975.00;
        $entryZones = $this->signalService->calculateEntryZones($preNewsPrice, $postNewsLow, $postNewsHigh);

        // Calculate position sizing
        $accountEquity = 50000; // Placeholder, should come from user account
        $riskPercent = 1.2;
        $atr = 18.40;
        $stopLoss = 4975.00;
        $positionSize = $this->signalService->calculatePositionSize(
            $accountEquity,
            $riskPercent,
            $entryZones['50.0'],
            $stopLoss,
            $atr
        );

        return view('market-tools.live-market', [
            'oandaEnabled' => $oandaEnabled,
            'goldAlphaSignal' => $goldAlphaSignal,
            'cpiEvent' => $mostImportantEvent,
            'eventSignal' => $eventSignal,
            'currentPrice' => $currentPrice,
            'entryZones' => $entryZones,
            'positionSize' => $positionSize,
            'atr' => $atr,
            'preNewsPrice' => $preNewsPrice,
            'postNewsLow' => $postNewsLow,
            'postNewsHigh' => $postNewsHigh,
        ]);
    }
}


