@extends('layouts.app')

@section('title', 'Market Tools - FXEngine')
@section('page-title', 'Market Tools')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Market Tools</h2>
        <p class="text-sm text-gray-600 mt-1">Comprehensive market analysis tools and real-time data</p>
    </div>

    <!-- Market Tools Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Live Market -->
        <a href="{{ route('market-tools.live-market') }}" class="card hover:shadow-lg transition-all transform hover:-translate-y-1">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Live Market</h3>
                <p class="text-sm text-gray-600 mb-4">Real-time currency prices and XAUUSD chart</p>
                <div class="flex items-center space-x-2 text-xs text-green-600">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span>Live Data</span>
                </div>
            </div>
        </a>

        <!-- Economic Calendar -->
        <a href="{{ route('market-tools.economic-calendar') }}" class="card hover:shadow-lg transition-all transform hover:-translate-y-1">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Economic Calendar</h3>
                <p class="text-sm text-gray-600 mb-4">Track important economic events and announcements</p>
                <div class="flex items-center space-x-2 text-xs text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Weekly View</span>
                </div>
            </div>
        </a>

        <!-- Spread Monitor -->
        <a href="{{ route('market-tools.spread-monitor') }}" class="card hover:shadow-lg transition-all transform hover:-translate-y-1">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Spread Monitor</h3>
                <p class="text-sm text-gray-600 mb-4">Monitor spreads across all currency pairs</p>
                <div class="flex items-center space-x-2 text-xs text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Real-time</span>
                </div>
            </div>
        </a>

        <!-- Trading Sessions -->
        <a href="{{ route('market-tools.trading-sessions') }}" class="card hover:shadow-lg transition-all transform hover:-translate-y-1">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Trading Sessions</h3>
                <p class="text-sm text-gray-600 mb-4">Global trading sessions and market activity</p>
                <div class="flex items-center space-x-2 text-xs text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>24/7 Monitoring</span>
                </div>
            </div>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Pairs</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">28</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Upcoming Events</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">5</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Sessions</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="activeSessions">2</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Access</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Live Market Prices</h4>
                            <p class="text-sm text-gray-600">View real-time currency quotes</p>
                        </div>
                    </div>
                    <a href="{{ route('market-tools.live-market') }}" class="text-blue-600 hover:text-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Economic Calendar</h4>
                            <p class="text-sm text-gray-600">Check upcoming economic events</p>
                        </div>
                    </div>
                    <a href="{{ route('market-tools.economic-calendar') }}" class="text-green-600 hover:text-green-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Spread Monitor</h4>
                            <p class="text-sm text-gray-600">Monitor trading spreads</p>
                        </div>
                    </div>
                    <a href="{{ route('market-tools.spread-monitor') }}" class="text-purple-600 hover:text-purple-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Trading Sessions</h4>
                            <p class="text-sm text-gray-600">View global trading hours</p>
                        </div>
                    </div>
                    <a href="{{ route('market-tools.trading-sessions') }}" class="text-yellow-600 hover:text-yellow-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update active sessions count
    function updateActiveSessions() {
        const now = new Date();
        const hour = now.getUTCHours();
        let activeCount = 0;

        // Sydney: 22:00 - 07:00 UTC
        if (hour >= 22 || hour < 7) activeCount++;
        // Tokyo: 00:00 - 09:00 UTC
        if (hour >= 0 && hour < 9) activeCount++;
        // London: 08:00 - 17:00 UTC
        if (hour >= 8 && hour < 17) activeCount++;
        // New York: 13:00 - 22:00 UTC
        if (hour >= 13 && hour < 22) activeCount++;

        document.getElementById('activeSessions').textContent = activeCount;
    }

    updateActiveSessions();
    setInterval(updateActiveSessions, 60000); // Update every minute
});
</script>
@endpush
@endsection
