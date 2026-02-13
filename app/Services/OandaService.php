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
    private $environment;

    public function __construct()
    {
        $this->apiKey = config('services.oanda.api_key');
        $this->accountId = config('services.oanda.account_id');
        $this->environment = config('services.oanda.environment', 'practice');
        
        if ($this->environment === 'live') {
            $this->baseUrl = 'https://api-fxtrade.oanda.com/v3';
        } else {
            $this->baseUrl = 'https://api-fxpractice.oanda.com/v3';
        }
    }

    /**
     * Get HTTP headers for API requests
     */
    private function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Make API request
     */
    private function makeRequest(string $method, string $endpoint, array $data = []): ?array
    {
        if (empty($this->apiKey)) {
            Log::warning('OANDA API key not configured');
            return null;
        }

        try {
            $url = $this->baseUrl . $endpoint;
            
            $response = Http::withHeaders($this->getHeaders())
                ->{strtolower($method)}($url, $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OANDA API error', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OANDA API exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get account information
     */
    public function getAccount(): ?array
    {
        if (empty($this->accountId)) {
            return null;
        }

        return $this->makeRequest('GET', '/accounts/' . $this->accountId);
    }

    /**
     * Get account summary
     */
    public function getAccountSummary(): ?array
    {
        if (empty($this->accountId)) {
            return null;
        }

        return $this->makeRequest('GET', '/accounts/' . $this->accountId . '/summary');
    }

    /**
     * Get prices for instruments
     */
    public function getPrices(array $instruments): ?array
    {
        $instrumentsStr = implode(',', $instruments);
        return $this->makeRequest('GET', '/accounts/' . $this->accountId . '/pricing?instruments=' . urlencode($instrumentsStr));
    }

    /**
     * Get available instruments
     */
    public function getInstruments(): ?array
    {
        return $this->makeRequest('GET', '/accounts/' . $this->accountId . '/instruments');
    }

    /**
     * Get candles for an instrument
     */
    public function getCandles(string $instrument, string $granularity, int $count = 500): ?array
    {
        $instrument = $this->parseInstrument($instrument);
        
        $params = [
            'granularity' => $granularity,
            'count' => $count,
            'price' => 'M' // Mid prices
        ];

        $queryString = http_build_query($params);
        return $this->makeRequest('GET', '/instruments/' . $instrument . '/candles?' . $queryString);
    }

    /**
     * Parse instrument name (convert XAU_USD to XAU/USD)
     */
    public function parseInstrument(string $instrument): string
    {
        return str_replace('_', '/', strtoupper($instrument));
    }

    /**
     * Create an order
     */
    public function createOrder(
        string $instrument,
        float $units,
        string $type = 'MARKET',
        string $side = 'BUY',
        ?float $stopLoss = null,
        ?float $takeProfit = null
    ): ?array {
        $instrument = $this->parseInstrument($instrument);
        
        $orderData = [
            'order' => [
                'type' => $type,
                'instrument' => $instrument,
                'units' => $side === 'BUY' ? abs($units) : -abs($units),
            ]
        ];

        if ($stopLoss !== null) {
            $orderData['order']['stopLossOnFill'] = [
                'price' => (string) $stopLoss
            ];
        }

        if ($takeProfit !== null) {
            $orderData['order']['takeProfitOnFill'] = [
                'price' => (string) $takeProfit
            ];
        }

        return $this->makeRequest('POST', '/accounts/' . $this->accountId . '/orders', $orderData);
    }

    /**
     * Get open trades
     */
    public function getOpenTrades(): ?array
    {
        if (empty($this->accountId)) {
            return null;
        }

        return $this->makeRequest('GET', '/accounts/' . $this->accountId . '/openTrades');
    }

    /**
     * Close a trade
     */
    public function closeTrade(string $tradeId): ?array
    {
        if (empty($this->accountId)) {
            return null;
        }

        return $this->makeRequest('PUT', '/accounts/' . $this->accountId . '/trades/' . $tradeId . '/close');
    }

    /**
     * Get trade details
     */
    public function getTrade(string $tradeId): ?array
    {
        if (empty($this->accountId)) {
            return null;
        }

        return $this->makeRequest('GET', '/accounts/' . $this->accountId . '/trades/' . $tradeId);
    }

    /**
     * Check if OANDA is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->accountId);
    }
}

