<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OandaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MarketDataController extends Controller
{
    protected $oandaService;

    public function __construct(OandaService $oandaService)
    {
        $this->oandaService = $oandaService;
    }

    /**
     * Get live market prices
     */
    public function getPrices(Request $request): JsonResponse
    {
        $instruments = $request->input('instruments', []);
        
        if (empty($instruments)) {
            // Default major pairs
            $instruments = ['EUR_USD', 'GBP_USD', 'USD_JPY', 'USD_CHF', 'AUD_USD', 'USD_CAD', 'NZD_USD'];
        }

        $data = $this->oandaService->getPrices($instruments);

        if ($data && isset($data['prices'])) {
            return response()->json([
                'success' => true,
                'data' => $data['prices'],
                'timestamp' => now()->toIso8601String()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch market data'
        ], 500);
    }

    /**
     * Get available instruments
     */
    public function getInstruments(): JsonResponse
    {
        $data = $this->oandaService->getInstruments();

        if ($data && isset($data['instruments'])) {
            return response()->json([
                'success' => true,
                'data' => $data['instruments']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch instruments'
        ], 500);
    }

    /**
     * Get account summary
     */
    public function getAccountSummary(): JsonResponse
    {
        $data = $this->oandaService->getAccountSummary();

        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch account summary'
        ], 500);
    }

    /**
     * Get candlestick data for XAUUSD
     */
    public function getXAUUSDCandles(Request $request): JsonResponse
    {
        $granularity = $request->input('granularity', 'M1'); // M1, M5, M15, H1, etc.
        $count = $request->input('count', 500);

        $data = $this->oandaService->getCandles('XAU_USD', $granularity, $count);

        if ($data && isset($data['candles'])) {
            return response()->json([
                'success' => true,
                'data' => $data['candles'],
                'instrument' => 'XAU_USD',
                'granularity' => $granularity
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch XAUUSD candles'
        ], 500);
    }
}

