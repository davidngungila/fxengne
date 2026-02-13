<?php

namespace App\Http\Controllers;

use App\Services\TradingBotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AutomatedTradingController extends Controller
{
    protected $tradingBot;

    public function __construct(TradingBotService $tradingBot)
    {
        $this->tradingBot = $tradingBot;
    }

    /**
     * Get bot status
     */
    public function status(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->tradingBot->getStatus()
        ]);
    }

    /**
     * Start the trading bot
     */
    public function start(Request $request): JsonResponse
    {
        $strategies = $request->input('strategies', []);
        $config = $request->input('config', []);

        $result = $this->tradingBot->start($strategies, $config);

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Trading bot started successfully',
                'data' => $this->tradingBot->getStatus()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Trading bot is already running'
        ], 400);
    }

    /**
     * Stop the trading bot
     */
    public function stop(): JsonResponse
    {
        $result = $this->tradingBot->stop();

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Trading bot stopped successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Trading bot is not running'
        ], 400);
    }

    /**
     * Execute one trading cycle manually
     */
    public function executeCycle(): JsonResponse
    {
        $this->tradingBot->executeCycle();

        return response()->json([
            'success' => true,
            'message' => 'Trading cycle executed'
        ]);
    }
}



