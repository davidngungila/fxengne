<!-- Live Market Data Section -->
<div class="card">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Live Market Prices</h3>
            <p class="text-sm text-gray-600 mt-1">Real-time quotes from the market</p>
        </div>
        <div class="flex items-center space-x-2">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span id="marketDataStatus">Live</span>
            </div>
            <button id="refreshMarketData" class="btn btn-sm btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Major Currency Pairs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- XAUUSD (Gold) -->
        <div class="bg-gradient-to-br from-yellow-50 to-amber-50 border-2 border-yellow-200 rounded-lg p-4 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-bold text-gray-900">XAU/USD</span>
                </div>
                <span id="xauusdchange" class="text-xs font-semibold px-2 py-1 rounded">--</span>
            </div>
            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Bid</span>
                    <span id="xauusdbid" class="font-mono font-bold text-lg text-gray-900">--</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Ask</span>
                    <span id="xauusdask" class="font-mono font-bold text-lg text-gray-900">--</span>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-yellow-200">
                    <span class="text-xs text-gray-600">Spread</span>
                    <span id="xauusdspread" class="text-xs font-semibold text-gray-700">--</span>
                </div>
            </div>
        </div>

        <!-- EUR/USD -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg p-4 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                    </svg>
                    <span class="font-bold text-gray-900">EUR/USD</span>
                </div>
                <span id="eurusdchange" class="text-xs font-semibold px-2 py-1 rounded">--</span>
            </div>
            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Bid</span>
                    <span id="eurusdbid" class="font-mono font-bold text-lg text-gray-900">--</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Ask</span>
                    <span id="eurusdask" class="font-mono font-bold text-lg text-gray-900">--</span>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-blue-200">
                    <span class="text-xs text-gray-600">Spread</span>
                    <span id="eurusdspread" class="text-xs font-semibold text-gray-700">--</span>
                </div>
            </div>
        </div>

        <!-- GBP/USD -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 rounded-lg p-4 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                    </svg>
                    <span class="font-bold text-gray-900">GBP/USD</span>
                </div>
                <span id="gbpusdchange" class="text-xs font-semibold px-2 py-1 rounded">--</span>
            </div>
            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Bid</span>
                    <span id="gbpusdbid" class="font-mono font-bold text-lg text-gray-900">--</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Ask</span>
                    <span id="gbpusdask" class="font-mono font-bold text-lg text-gray-900">--</span>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-purple-200">
                    <span class="text-xs text-gray-600">Spread</span>
                    <span id="gbpusdspread" class="text-xs font-semibold text-gray-700">--</span>
                </div>
            </div>
        </div>

        <!-- USD/JPY -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg p-4 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                    </svg>
                    <span class="font-bold text-gray-900">USD/JPY</span>
                </div>
                <span id="usdjpychange" class="text-xs font-semibold px-2 py-1 rounded">--</span>
            </div>
            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Bid</span>
                    <span id="usdjpybid" class="font-mono font-bold text-lg text-gray-900">--</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Ask</span>
                    <span id="usdjpyask" class="font-mono font-bold text-lg text-gray-900">--</span>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-green-200">
                    <span class="text-xs text-gray-600">Spread</span>
                    <span id="usdjpyspread" class="text-xs font-semibold text-gray-700">--</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Pairs Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-semibold text-gray-700">AUD/USD</span>
                <span id="audusdchange" class="text-xs font-semibold px-1 py-0.5 rounded">--</span>
            </div>
            <div class="flex items-center justify-between">
                <span id="audusdbid" class="font-mono text-sm font-bold text-gray-900">--</span>
                <span id="audusdask" class="font-mono text-xs text-gray-600">--</span>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-semibold text-gray-700">USD/CAD</span>
                <span id="usdcadchange" class="text-xs font-semibold px-1 py-0.5 rounded">--</span>
            </div>
            <div class="flex items-center justify-between">
                <span id="usdcadbid" class="font-mono text-sm font-bold text-gray-900">--</span>
                <span id="usdcadask" class="font-mono text-xs text-gray-600">--</span>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-semibold text-gray-700">USD/CHF</span>
                <span id="usdchfchange" class="text-xs font-semibold px-1 py-0.5 rounded">--</span>
            </div>
            <div class="flex items-center justify-between">
                <span id="usdchfbid" class="font-mono text-sm font-bold text-gray-900">--</span>
                <span id="usdchfask" class="font-mono text-xs text-gray-600">--</span>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-semibold text-gray-700">NZD/USD</span>
                <span id="nzdusdchange" class="text-xs font-semibold px-1 py-0.5 rounded">--</span>
            </div>
            <div class="flex items-center justify-between">
                <span id="nzdusdbid" class="font-mono text-sm font-bold text-gray-900">--</span>
                <span id="nzdusdask" class="font-mono text-xs text-gray-600">--</span>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-semibold text-gray-700">EUR/GBP</span>
                <span id="eurgbpchange" class="text-xs font-semibold px-1 py-0.5 rounded">--</span>
            </div>
            <div class="flex items-center justify-between">
                <span id="eurgbpbid" class="font-mono text-sm font-bold text-gray-900">--</span>
                <span id="eurgbpask" class="font-mono text-xs text-gray-600">--</span>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-semibold text-gray-700">EUR/JPY</span>
                <span id="eurjpychange" class="text-xs font-semibold px-1 py-0.5 rounded">--</span>
            </div>
            <div class="flex items-center justify-between">
                <span id="eurjpybid" class="font-mono text-sm font-bold text-gray-900">--</span>
                <span id="eurjpyask" class="font-mono text-xs text-gray-600">--</span>
            </div>
        </div>
    </div>
</div>

<!-- Market Movers & Economic Calendar -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Market Movers -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Market Movers</h3>
            <span class="text-xs text-gray-500">24h Change</span>
        </div>
        <div id="marketMovers" class="space-y-2">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>

    <!-- Economic Calendar Highlights -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Today's Key Events</h3>
            <a href="{{ route('market-tools.economic-calendar') }}" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
        </div>
        <div id="economicEvents" class="space-y-2">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
</div>

