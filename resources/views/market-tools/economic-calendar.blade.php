@extends('layouts.app')

@section('title', 'Economic Calendar - FXEngine')
@section('page-title', 'Economic Calendar')

@section('content')
<div class="space-y-6">
    <!-- Header with Gold Sensitivity Score -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Economic Calendar</h2>
            <p class="text-sm text-gray-600 mt-1">Track important economic events and their impact on XAUUSD</p>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Gold Sensitivity Score (Alpha Score) -->
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl p-4 shadow-lg">
                <div class="text-center">
                    <p class="text-xs text-yellow-900 font-medium mb-1">Gold Sensitivity Score</p>
                    <p class="text-4xl font-bold text-white">{{ number_format($goldSensitivityScore, 1) }}</p>
                    <p class="text-xs text-yellow-900 mt-1">/ 10.0</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <select id="filterCountry" class="form-input text-sm">
                    <option value="all">All Countries</option>
                    <option value="US" selected>United States</option>
                    <option value="EU">European Union</option>
                    <option value="GB">United Kingdom</option>
                    <option value="JP">Japan</option>
                </select>
                <select id="filterImpact" class="form-input text-sm">
                    <option value="all">All Impact</option>
                    <option value="high" selected>High Impact</option>
                    <option value="medium">Medium Impact</option>
                    <option value="low">Low Impact</option>
                </select>
                <button id="refreshCalendar" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Most Important Event Today -->
    @if($mostImportantEvent)
    <div class="card border-l-4 border-red-500 bg-red-50">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded">CRITICAL</span>
                    <h3 class="text-lg font-bold text-gray-900">Today's Most Important Event</h3>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Event</p>
                        <p class="font-semibold text-gray-900">{{ $mostImportantEvent['event'] }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Time</p>
                        <p class="font-semibold text-gray-900">{{ $mostImportantEvent['time'] }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Forecast</p>
                        <p class="font-semibold text-gray-900">{{ $mostImportantEvent['forecast'] }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Previous</p>
                        <p class="font-semibold text-gray-900">{{ $mostImportantEvent['previous'] }}</p>
                    </div>
                </div>
                @if(isset($mostImportantEvent['signal_data']))
                <div class="mt-4 p-3 bg-white rounded-lg border border-red-200">
                    <div class="flex items-center space-x-4">
                        <div>
                            <p class="text-xs text-gray-600">Projected Signal</p>
                            <p class="text-xl font-bold {{ $mostImportantEvent['signal_data']['signal'] === 'BUY' ? 'text-green-600' : ($mostImportantEvent['signal_data']['signal'] === 'SELL' ? 'text-red-600' : 'text-gray-600') }}">
                                {{ $mostImportantEvent['signal_data']['signal'] }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Confidence</p>
                            <p class="text-xl font-bold text-gray-900">{{ number_format($mostImportantEvent['signal_data']['confidence'] * 100, 0) }}%</p>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-600">Reason</p>
                            <p class="text-sm font-medium text-gray-900">{{ $mostImportantEvent['signal_data']['reason'] }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- 8-State Decision Model Reference -->
    <div class="card bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            8-State Decision Model (XAUUSD Trading Framework)
        </h3>
        <div class="overflow-x-auto">
            <table class="table text-sm">
                <thead>
                    <tr>
                        <th>State</th>
                        <th>Actual << Forecast</th>
                        <th>Actual > Forecast</th>
                        <th>Actual = Forecast</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-semibold">USD Reaction</td>
                        <td class="text-red-600">Bearish (Sell USD)</td>
                        <td class="text-green-600">Bullish (Buy USD)</td>
                        <td>Neutral/Volatile</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">XAUUSD Reaction</td>
                        <td class="text-green-600">Bullish (Buy Gold)</td>
                        <td class="text-red-600">Bearish (Sell Gold)</td>
                        <td>Range-Bound / Reversal Risk</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Entry Logic</td>
                        <td>Pullback Buy. Wait for USD strength to fade.</td>
                        <td>Breakout Sell. Sell on USD momentum.</td>
                        <td>Fade the Move. Wait 5min, trade opposite of knee-jerk.</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Stop Loss</td>
                        <td>Below recent swing low</td>
                        <td>Above recent swing high</td>
                        <td>2x ATR from entry</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Take Profit 1</td>
                        <td>Previous resistance becomes support</td>
                        <td>Previous support becomes resistance</td>
                        <td>1.5x ATR</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Take Profit 2</td>
                        <td>50% retracement of recent drop</td>
                        <td>50% retracement of recent rally</td>
                        <td>2.5x ATR</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Invalidation</td>
                        <td>USD regains strength >15min</td>
                        <td>USD weakens >15min</td>
                        <td>Price returns to pre-news level</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Hybrid View: Forex Factory iframe + Overlay Table -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Forex Factory iframe -->
        <div class="lg:col-span-2 card p-0 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Forex Factory Calendar</h3>
                    <a href="https://www.forexfactory.com/calendar" target="_blank" class="text-sm text-blue-600 hover:text-blue-700">
                        Open in New Tab →
                    </a>
                </div>
            </div>
            <div class="relative" style="height: 800px;">
                <iframe 
                    src="https://www.forexfactory.com/calendar" 
                    class="w-full h-full border-0"
                    title="Forex Factory Economic Calendar"
                    id="forexFactoryFrame">
                </iframe>
            </div>
        </div>

        <!-- Signal Projection Overlay -->
        <div class="space-y-6">
            <!-- Today's Projections -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Projections</h3>
                <div class="space-y-3">
                    @forelse($events as $event)
                    <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 text-sm">{{ $event['event'] }}</h4>
                                <p class="text-xs text-gray-600">{{ $event['time'] }} • {{ $event['currency'] }}</p>
                            </div>
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $event['impact'] === 'high' ? 'bg-red-100 text-red-800' : ($event['impact'] === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                {{ strtoupper($event['impact']) }}
                            </span>
                        </div>
                        
                        @if(isset($event['signal_data']))
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-600">Signal:</span>
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $event['signal_data']['signal'] === 'BUY' ? 'bg-green-100 text-green-800' : ($event['signal_data']['signal'] === 'SELL' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $event['signal_data']['signal'] }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-600">Confidence:</span>
                                <span class="text-xs font-semibold text-gray-900">{{ number_format($event['signal_data']['confidence'] * 100, 0) }}%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-600">State:</span>
                                <span class="text-xs font-medium text-gray-700">{{ str_replace('_', ' ', $event['signal_data']['state']) }}</span>
                            </div>
                            @if(isset($event['actual']) && isset($event['forecast']))
                            <div class="mt-2 pt-2 border-t border-gray-200">
                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <div>
                                        <p class="text-gray-600">Actual</p>
                                        <p class="font-semibold {{ $event['signal_data']['comparison'] === 'hotter' ? 'text-red-600' : ($event['signal_data']['comparison'] === 'worse' ? 'text-green-600' : 'text-gray-600') }}">
                                            {{ $event['actual'] }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Forecast</p>
                                        <p class="font-semibold text-gray-900">{{ $event['forecast'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Previous</p>
                                        <p class="font-semibold text-gray-900">{{ $event['previous'] }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="mt-2 text-xs text-gray-500">
                            <p>Waiting for data...</p>
                        </div>
                        @endif
                    </div>
                    @empty
                    <p class="text-center text-gray-500 text-sm py-4">No events today</p>
                    @endforelse
                </div>
            </div>

            <!-- Trading Guidelines -->
            <div class="card bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Trading Guidelines</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-start space-x-2">
                        <span class="text-green-600 font-bold">•</span>
                        <p class="text-gray-700"><strong>Pre-News:</strong> FLAT positions 15min before high-impact events</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <span class="text-red-600 font-bold">•</span>
                        <p class="text-gray-700"><strong>News Release:</strong> VOLATILITY_LOCK prevents execution for 5-15 seconds</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <span class="text-yellow-600 font-bold">•</span>
                        <p class="text-gray-700"><strong>Initial Reaction:</strong> Observe first 5 minutes, log impact</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <p class="text-gray-700"><strong>Execution Window:</strong> Trade 5-20 minutes after release</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <span class="text-purple-600 font-bold">•</span>
                        <p class="text-gray-700"><strong>Invalidation:</strong> Monitor for 20 minutes, exit if signal fails</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Events Table with Signal Projections -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Event Analysis with Projected Signals</h3>
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                <span>Bullish for Gold</span>
                <div class="w-2 h-2 bg-red-500 rounded-full ml-3"></div>
                <span>Bearish for Gold</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Currency</th>
                        <th>Event</th>
                        <th>Impact</th>
                        <th>Previous</th>
                        <th>Forecast</th>
                        <th>Actual</th>
                        <th>Comparison</th>
                        <th>USD Reaction</th>
                        <th>XAUUSD Signal</th>
                        <th>Confidence</th>
                        <th>Entry Logic</th>
                        <th>State</th>
                    </tr>
                </thead>
                <tbody id="eventsTable">
                    @forelse($events as $event)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="text-sm font-medium">{{ $event['time'] }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $event['currency'] }}
                            </span>
                        </td>
                        <td class="font-medium">{{ $event['event'] }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $event['impact'] === 'high' ? 'bg-red-100 text-red-800' : ($event['impact'] === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                {{ strtoupper($event['impact']) }}
                            </span>
                        </td>
                        <td class="text-sm">{{ $event['previous'] }}</td>
                        <td class="text-sm font-semibold">{{ $event['forecast'] }}</td>
                        <td class="text-sm {{ isset($event['signal_data']['comparison']) && $event['signal_data']['comparison'] === 'hotter' ? 'text-red-600 font-bold' : (isset($event['signal_data']['comparison']) && $event['signal_data']['comparison'] === 'worse' ? 'text-green-600 font-bold' : '') }}">
                            {{ $event['actual'] ?? 'Pending' }}
                        </td>
                        <td>
                            @if(isset($event['signal_data']['comparison']))
                                @if($event['signal_data']['comparison'] === 'hotter')
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">Hotter</span>
                                @elseif($event['signal_data']['comparison'] === 'worse')
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Worse</span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">In Line</span>
                                @endif
                            @else
                                <span class="text-gray-400">--</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($event['signal_data']['usd_reaction']))
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $event['signal_data']['usd_reaction'] === 'bullish' ? 'bg-green-100 text-green-800' : ($event['signal_data']['usd_reaction'] === 'bearish' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($event['signal_data']['usd_reaction']) }}
                                </span>
                            @else
                                <span class="text-gray-400">--</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($event['signal_data']['signal']))
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $event['signal_data']['signal'] === 'BUY' ? 'bg-green-100 text-green-800' : ($event['signal_data']['signal'] === 'SELL' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $event['signal_data']['signal'] }}
                                </span>
                            @else
                                <span class="text-gray-400">--</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($event['signal_data']['confidence']))
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 w-16">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $event['signal_data']['confidence'] * 100 }}%"></div>
                                    </div>
                                    <span class="text-xs font-semibold">{{ number_format($event['signal_data']['confidence'] * 100, 0) }}%</span>
                                </div>
                            @else
                                <span class="text-gray-400">--</span>
                            @endif
                        </td>
                        <td class="text-xs text-gray-600 max-w-xs">
                            @if(isset($event['signal_data']['entry_logic']))
                                {{ $event['signal_data']['entry_logic'] }}
                            @else
                                --
                            @endif
                        </td>
                        <td>
                            @if(isset($event['signal_data']['state']))
                                <span class="px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ str_replace('_', ' ', $event['signal_data']['state']) }}
                                </span>
                            @else
                                <span class="text-gray-400">--</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="text-center py-8 text-gray-500">No events found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Signal Details Modal -->
    <div id="signalModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 sticky top-0 bg-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900" id="signalEventTitle">Signal Details</h3>
                    <button id="closeSignalModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="px-6 py-4" id="signalDetails">
                <!-- Signal details will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Time synchronization check
    const serverTime = new Date('{{ now()->toIso8601String() }}');
    const clientTime = new Date();
    const timeDiff = Math.abs(serverTime - clientTime);
    
    if (timeDiff > 60000) { // More than 1 minute difference
        console.warn('Time synchronization issue detected. Server and client times differ by', timeDiff / 1000, 'seconds');
    }

    // Auto-refresh every 60 seconds
    setInterval(() => {
        location.reload();
    }, 60000);

    // Signal modal handlers
    document.getElementById('closeSignalModal')?.addEventListener('click', () => {
        document.getElementById('signalModal').classList.add('hidden');
    });

    // Make events clickable to show detailed signal
    document.querySelectorAll('#eventsTable tr').forEach(row => {
        row.addEventListener('click', function() {
            // Extract event data and show modal
            // Implementation would extract data from row
        });
    });
});
</script>
@endpush
@endsection
