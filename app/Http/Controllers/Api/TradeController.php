<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OandaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TradeController extends Controller
{
    protected $oandaService;

    public function __construct(OandaService $oandaService)
    {
        $this->oandaService = $oandaService;
    }

    /**
     * Execute a market order
     */
    public function executeOrder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'instrument' => 'required|string',
            'units' => 'required|numeric',
            'side' => 'required|in:BUY,SELL',
            'stop_loss' => 'nullable|numeric',
            'take_profit' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $instrument = $this->oandaService->parseInstrument($request->instrument);
        $result = $this->oandaService->createOrder(
            $instrument,
            $request->units,
            'MARKET',
            $request->side,
            $request->stop_loss,
            $request->take_profit
        );

        if (isset($result['orderFillTransaction'])) {
            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Order executed successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['error'] ?? 'Order execution failed'
        ], 400);
    }

    /**
     * Get open trades
     */
    public function getOpenTrades(): JsonResponse
    {
        $data = $this->oandaService->getOpenTrades();

        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch open trades'
        ], 500);
    }

    /**
     * Close a trade
     */
    public function closeTrade(Request $request, $tradeId): JsonResponse
    {
        $result = $this->oandaService->closeTrade($tradeId);

        if (isset($result['orderFillTransaction'])) {
            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Trade closed successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['error'] ?? 'Trade close failed'
        ], 400);
    }

    /**
     * Get trade history
     */
    public function getTradeHistory(Request $request): JsonResponse
    {
        $data = $this->oandaService->getTradeHistory(
            $request->input('from'),
            $request->input('to'),
            $request->input('count', 50)
        );

        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch trade history'
        ], 500);
    }
}

