<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class OandaService
{
    private $apiKey;
    private $accountId;
    private $baseUrl;
    private $environment; // 'practice' or 'live'

    public function __construct()
    {
        $this->apiKey = config('services.oanda.api_key');
        $this->accountId = config('services.oanda.account_id');
        $this->environment = config('services.oanda.environment', 'practice');
        
        // OANDA API endpoints
        if ($this->environment === 'live') {
            $this->baseUrl = 'https://api-fxtrade.oanda.com';
        } else {
            $this->baseUrl = 'https://api-fxpractice.oanda.com';
        }
    }

    /**
     * Get account information
     */
    public function getAccount()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/v3/accounts/{$this->accountId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OANDA API Error - Get Account', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OANDA API Exception - Get Account', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get live prices for instruments
     */
    public function getPrices(array $instruments)
    {
        try {
            $instrumentsString = implode(',', $instruments);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/v3/accounts/{$this->accountId}/pricing", [
                'instruments' => $instrumentsString
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OANDA API Error - Get Prices', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OANDA API Exception - Get Prices', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get all available instruments
     */
    public function getInstruments()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/v3/accounts/{$this->accountId}/instruments");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OANDA API Error - Get Instruments', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OANDA API Exception - Get Instruments', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get candlestick data for an instrument
     */
    public function getCandles($instrument, $granularity = 'M1', $count = 500)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/v3/instruments/{$instrument}/candles", [
                'granularity' => $granularity,
                'count' => $count
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OANDA API Error - Get Candles', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OANDA API Exception - Get Candles', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Execute a market order
     */
    public function createOrder($instrument, $units, $type = 'MARKET', $side = 'BUY', $stopLoss = null, $takeProfit = null)
    {
        try {
            $orderData = [
                'order' => [
                    'type' => $type,
                    'instrument' => $instrument,
                    'units' => $side === 'BUY' ? abs($units) : -abs($units),
                ]
            ];

            if ($stopLoss) {
                $orderData['order']['stopLossOnFill'] = [
                    'price' => (string)$stopLoss
                ];
            }

            if ($takeProfit) {
                $orderData['order']['takeProfitOnFill'] = [
                    'price' => (string)$takeProfit
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/v3/accounts/{$this->accountId}/orders", $orderData);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OANDA API Error - Create Order', [
                'status' => $response->status(),
                'body' => $response->body(),
                'order' => $orderData
            ]);

            return [
                'success' => false,
                'error' => $response->json()['errorMessage'] ?? 'Order execution failed'
            ];
        } catch (\Exception $e) {
            Log::error('OANDA API Exception - Create Order', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get open trades
     */
    public function getOpenTrades()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/v3/accounts/{$this->accountId}/openTrades");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OANDA API Error - Get Open Trades', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OANDA API Exception - Get Open Trades', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get trade history
     */
    public function getTradeHistory($from = null, $to = null, $count = 50)
    {
        try {
            $params = ['count' => $count];
            
            if ($from) {
                $params['from'] = $from;
            }
            if ($to) {
                $params['to'] = $to;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/v3/accounts/{$this->accountId}/trades", $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OANDA API Error - Get Trade History', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OANDA API Exception - Get Trade History', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Close a trade
     */
    public function closeTrade($tradeId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->put("{$this->baseUrl}/v3/accounts/{$this->accountId}/trades/{$tradeId}/close");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OANDA API Error - Close Trade', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => $response->json()['errorMessage'] ?? 'Trade close failed'
            ];
        } catch (\Exception $e) {
            Log::error('OANDA API Exception - Close Trade', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get account summary
     */
    public function getAccountSummary()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/v3/accounts/{$this->accountId}/summary");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OANDA API Error - Get Account Summary', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OANDA API Exception - Get Account Summary', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Format instrument name (e.g., EUR_USD -> EURUSD)
     */
    public function formatInstrument($instrument)
    {
        return str_replace('_', '', $instrument);
    }

    /**
     * Parse instrument name (e.g., EURUSD -> EUR_USD)
     */
    public function parseInstrument($instrument)
    {
        if (strlen($instrument) === 6) {
            return substr($instrument, 0, 3) . '_' . substr($instrument, 3, 3);
        }
        return $instrument;
    }
}



