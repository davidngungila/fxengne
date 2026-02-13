// Live Market Data Functions
let previousPrices = {};

// Load live market prices
async function loadLiveMarketData() {
    const API_BASE_URL = window.API_BASE_URL || '/api';
    
    try {
        const instruments = [
            'XAU_USD', 'EUR_USD', 'GBP_USD', 'USD_JPY',
            'AUD_USD', 'USD_CAD', 'USD_CHF', 'NZD_USD',
            'EUR_GBP', 'EUR_JPY'
        ];

        const response = await fetch(`${API_BASE_URL}/market/prices?instruments=${instruments.join(',')}`);
        const result = await response.json();

        if (result.success && result.data && result.data.length > 0) {
            result.data.forEach(price => {
                updateMarketPrice(price);
            });
            
            // Update market movers
            updateMarketMovers(result.data);
            
            // Update status
            const statusEl = document.getElementById('marketDataStatus');
            if (statusEl) {
                statusEl.textContent = 'Live';
                const indicator = statusEl.previousElementSibling;
                if (indicator) {
                    indicator.classList.remove('bg-red-500');
                    indicator.classList.add('bg-green-500');
                }
            }
        }
    } catch (error) {
        console.error('Error loading market data:', error);
        const statusEl = document.getElementById('marketDataStatus');
        if (statusEl) {
            statusEl.textContent = 'Offline';
            const indicator = statusEl.previousElementSibling;
            if (indicator) {
                indicator.classList.remove('bg-green-500');
                indicator.classList.add('bg-red-500');
            }
        }
    }
}

// Update market price display
function updateMarketPrice(priceData) {
    if (!priceData || !priceData.instrument) return;

    const instrument = priceData.instrument.replace('_', '').toLowerCase();
    const bid = parseFloat(priceData.bids?.[0]?.price || 0);
    const ask = parseFloat(priceData.asks?.[0]?.price || 0);
    const mid = (bid + ask) / 2;
    const spread = ask - bid;

    if (bid === 0 || ask === 0) return;

    // Format based on instrument type
    const isGold = instrument.includes('xau');
    const decimals = isGold ? 2 : 5;
    const spreadMultiplier = isGold ? 100 : 10000;

    // Update bid/ask
    const bidEl = document.getElementById(`${instrument}bid`);
    const askEl = document.getElementById(`${instrument}ask`);
    const spreadEl = document.getElementById(`${instrument}spread`);
    const changeEl = document.getElementById(`${instrument}change`);

    if (bidEl) {
        const prevBid = parseFloat(bidEl.textContent.replace(/[^0-9.]/g, '')) || bid;
        bidEl.textContent = bid.toFixed(decimals);
        
        // Flash animation on change
        if (prevBid !== bid && prevBid !== 0) {
            bidEl.classList.add('animate-pulse');
            setTimeout(() => bidEl.classList.remove('animate-pulse'), 500);
        }
    }

    if (askEl) {
        const prevAsk = parseFloat(askEl.textContent.replace(/[^0-9.]/g, '')) || ask;
        askEl.textContent = ask.toFixed(decimals);
        
        if (prevAsk !== ask && prevAsk !== 0) {
            askEl.classList.add('animate-pulse');
            setTimeout(() => askEl.classList.remove('animate-pulse'), 500);
        }
    }

    if (spreadEl) {
        const spreadPips = (spread * spreadMultiplier).toFixed(1);
        spreadEl.textContent = isGold ? `$${spreadPips}` : `${spreadPips} pips`;
    }

    // Calculate and display change
    if (changeEl && previousPrices[instrument]) {
        const prevMid = previousPrices[instrument];
        const change = mid - prevMid;
        const changePercent = (change / prevMid) * 100;
        
        const isPositive = change >= 0;
        changeEl.textContent = `${isPositive ? '+' : ''}${changePercent.toFixed(2)}%`;
        changeEl.className = `text-xs font-semibold px-2 py-1 rounded ${isPositive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
    } else if (changeEl) {
        changeEl.textContent = '--';
        changeEl.className = 'text-xs font-semibold px-2 py-1 rounded bg-gray-100 text-gray-600';
    }

    // Store current price
    previousPrices[instrument] = mid;
}

// Update market movers
function updateMarketMovers(prices) {
    const container = document.getElementById('marketMovers');
    if (!container) return;

    const movers = prices
        .map(price => {
            const instrument = price.instrument.replace('_', '/');
            const bid = parseFloat(price.bids?.[0]?.price || 0);
            const ask = parseFloat(price.asks?.[0]?.price || 0);
            const mid = (bid + ask) / 2;
            const instKey = price.instrument.replace('_', '').toLowerCase();
            const prevMid = previousPrices[instKey] || mid;
            const change = mid - prevMid;
            const changePercent = prevMid > 0 ? (change / prevMid) * 100 : 0;
            
            return {
                instrument,
                change,
                changePercent,
                mid
            };
        })
        .filter(m => Math.abs(m.changePercent) > 0.01)
        .sort((a, b) => Math.abs(b.changePercent) - Math.abs(a.changePercent))
        .slice(0, 5);

    if (movers.length === 0) {
        container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No significant movements</p>';
        return;
    }

    container.innerHTML = movers.map(mover => {
        const isPositive = mover.changePercent >= 0;
        const colorClass = isPositive ? 'text-green-600' : 'text-red-600';
        const bgColorClass = isPositive ? 'bg-green-500' : 'bg-red-500';
        const sign = isPositive ? '+' : '';
        
        return `
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 ${bgColorClass} rounded-full"></div>
                    <div>
                        <p class="font-medium text-sm text-gray-900">${mover.instrument}</p>
                        <p class="text-xs text-gray-600">${sign}${mover.changePercent.toFixed(2)}%</p>
                    </div>
                </div>
                <span class="font-bold ${colorClass}">${sign}${mover.change.toFixed(5)}</span>
            </div>
        `;
    }).join('');
}

// Make functions available globally
window.loadLiveMarketData = loadLiveMarketData;
window.updateMarketPrice = updateMarketPrice;
window.updateMarketMovers = updateMarketMovers;

