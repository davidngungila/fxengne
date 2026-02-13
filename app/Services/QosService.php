<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class QosService
{
    private $apiKey;
    private $wsUrl;

    public function __construct()
    {
        $this->apiKey = config('services.qos.api_key');
        $this->wsUrl = config('services.qos.ws_url', 'wss://quote.qos.hk/ws');
    }

    /**
     * Get WebSocket URL with authentication
     */
    public function getWebSocketUrl(): string
    {
        return $this->wsUrl . '?key=' . $this->apiKey;
    }

    /**
     * Get API key
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Check if QOS is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Get subscription message for instrument
     */
    public function getSubscribeMessage(string $instrument): array
    {
        // QOS typically uses format like "XAUUSD" or "XAU/USD"
        $qosSymbol = str_replace('_', '', $instrument);
        
        return [
            'action' => 'subscribe',
            'symbol' => $qosSymbol,
            'type' => 'tick', // or 'candle', 'depth', etc.
        ];
    }

    /**
     * Get unsubscribe message
     */
    public function getUnsubscribeMessage(string $instrument): array
    {
        $qosSymbol = str_replace('_', '', $instrument);
        
        return [
            'action' => 'unsubscribe',
            'symbol' => $qosSymbol,
        ];
    }
}

