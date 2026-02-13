<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Services\OandaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

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

    /**
     * Export trade history to CSV
     */
    public function exportHistory(Request $request)
    {
        $userId = Auth::id();
        
        // Get filters (same as history method)
        $dateRange = $request->get('date_range', 'all');
        $instrument = $request->get('instrument', 'all');
        $result = $request->get('result', 'all');
        
        // Build query (same as history method)
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
        
        // Get all trades (no pagination for export)
        $trades = $query->orderBy('closed_at', 'desc')->get();
        
        // Generate CSV
        $filename = 'trade_history_' . Carbon::now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($trades) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers
            fputcsv($file, [
                'Date',
                'Instrument',
                'Type',
                'Units',
                'Entry Price',
                'Exit Price',
                'Stop Loss',
                'Take Profit',
                'Duration (minutes)',
                'Realized P/L',
                'P/L %',
                'Strategy',
                'Close Reason'
            ]);
            
            // CSV Data
            foreach ($trades as $trade) {
                $duration = $trade->duration ?? 0;
                $plPercent = $trade->pl_percentage ?? 0;
                
                fputcsv($file, [
                    $trade->closed_at->format('Y-m-d H:i:s'),
                    $trade->formatted_instrument,
                    $trade->type,
                    number_format($trade->units, 0),
                    number_format($trade->entry_price, 5),
                    number_format($trade->exit_price ?? $trade->entry_price, 5),
                    $trade->stop_loss ? number_format($trade->stop_loss, 5) : '',
                    $trade->take_profit ? number_format($trade->take_profit, 5) : '',
                    $duration,
                    number_format($trade->realized_pl, 2),
                    number_format($plPercent, 2),
                    $trade->strategy ?? 'Manual',
                    $trade->close_reason ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export open trades to CSV
     */
    public function exportOpenTrades()
    {
        $userId = Auth::id();
        
        // Get all open trades
        $trades = Trade::open()
            ->forUser($userId)
            ->orderBy('opened_at', 'desc')
            ->get();
        
        // Generate CSV
        $filename = 'open_trades_' . Carbon::now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($trades) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers
            fputcsv($file, [
                'Opened Date',
                'Instrument',
                'Type',
                'Units',
                'Entry Price',
                'Current Price',
                'Stop Loss',
                'Take Profit',
                'Unrealized P/L',
                'P/L %',
                'Margin Used',
                'Strategy',
                'Duration (minutes)'
            ]);
            
            // CSV Data
            foreach ($trades as $trade) {
                $duration = $trade->duration ?? 0;
                $plPercent = $trade->pl_percentage ?? 0;
                
                fputcsv($file, [
                    $trade->opened_at->format('Y-m-d H:i:s'),
                    $trade->formatted_instrument,
                    $trade->type,
                    number_format($trade->units, 0),
                    number_format($trade->entry_price, 5),
                    number_format($trade->current_price ?? $trade->entry_price, 5),
                    $trade->stop_loss ? number_format($trade->stop_loss, 5) : '',
                    $trade->take_profit ? number_format($trade->take_profit, 5) : '',
                    number_format($trade->unrealized_pl, 2),
                    number_format($plPercent, 2),
                    number_format($trade->margin_used, 2),
                    $trade->strategy ?? 'Manual',
                    $duration
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Services\OandaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

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

    /**
     * Export trade history to CSV
     */
    public function exportHistory(Request $request)
    {
        $userId = Auth::id();
        
        // Get filters (same as history method)
        $dateRange = $request->get('date_range', 'all');
        $instrument = $request->get('instrument', 'all');
        $result = $request->get('result', 'all');
        
        // Build query (same as history method)
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
        
        // Get all trades (no pagination for export)
        $trades = $query->orderBy('closed_at', 'desc')->get();
        
        // Generate CSV
        $filename = 'trade_history_' . Carbon::now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($trades) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers
            fputcsv($file, [
                'Date',
                'Instrument',
                'Type',
                'Units',
                'Entry Price',
                'Exit Price',
                'Stop Loss',
                'Take Profit',
                'Duration (minutes)',
                'Realized P/L',
                'P/L %',
                'Strategy',
                'Close Reason'
            ]);
            
            // CSV Data
            foreach ($trades as $trade) {
                $duration = $trade->duration ?? 0;
                $plPercent = $trade->pl_percentage ?? 0;
                
                fputcsv($file, [
                    $trade->closed_at->format('Y-m-d H:i:s'),
                    $trade->formatted_instrument,
                    $trade->type,
                    number_format($trade->units, 0),
                    number_format($trade->entry_price, 5),
                    number_format($trade->exit_price ?? $trade->entry_price, 5),
                    $trade->stop_loss ? number_format($trade->stop_loss, 5) : '',
                    $trade->take_profit ? number_format($trade->take_profit, 5) : '',
                    $duration,
                    number_format($trade->realized_pl, 2),
                    number_format($plPercent, 2),
                    $trade->strategy ?? 'Manual',
                    $trade->close_reason ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export open trades to CSV
     */
    public function exportOpenTrades()
    {
        $userId = Auth::id();
        
        // Get all open trades
        $trades = Trade::open()
            ->forUser($userId)
            ->orderBy('opened_at', 'desc')
            ->get();
        
        // Generate CSV
        $filename = 'open_trades_' . Carbon::now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($trades) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers
            fputcsv($file, [
                'Opened Date',
                'Instrument',
                'Type',
                'Units',
                'Entry Price',
                'Current Price',
                'Stop Loss',
                'Take Profit',
                'Unrealized P/L',
                'P/L %',
                'Margin Used',
                'Strategy',
                'Duration (minutes)'
            ]);
            
            // CSV Data
            foreach ($trades as $trade) {
                $duration = $trade->duration ?? 0;
                $plPercent = $trade->pl_percentage ?? 0;
                
                fputcsv($file, [
                    $trade->opened_at->format('Y-m-d H:i:s'),
                    $trade->formatted_instrument,
                    $trade->type,
                    number_format($trade->units, 0),
                    number_format($trade->entry_price, 5),
                    number_format($trade->current_price ?? $trade->entry_price, 5),
                    $trade->stop_loss ? number_format($trade->stop_loss, 5) : '',
                    $trade->take_profit ? number_format($trade->take_profit, 5) : '',
                    number_format($trade->unrealized_pl, 2),
                    number_format($plPercent, 2),
                    number_format($trade->margin_used, 2),
                    $trade->strategy ?? 'Manual',
                    $duration
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}
