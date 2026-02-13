<?php

namespace App\Http\Controllers;

use App\Services\MarketSignalService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SignalController extends Controller
{
    protected $signalService;

    public function __construct(MarketSignalService $signalService)
    {
        $this->signalService = $signalService;
    }

    /**
     * Display signals dashboard
     */
    public function index()
    {
        return view('signals.index');
    }

    /**
     * Display active signals
     */
    public function active()
    {
        return view('signals.active');
    }

    /**
     * Display signal history
     */
    public function history()
    {
        return view('signals.history');
    }

    /**
     * Display alert settings
     */
    public function alerts()
    {
        return view('signals.alerts');
    }

    /**
     * Get active signals (API)
     */
    public function getActiveSignals(): JsonResponse
    {
        $signals = $this->signalService->getActiveSignals();

        return response()->json([
            'success' => true,
            'data' => $signals,
            'count' => count($signals)
        ]);
    }

    /**
     * Get signal history (API)
     */
    public function getSignalHistory(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 100);
        $history = $this->signalService->getSignalHistory($limit);

        return response()->json([
            'success' => true,
            'data' => $history,
            'count' => count($history)
        ]);
    }

    /**
     * Generate new signals (API)
     */
    public function generateSignals(Request $request): JsonResponse
    {
        $strategies = $request->input('strategies', []);
        
        $signals = $this->signalService->generateSignals($strategies);

        // Create notifications for new signals
        if (count($signals) > 0) {
            $notificationService = app(\App\Services\NotificationService::class);
            foreach ($signals as $signal) {
                $notificationService->sendSignalNotification([
                    'type' => $signal['type'] ?? 'BUY',
                    'instrument' => $signal['instrument'] ?? 'N/A',
                    'strength' => $signal['strength'] ?? 0,
                    'strategy' => $signal['strategy'] ?? 'Unknown',
                    'price' => $signal['price'] ?? 0,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $signals,
            'count' => count($signals),
            'message' => 'Signals generated successfully'
        ]);
    }

    /**
     * Get signals by instrument (API)
     */
    public function getSignalsByInstrument($instrument): JsonResponse
    {
        $signals = $this->signalService->getSignalsByInstrument($instrument);

        return response()->json([
            'success' => true,
            'data' => array_values($signals),
            'count' => count($signals)
        ]);
    }

    /**
     * Get signals by strategy (API)
     */
    public function getSignalsByStrategy($strategy): JsonResponse
    {
        $signals = $this->signalService->getSignalsByStrategy($strategy);

        return response()->json([
            'success' => true,
            'data' => array_values($signals),
            'count' => count($signals)
        ]);
    }
}

namespace App\Http\Controllers;

use App\Services\MarketSignalService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SignalController extends Controller
{
    protected $signalService;

    public function __construct(MarketSignalService $signalService)
    {
        $this->signalService = $signalService;
    }

    /**
     * Display signals dashboard
     */
    public function index()
    {
        return view('signals.index');
    }

    /**
     * Display active signals
     */
    public function active()
    {
        return view('signals.active');
    }

    /**
     * Display signal history
     */
    public function history()
    {
        return view('signals.history');
    }

    /**
     * Display alert settings
     */
    public function alerts()
    {
        return view('signals.alerts');
    }

    /**
     * Get active signals (API)
     */
    public function getActiveSignals(): JsonResponse
    {
        $signals = $this->signalService->getActiveSignals();

        return response()->json([
            'success' => true,
            'data' => $signals,
            'count' => count($signals)
        ]);
    }

    /**
     * Get signal history (API)
     */
    public function getSignalHistory(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 100);
        $history = $this->signalService->getSignalHistory($limit);

        return response()->json([
            'success' => true,
            'data' => $history,
            'count' => count($history)
        ]);
    }

    /**
     * Generate new signals (API)
     */
    public function generateSignals(Request $request): JsonResponse
    {
        $strategies = $request->input('strategies', []);
        
        $signals = $this->signalService->generateSignals($strategies);

        // Create notifications for new signals
        if (count($signals) > 0) {
            $notificationService = app(\App\Services\NotificationService::class);
            foreach ($signals as $signal) {
                $notificationService->sendSignalNotification([
                    'type' => $signal['type'] ?? 'BUY',
                    'instrument' => $signal['instrument'] ?? 'N/A',
                    'strength' => $signal['strength'] ?? 0,
                    'strategy' => $signal['strategy'] ?? 'Unknown',
                    'price' => $signal['price'] ?? 0,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $signals,
            'count' => count($signals),
            'message' => 'Signals generated successfully'
        ]);
    }

    /**
     * Get signals by instrument (API)
     */
    public function getSignalsByInstrument($instrument): JsonResponse
    {
        $signals = $this->signalService->getSignalsByInstrument($instrument);

        return response()->json([
            'success' => true,
            'data' => array_values($signals),
            'count' => count($signals)
        ]);
    }

    /**
     * Get signals by strategy (API)
     */
    public function getSignalsByStrategy($strategy): JsonResponse
    {
        $signals = $this->signalService->getSignalsByStrategy($strategy);

        return response()->json([
            'success' => true,
            'data' => array_values($signals),
            'count' => count($signals)
        ]);
    }
}
