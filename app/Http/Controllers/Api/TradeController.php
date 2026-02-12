<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OandaService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TradeController extends Controller
{
    protected $oandaService;
    protected $notificationService;

    public function __construct(OandaService $oandaService, NotificationService $notificationService)
    {
        $this->oandaService = $oandaService;
        $this->notificationService = $notificationService;
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
            // Create notification for trade execution
            $transaction = $result['orderFillTransaction'];
            $this->notificationService->sendTradeNotification([
                'instrument' => $request->instrument,
                'direction' => $request->side,
                'units' => $request->units,
                'entry_price' => $transaction['price'] ?? 0,
                'stop_loss' => $request->stop_loss ?? 'Not set',
                'take_profit' => $request->take_profit ?? 'Not set',
                'strategy' => 'Manual Entry',
            ]);

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
            // Create notification for trade closure
            $transaction = $result['orderFillTransaction'];
            $pl = $transaction['pl'] ?? 0;
            
            // Get trade details first
            $openTrades = $this->oandaService->getOpenTrades();
            $trade = null;
            if ($openTrades && isset($openTrades['trades'])) {
                foreach ($openTrades['trades'] as $t) {
                    if ($t['id'] == $tradeId) {
                        $trade = $t;
                        break;
                    }
                }
            }

            $this->notificationService->sendTradeClosedNotification([
                'instrument' => $trade['instrument'] ?? 'N/A',
                'realized_pl' => $pl,
                'close_time' => now(),
            ]);

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

