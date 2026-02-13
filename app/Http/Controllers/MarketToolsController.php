<?php

namespace App\Http\Controllers;

use App\Services\EconomicCalendarService;
use App\Services\LiveMarketSignalService;
use App\Services\OandaService;
use App\Services\QosService;
use App\Models\MLModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MarketToolsController extends Controller
{
    protected $calendarService;
    protected $signalService;
    protected $oandaService;
    protected $qosService;

    public function __construct(EconomicCalendarService $calendarService, LiveMarketSignalService $signalService, OandaService $oandaService, QosService $qosService)
    {
        $this->calendarService = $calendarService;
        $this->signalService = $signalService;
        $this->oandaService = $oandaService;
        $this->qosService = $qosService;
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
        $qosEnabled = $this->qosService->isConfigured();
        
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

        // Fetch real-time XAUUSD price from OANDA
        $currentPrice = 4944.80; // Default fallback
        if ($oandaEnabled) {
            try {
                $priceData = $this->oandaService->getPrices(['XAU_USD']);
                if ($priceData && isset($priceData['prices']) && count($priceData['prices']) > 0) {
                    $xauusdPrice = $priceData['prices'][0];
                    if (isset($xauusdPrice['bids'][0]['price'])) {
                        $currentPrice = (float) $xauusdPrice['bids'][0]['price'];
                    }
                }
            } catch (\Exception $e) {
                // Use fallback price if API fails
            }
        }
        
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

        // Get account equity from OANDA
        $accountEquity = 50000; // Default fallback
        if ($oandaEnabled) {
            try {
                $accountData = $this->oandaService->getAccount();
                if ($accountData && isset($accountData['account']['balance'])) {
                    $accountEquity = (float) $accountData['account']['balance'];
                }
            } catch (\Exception $e) {
                // Use fallback equity if API fails
            }
        }
        
        // Calculate ATR from recent candles (or use default)
        $atr = 18.40; // Default fallback
        if ($oandaEnabled) {
            try {
                $candles = $this->oandaService->getCandles('XAU_USD', 'M15', 14);
                if ($candles && isset($candles['candles'])) {
                    // Calculate ATR from last 14 candles
                    $highs = [];
                    $lows = [];
                    foreach ($candles['candles'] as $candle) {
                        if ($candle['complete']) {
                            $highs[] = (float) $candle['mid']['h'];
                            $lows[] = (float) $candle['mid']['l'];
                        }
                    }
                    if (count($highs) >= 14) {
                        $trueRanges = [];
                        for ($i = 1; $i < count($highs); $i++) {
                            $tr = max(
                                $highs[$i] - $lows[$i],
                                abs($highs[$i] - $highs[$i-1]),
                                abs($lows[$i] - $lows[$i-1])
                            );
                            $trueRanges[] = $tr;
                        }
                        $atr = array_sum($trueRanges) / count($trueRanges);
                        $atr = round($atr * 100, 2); // Convert to pips
                    }
                }
            } catch (\Exception $e) {
                // Use fallback ATR if calculation fails
            }
        }
        
        $riskPercent = 1.2;
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
            'qosEnabled' => $qosEnabled,
            'qosWsUrl' => $qosEnabled ? $this->qosService->getWebSocketUrl() : null,
            'qosApiKey' => $qosEnabled ? $this->qosService->getApiKey() : null,
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
