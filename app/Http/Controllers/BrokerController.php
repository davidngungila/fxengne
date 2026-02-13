<?php

namespace App\Http\Controllers;

use App\Services\OandaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BrokerController extends Controller
{
    protected $oandaService;

    public function __construct(OandaService $oandaService)
    {
        $this->oandaService = $oandaService;
    }

    /**
     * Display broker dashboard
     */
    public function index()
    {
        return view('broker.index');
    }

    /**
     * Display broker connection page
     */
    public function connection()
    {
        $oandaEnabled = !empty(config('services.oanda.api_key'));
        $connectionStatus = $this->getConnectionStatus();

        return view('broker.connection', [
            'oandaEnabled' => $oandaEnabled,
            'connectionStatus' => $connectionStatus
        ]);
    }

    /**
     * Display API settings page
     */
    public function apiSettings()
    {
        $oandaEnabled = !empty(config('services.oanda.api_key'));
        
        return view('broker.api-settings', [
            'oandaEnabled' => $oandaEnabled,
            'environment' => config('services.oanda.environment', 'practice')
        ]);
    }

    /**
     * Display execution logs page
     */
    public function executionLogs()
    {
        return view('broker.execution-logs');
    }

    /**
     * Display VPS status page
     */
    public function vpsStatus()
    {
        return view('broker.vps-status');
    }

    /**
     * Test broker connection (API)
     */
    public function testConnection(): JsonResponse
    {
        try {
            $account = $this->oandaService->getAccount();
            
            if ($account && isset($account['account'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connection successful',
                    'data' => [
                        'account_id' => $account['account']['id'] ?? null,
                        'account_name' => $account['account']['tags'][0] ?? 'Default',
                        'currency' => $account['account']['currency'] ?? 'USD',
                        'balance' => $account['account']['balance'] ?? 0,
                        'margin_available' => $account['account']['marginAvailable'] ?? 0,
                        'margin_used' => $account['account']['marginUsed'] ?? 0,
                        'open_trade_count' => $account['account']['openTradeCount'] ?? 0,
                        'open_position_count' => $account['account']['openPositionCount'] ?? 0,
                        'pending_order_count' => $account['account']['pendingOrderCount'] ?? 0
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to broker'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get connection status
     */
    protected function getConnectionStatus()
    {
        try {
            $account = $this->oandaService->getAccount();
            return [
                'connected' => $account !== null,
                'last_check' => now()->toDateTimeString()
            ];
        } catch (\Exception $e) {
            return [
                'connected' => false,
                'error' => $e->getMessage(),
                'last_check' => now()->toDateTimeString()
            ];
        }
    }

    /**
     * Get execution logs (API)
     */
    public function getExecutionLogs(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 50);
        
        // Get from cache or database
        $logs = \Illuminate\Support\Facades\Cache::get('execution_logs', []);
        
        // Sort by timestamp descending
        usort($logs, function($a, $b) {
            return strtotime($b['timestamp'] ?? 0) <=> strtotime($a['timestamp'] ?? 0);
        });

        return response()->json([
            'success' => true,
            'data' => array_slice($logs, 0, $limit),
            'count' => count($logs)
        ]);
    }

    /**
     * Get VPS status (API)
     */
    public function getVpsStatus(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'status' => 'online',
                'uptime' => $this->calculateUptime(),
                'cpu_usage' => rand(10, 40),
                'memory_usage' => rand(30, 60),
                'disk_usage' => rand(20, 50),
                'network_latency' => rand(5, 25),
                'last_update' => now()->toDateTimeString()
            ]
        ]);
    }

    /**
     * Calculate server uptime (simulated)
     */
    protected function calculateUptime()
    {
        // In production, this would get actual server uptime
        $days = rand(1, 30);
        $hours = rand(0, 23);
        $minutes = rand(0, 59);
        
        return "{$days}d {$hours}h {$minutes}m";
    }
}

namespace App\Http\Controllers;

use App\Services\OandaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BrokerController extends Controller
{
    protected $oandaService;

    public function __construct(OandaService $oandaService)
    {
        $this->oandaService = $oandaService;
    }

    /**
     * Display broker dashboard
     */
    public function index()
    {
        return view('broker.index');
    }

    /**
     * Display broker connection page
     */
    public function connection()
    {
        $oandaEnabled = !empty(config('services.oanda.api_key'));
        $connectionStatus = $this->getConnectionStatus();

        return view('broker.connection', [
            'oandaEnabled' => $oandaEnabled,
            'connectionStatus' => $connectionStatus
        ]);
    }

    /**
     * Display API settings page
     */
    public function apiSettings()
    {
        $oandaEnabled = !empty(config('services.oanda.api_key'));
        
        return view('broker.api-settings', [
            'oandaEnabled' => $oandaEnabled,
            'environment' => config('services.oanda.environment', 'practice')
        ]);
    }

    /**
     * Display execution logs page
     */
    public function executionLogs()
    {
        return view('broker.execution-logs');
    }

    /**
     * Display VPS status page
     */
    public function vpsStatus()
    {
        return view('broker.vps-status');
    }

    /**
     * Test broker connection (API)
     */
    public function testConnection(): JsonResponse
    {
        try {
            $account = $this->oandaService->getAccount();
            
            if ($account && isset($account['account'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connection successful',
                    'data' => [
                        'account_id' => $account['account']['id'] ?? null,
                        'account_name' => $account['account']['tags'][0] ?? 'Default',
                        'currency' => $account['account']['currency'] ?? 'USD',
                        'balance' => $account['account']['balance'] ?? 0,
                        'margin_available' => $account['account']['marginAvailable'] ?? 0,
                        'margin_used' => $account['account']['marginUsed'] ?? 0,
                        'open_trade_count' => $account['account']['openTradeCount'] ?? 0,
                        'open_position_count' => $account['account']['openPositionCount'] ?? 0,
                        'pending_order_count' => $account['account']['pendingOrderCount'] ?? 0
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to broker'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get connection status
     */
    protected function getConnectionStatus()
    {
        try {
            $account = $this->oandaService->getAccount();
            return [
                'connected' => $account !== null,
                'last_check' => now()->toDateTimeString()
            ];
        } catch (\Exception $e) {
            return [
                'connected' => false,
                'error' => $e->getMessage(),
                'last_check' => now()->toDateTimeString()
            ];
        }
    }

    /**
     * Get execution logs (API)
     */
    public function getExecutionLogs(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 50);
        
        // Get from cache or database
        $logs = \Illuminate\Support\Facades\Cache::get('execution_logs', []);
        
        // Sort by timestamp descending
        usort($logs, function($a, $b) {
            return strtotime($b['timestamp'] ?? 0) <=> strtotime($a['timestamp'] ?? 0);
        });

        return response()->json([
            'success' => true,
            'data' => array_slice($logs, 0, $limit),
            'count' => count($logs)
        ]);
    }

    /**
     * Get VPS status (API)
     */
    public function getVpsStatus(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'status' => 'online',
                'uptime' => $this->calculateUptime(),
                'cpu_usage' => rand(10, 40),
                'memory_usage' => rand(30, 60),
                'disk_usage' => rand(20, 50),
                'network_latency' => rand(5, 25),
                'last_update' => now()->toDateTimeString()
            ]
        ]);
    }

    /**
     * Calculate server uptime (simulated)
     */
    protected function calculateUptime()
    {
        // In production, this would get actual server uptime
        $days = rand(1, 30);
        $hours = rand(0, 23);
        $minutes = rand(0, 59);
        
        return "{$days}d {$hours}h {$minutes}m";
    }
}
