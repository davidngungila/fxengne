<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trade;
use App\Services\OandaService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            $transaction = $result['orderFillTransaction'];
            $tradeId = $transaction['tradeOpened']['id'] ?? null;
            
            // Store trade in database
            if ($tradeId) {
                $this->syncTradeFromOanda($tradeId);
            }
            
            // Create notification for trade execution
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
     * Get open trades - sync with OANDA and return from database
     */
    public function getOpenTrades(): JsonResponse
    {
        $userId = Auth::id();
        
        // Fetch from OANDA
        $oandaData = $this->oandaService->getOpenTrades();
        
        // Sync OANDA trades with database
        if ($oandaData && isset($oandaData['trades'])) {
            foreach ($oandaData['trades'] as $oandaTrade) {
                $this->syncTradeFromOandaData($oandaTrade, $userId);
            }
        }
        
        // Get from database
        $trades = Trade::open()
            ->forUser($userId)
            ->orderBy('opened_at', 'desc')
            ->get()
            ->map(function($trade) {
                return $this->formatTradeForApi($trade);
            });

        return response()->json([
            'success' => true,
            'data' => [
                'trades' => $trades
            ]
        ]);
    }

    /**
     * Close a trade
     */
    public function closeTrade(Request $request, $tradeId): JsonResponse
    {
        $result = $this->oandaService->closeTrade($tradeId);

        if (isset($result['orderFillTransaction'])) {
            $transaction = $result['orderFillTransaction'];
            $pl = $transaction['pl'] ?? 0;
            
            // Update trade in database
            $trade = Trade::where('oanda_trade_id', $tradeId)->first();
            if ($trade) {
                $trade->update([
                    'state' => 'CLOSED',
                    'exit_price' => $transaction['price'] ?? null,
                    'realized_pl' => $pl,
                    'closed_at' => Carbon::parse($transaction['time'] ?? now()),
                    'close_reason' => 'Manual Close',
                ]);
            }
            
            // Create notification for trade closure
            $this->notificationService->sendTradeClosedNotification([
                'instrument' => $trade->instrument ?? 'N/A',
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
     * Get trade history - from database
     */
    public function getTradeHistory(Request $request): JsonResponse
    {
        $userId = Auth::id();
        
        $query = Trade::closed()->forUser($userId);
        
        // Apply filters
        if ($request->has('from')) {
            $query->where('closed_at', '>=', $request->input('from'));
        }
        if ($request->has('to')) {
            $query->where('closed_at', '<=', $request->input('to'));
        }
        
        $trades = $query->orderBy('closed_at', 'desc')
            ->limit($request->input('count', 50))
            ->get()
            ->map(function($trade) {
                return $this->formatTradeForApi($trade);
            });

        return response()->json([
            'success' => true,
            'data' => [
                'trades' => $trades
            ]
        ]);
    }

    /**
     * Sync a trade from OANDA by trade ID
     */
    protected function syncTradeFromOanda($tradeId, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        // Get trade details from OANDA
        $openTrades = $this->oandaService->getOpenTrades();
        if ($openTrades && isset($openTrades['trades'])) {
            foreach ($openTrades['trades'] as $oandaTrade) {
                if ($oandaTrade['id'] == $tradeId) {
                    return $this->syncTradeFromOandaData($oandaTrade, $userId);
                }
            }
        }
        
        return null;
    }

    /**
     * Sync trade data from OANDA response
     */
    protected function syncTradeFromOandaData(array $oandaTrade, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        $trade = Trade::updateOrCreate(
            ['oanda_trade_id' => $oandaTrade['id']],
            [
                'user_id' => $userId,
                'instrument' => $oandaTrade['instrument'] ?? '',
                'type' => $oandaTrade['currentUnits'] > 0 ? 'BUY' : 'SELL',
                'state' => $oandaTrade['state'] ?? 'OPEN',
                'units' => abs($oandaTrade['currentUnits'] ?? 0),
                'entry_price' => $oandaTrade['price'] ?? 0,
                'current_price' => $oandaTrade['price'] ?? null,
                'stop_loss' => $oandaTrade['stopLossOrder']['price'] ?? null,
                'take_profit' => $oandaTrade['takeProfitOrder']['price'] ?? null,
                'unrealized_pl' => $oandaTrade['unrealizedPL'] ?? 0,
                'margin_used' => $oandaTrade['marginUsed'] ?? 0,
                'opened_at' => Carbon::parse($oandaTrade['openTime'] ?? now()),
                'oanda_data' => $oandaTrade,
            ]
        );
        
        return $trade;
    }

    /**
     * Format trade for API response
     */
    protected function formatTradeForApi(Trade $trade): array
    {
        return [
            'id' => $trade->oanda_trade_id ?? $trade->id,
            'instrument' => $trade->instrument,
            'type' => $trade->type,
            'state' => $trade->state,
            'currentUnits' => $trade->type === 'BUY' ? $trade->units : -$trade->units,
            'units' => $trade->units,
            'openPrice' => $trade->entry_price,
            'currentPrice' => $trade->current_price ?? $trade->entry_price,
            'price' => $trade->current_price ?? $trade->entry_price,
            'exitPrice' => $trade->exit_price,
            'stopLossOrder' => $trade->stop_loss ? ['price' => $trade->stop_loss] : null,
            'takeProfitOrder' => $trade->take_profit ? ['price' => $trade->take_profit] : null,
            'unrealizedPL' => $trade->unrealized_pl,
            'realizedPL' => $trade->realized_pl,
            'openTime' => $trade->opened_at?->toIso8601String(),
            'closeTime' => $trade->closed_at?->toIso8601String(),
            'time' => $trade->opened_at?->toIso8601String(),
        ];
    }
}
