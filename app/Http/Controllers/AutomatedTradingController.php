<?php

namespace App\Http\Controllers;

use App\Services\TradingBotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AutomatedTradingController extends Controller
{
    protected $tradingBotService;

    public function __construct(TradingBotService $tradingBotService)
    {
        $this->tradingBotService = $tradingBotService;
    }

    /**
     * Get bot status
     */
    public function status(): JsonResponse
    {
        $status = $this->tradingBotService->getStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Start the trading bot
     */
    public function start(Request $request): JsonResponse
    {
        $strategy = $request->input('strategy', 'default');
        $riskPercent = $request->input('risk_percent', 1.0);
        
        $result = $this->tradingBotService->start($strategy, $riskPercent);

        return response()->json([
            'success' => $result['success'] ?? false,
            'message' => $result['message'] ?? 'Bot started',
            'data' => $result['data'] ?? []
        ]);
    }

    /**
     * Stop the trading bot
     */
    public function stop(): JsonResponse
    {
        $result = $this->tradingBotService->stop();

        return response()->json([
            'success' => $result['success'] ?? false,
            'message' => $result['message'] ?? 'Bot stopped',
            'data' => $result['data'] ?? []
        ]);
    }

    /**
     * Execute a single trading cycle
     */
    public function executeCycle(Request $request): JsonResponse
    {
        $strategy = $request->input('strategy', 'default');
        
        $result = $this->tradingBotService->executeCycle($strategy);

        return response()->json([
            'success' => $result['success'] ?? false,
            'message' => $result['message'] ?? 'Cycle executed',
            'data' => $result['data'] ?? []
        ]);
    }
}

