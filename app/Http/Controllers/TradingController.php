<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Services\OandaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradingController extends Controller
{
    protected $oandaService;

    public function __construct(OandaService $oandaService)
    {
        $this->oandaService = $oandaService;
    }

    public function index()
    {
        $userId = Auth::id();
        
        // Get statistics from database
        $openTrades = Trade::open()->forUser($userId)->get();
        $closedTrades = Trade::closed()->forUser($userId)->get();
        
        // Calculate statistics
        $totalTrades = Trade::forUser($userId)->count();
        $openTradesCount = $openTrades->count();
        $winningTrades = $closedTrades->where('realized_pl', '>', 0)->count();
        $totalProfit = $closedTrades->sum('realized_pl');
        $unrealizedPL = $openTrades->sum('unrealized_pl');
        $winRate = $closedTrades->count() > 0 
            ? ($winningTrades / $closedTrades->count()) * 100 
            : 0;
        
        // Get recent trades
        $recentTrades = Trade::forUser($userId)
            ->orderBy('opened_at', 'desc')
            ->limit(10)
            ->get();

        return view('trading.index', [
            'openTradesCount' => $openTradesCount,
            'totalTrades' => $totalTrades,
            'winRate' => round($winRate, 2),
            'totalProfit' => $totalProfit,
            'unrealizedPL' => $unrealizedPL,
            'recentTrades' => $recentTrades,
        ]);
    }

    public function openTrades()
    {
        $userId = Auth::id();
        
        // Get open trades from database
        $openTrades = Trade::open()
            ->forUser($userId)
            ->orderBy('opened_at', 'desc')
            ->get();
        
        // Calculate summary statistics
        $totalOpen = $openTrades->count();
        $unrealizedPL = $openTrades->sum('unrealized_pl');
        $totalExposure = $openTrades->sum(function($trade) {
            return abs($trade->units * ($trade->current_price ?? $trade->entry_price));
        });
        $marginUsed = $openTrades->sum('margin_used');

        return view('trading.open-trades', [
            'openTrades' => $openTrades,
            'totalOpen' => $totalOpen,
            'unrealizedPL' => $unrealizedPL,
            'totalExposure' => $totalExposure,
            'marginUsed' => $marginUsed,
        ]);
    }

    public function history(Request $request)
    {
        $userId = Auth::id();
        
        // Get filters
        $dateRange = $request->get('date_range', 'all');
        $instrument = $request->get('instrument', 'all');
        $result = $request->get('result', 'all');
        
        // Build query
        $query = Trade::closed()->forUser($userId);
        
        // Apply date range filter
        if ($dateRange === 'today') {
            $query->whereDate('closed_at', today());
        } elseif ($dateRange === 'week') {
            $query->where('closed_at', '>=', now()->startOfWeek());
        } elseif ($dateRange === 'month') {
            $query->where('closed_at', '>=', now()->startOfMonth());
        }
        
        // Apply instrument filter
        if ($instrument !== 'all') {
            $query->where('instrument', $instrument);
        }
        
        // Apply result filter
        if ($result === 'win') {
            $query->where('realized_pl', '>', 0);
        } elseif ($result === 'loss') {
            $query->where('realized_pl', '<', 0);
        }
        
        $trades = $query->orderBy('closed_at', 'desc')->paginate(50);
        
        // Calculate statistics
        $totalTrades = $trades->total();
        $winningTrades = Trade::closed()
            ->forUser($userId)
            ->where('realized_pl', '>', 0)
            ->count();
        $losingTrades = Trade::closed()
            ->forUser($userId)
            ->where('realized_pl', '<', 0)
            ->count();
        $totalProfit = Trade::closed()
            ->forUser($userId)
            ->sum('realized_pl');

        return view('trading.history', [
            'trades' => $trades,
            'totalTrades' => $totalTrades,
            'winningTrades' => $winningTrades,
            'losingTrades' => $losingTrades,
            'totalProfit' => $totalProfit,
            'filters' => [
                'date_range' => $dateRange,
                'instrument' => $instrument,
                'result' => $result,
            ],
        ]);
    }

    public function manualEntry()
    {
        return view('trading.manual-entry');
    }
}
