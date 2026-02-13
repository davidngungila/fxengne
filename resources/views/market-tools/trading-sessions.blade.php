@extends('layouts.app')

@section('title', 'Trading Sessions - FXEngine')
@section('page-title', 'Trading Sessions')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Trading Sessions</h2>
            <p class="text-sm text-gray-600 mt-1">Monitor global trading sessions and market activity</p>
        </div>
        <div class="flex items-center space-x-3">
            <select id="timezone" class="form-input text-sm">
                <option value="UTC">UTC</option>
                <option value="EST">EST</option>
                <option value="PST">PST</option>
                <option value="GMT">GMT</option>
                <option value="JST">JST</option>
            </select>
            <button id="refreshSessions" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Current Time Display -->
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Current Time</p>
                <p class="text-3xl font-bold text-gray-900 mt-1" id="currentTime">--:--:--</p>
                <p class="text-sm text-gray-500 mt-1" id="currentDate">--</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Active Sessions</p>
                <p class="text-3xl font-bold text-blue-600 mt-1" id="activeSessions">0</p>
            </div>
        </div>
    </div>

    <!-- Trading Sessions Timeline -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">24-Hour Trading Sessions</h3>
        <div class="relative" style="height: 200px;">
            <canvas id="sessionsChart"></canvas>
        </div>
    </div>

    <!-- Session Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Sydney Session -->
        <div class="card" id="sydneySession">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">🇦🇺</span>
                    <h4 class="font-semibold text-gray-900">Sydney</h4>
                </div>
                <div class="w-3 h-3 rounded-full bg-gray-300" id="sydneyStatus"></div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Open:</span>
                    <span class="font-medium" id="sydneyOpen">22:00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Close:</span>
                    <span class="font-medium" id="sydneyClose">07:00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="font-medium" id="sydneyStatusText">Closed</span>
                </div>
            </div>
        </div>

        <!-- Tokyo Session -->
        <div class="card" id="tokyoSession">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">🇯🇵</span>
                    <h4 class="font-semibold text-gray-900">Tokyo</h4>
                </div>
                <div class="w-3 h-3 rounded-full bg-gray-300" id="tokyoStatus"></div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Open:</span>
                    <span class="font-medium" id="tokyoOpen">00:00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Close:</span>
                    <span class="font-medium" id="tokyoClose">09:00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="font-medium" id="tokyoStatusText">Closed</span>
                </div>
            </div>
        </div>

        <!-- London Session -->
        <div class="card" id="londonSession">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">🇬🇧</span>
                    <h4 class="font-semibold text-gray-900">London</h4>
                </div>
                <div class="w-3 h-3 rounded-full bg-gray-300" id="londonStatus"></div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Open:</span>
                    <span class="font-medium" id="londonOpen">08:00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Close:</span>
                    <span class="font-medium" id="londonClose">17:00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="font-medium" id="londonStatusText">Closed</span>
                </div>
            </div>
        </div>

        <!-- New York Session -->
        <div class="card" id="newyorkSession">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">🇺🇸</span>
                    <h4 class="font-semibold text-gray-900">New York</h4>
                </div>
                <div class="w-3 h-3 rounded-full bg-gray-300" id="newyorkStatus"></div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Open:</span>
                    <span class="font-medium" id="newyorkOpen">13:00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Close:</span>
                    <span class="font-medium" id="newyorkClose">22:00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="font-medium" id="newyorkStatusText">Closed</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Overlaps -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">High Activity Periods (Session Overlaps)</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="text-xl">🇬🇧🇺🇸</span>
                    <h4 class="font-semibold text-gray-900">London-New York</h4>
                </div>
                <p class="text-sm text-gray-600 mb-2">13:00 - 17:00 UTC</p>
                <p class="text-xs text-gray-500">Highest volatility period</p>
                <div class="mt-2">
                    <span class="px-2 py-1 bg-blue-200 text-blue-800 rounded text-xs font-medium" id="londonNYOverlap">Inactive</span>
                </div>
            </div>

            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="text-xl">🇯🇵🇬🇧</span>
                    <h4 class="font-semibold text-gray-900">Tokyo-London</h4>
                </div>
                <p class="text-sm text-gray-600 mb-2">08:00 - 09:00 UTC</p>
                <p class="text-xs text-gray-500">Asian-European overlap</p>
                <div class="mt-2">
                    <span class="px-2 py-1 bg-green-200 text-green-800 rounded text-xs font-medium" id="tokyoLondonOverlap">Inactive</span>
                </div>
            </div>

            <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="text-xl">🇦🇺🇯🇵</span>
                    <h4 class="font-semibold text-gray-900">Sydney-Tokyo</h4>
                </div>
                <p class="text-sm text-gray-600 mb-2">00:00 - 07:00 UTC</p>
                <p class="text-xs text-gray-500">Asian session overlap</p>
                <div class="mt-2">
                    <span class="px-2 py-1 bg-purple-200 text-purple-800 rounded text-xs font-medium" id="sydneyTokyoOverlap">Inactive</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Market Activity Heatmap -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Weekly Activity Heatmap</h3>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Time (UTC)</th>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                        <th>Sun</th>
                    </tr>
                </thead>
                <tbody id="activityHeatmap">
                    <!-- Heatmap will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sessions = {
        sydney: { open: 22, close: 7, name: 'Sydney' },
        tokyo: { open: 0, close: 9, name: 'Tokyo' },
        london: { open: 8, close: 17, name: 'London' },
        newyork: { open: 13, close: 22, name: 'New York' }
    };

    function updateTime() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { hour12: false });
        const dateStr = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        
        document.getElementById('currentTime').textContent = timeStr;
        document.getElementById('currentDate').textContent = dateStr;
    }

    function checkSessionStatus(session, openHour, closeHour) {
        const now = new Date();
        const currentHour = now.getUTCHours();
        
        let isOpen = false;
        if (closeHour > openHour) {
            isOpen = currentHour >= openHour && currentHour < closeHour;
        } else {
            isOpen = currentHour >= openHour || currentHour < closeHour;
        }

        return isOpen;
    }

    function updateSessions() {
        let activeCount = 0;

        // Sydney
        const sydneyOpen = checkSessionStatus('sydney', sessions.sydney.open, sessions.sydney.close);
        updateSessionCard('sydney', sydneyOpen, sessions.sydney);
        if (sydneyOpen) activeCount++;

        // Tokyo
        const tokyoOpen = checkSessionStatus('tokyo', sessions.tokyo.open, sessions.tokyo.close);
        updateSessionCard('tokyo', tokyoOpen, sessions.tokyo);
        if (tokyoOpen) activeCount++;

        // London
        const londonOpen = checkSessionStatus('london', sessions.london.open, sessions.london.close);
        updateSessionCard('london', londonOpen, sessions.london);
        if (londonOpen) activeCount++;

        // New York
        const nyOpen = checkSessionStatus('newyork', sessions.newyork.open, sessions.newyork.close);
        updateSessionCard('newyork', nyOpen, sessions.newyork);
        if (nyOpen) activeCount++;

        document.getElementById('activeSessions').textContent = activeCount;

        // Update overlaps
        updateOverlaps();
        renderSessionsChart();
    }

    function updateSessionCard(sessionName, isOpen, session) {
        const statusEl = document.getElementById(`${sessionName}Status`);
        const statusTextEl = document.getElementById(`${sessionName}StatusText`);
        const cardEl = document.getElementById(`${sessionName}Session`);

        if (isOpen) {
            statusEl.className = 'w-3 h-3 rounded-full bg-green-500 animate-pulse';
            statusTextEl.textContent = 'Open';
            statusTextEl.className = 'font-medium text-green-600';
            cardEl.classList.add('ring-2', 'ring-green-500');
        } else {
            statusEl.className = 'w-3 h-3 rounded-full bg-gray-300';
            statusTextEl.textContent = 'Closed';
            statusTextEl.className = 'font-medium text-gray-600';
            cardEl.classList.remove('ring-2', 'ring-green-500');
        }

        document.getElementById(`${sessionName}Open`).textContent = 
            String(session.open).padStart(2, '0') + ':00';
        document.getElementById(`${sessionName}Close`).textContent = 
            String(session.close).padStart(2, '0') + ':00';
    }

    function updateOverlaps() {
        const now = new Date();
        const hour = now.getUTCHours();

        // London-New York (13:00 - 17:00)
        const londonNY = hour >= 13 && hour < 17;
        document.getElementById('londonNYOverlap').textContent = londonNY ? 'Active' : 'Inactive';
        document.getElementById('londonNYOverlap').className = 
            londonNY ? 'px-2 py-1 bg-blue-600 text-white rounded text-xs font-medium' :
            'px-2 py-1 bg-blue-200 text-blue-800 rounded text-xs font-medium';

        // Tokyo-London (08:00 - 09:00)
        const tokyoLondon = hour >= 8 && hour < 9;
        document.getElementById('tokyoLondonOverlap').textContent = tokyoLondon ? 'Active' : 'Inactive';
        document.getElementById('tokyoLondonOverlap').className = 
            tokyoLondon ? 'px-2 py-1 bg-green-600 text-white rounded text-xs font-medium' :
            'px-2 py-1 bg-green-200 text-green-800 rounded text-xs font-medium';

        // Sydney-Tokyo (00:00 - 07:00)
        const sydneyTokyo = hour >= 0 && hour < 7;
        document.getElementById('sydneyTokyoOverlap').textContent = sydneyTokyo ? 'Active' : 'Inactive';
        document.getElementById('sydneyTokyoOverlap').className = 
            sydneyTokyo ? 'px-2 py-1 bg-purple-600 text-white rounded text-xs font-medium' :
            'px-2 py-1 bg-purple-200 text-purple-800 rounded text-xs font-medium';
    }

    function renderSessionsChart() {
        const ctx = document.getElementById('sessionsChart');
        if (!ctx) return;

        // Create 24-hour timeline data
        const hours = Array.from({ length: 24 }, (_, i) => i);
        const sydneyData = hours.map(h => {
            const open = sessions.sydney.open;
            const close = sessions.sydney.close;
            return (close > open) ? (h >= open && h < close ? 1 : 0) : (h >= open || h < close ? 1 : 0);
        });
        const tokyoData = hours.map(h => h >= sessions.tokyo.open && h < sessions.tokyo.close ? 1 : 0);
        const londonData = hours.map(h => h >= sessions.london.open && h < sessions.london.close ? 1 : 0);
        const nyData = hours.map(h => h >= sessions.newyork.open && h < sessions.newyork.close ? 1 : 0);

        if (window.sessionsChartInstance) {
            window.sessionsChartInstance.destroy();
        }

        window.sessionsChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: hours.map(h => String(h).padStart(2, '0') + ':00'),
                datasets: [
                    {
                        label: 'Sydney',
                        data: sydneyData,
                        backgroundColor: TradingColors.toRgba(TradingColors.movingAverages.ema9, 0.7),
                        borderColor: TradingColors.movingAverages.ema9,
                        borderWidth: 1
                    },
                    {
                        label: 'Tokyo',
                        data: tokyoData,
                        backgroundColor: TradingColors.toRgba(TradingColors.movingAverages.ema21, 0.7),
                        borderColor: TradingColors.movingAverages.ema21,
                        borderWidth: 1
                    },
                    {
                        label: 'London',
                        data: londonData,
                        backgroundColor: TradingColors.toRgba(TradingColors.entryExit.takeProfit, 0.7),
                        borderColor: TradingColors.entryExit.takeProfit,
                        borderWidth: 1
                    },
                    {
                        label: 'New York',
                        data: nyData,
                        backgroundColor: TradingColors.toRgba(TradingColors.candles.bullish, 0.7),
                        borderColor: TradingColors.candles.bullish,
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        stacked: true,
                        max: 4,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return value === 1 ? 'Open' : '';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + (context.parsed.y === 1 ? 'Open' : 'Closed');
                            }
                        }
                    }
                }
            }
        });
    }

    function renderActivityHeatmap() {
        const tbody = document.getElementById('activityHeatmap');
        const hours = Array.from({ length: 24 }, (_, i) => i);
        const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        tbody.innerHTML = hours.map(hour => {
            const hourStr = String(hour).padStart(2, '0') + ':00';
            const cells = days.map(day => {
                // Calculate activity level (0-3 sessions open)
                let activity = 0;
                if (hour >= sessions.sydney.open || hour < sessions.sydney.close) activity++;
                if (hour >= sessions.tokyo.open && hour < sessions.tokyo.close) activity++;
                if (hour >= sessions.london.open && hour < sessions.london.close) activity++;
                if (hour >= sessions.newyork.open && hour < sessions.newyork.close) activity++;

                const colors = [
                    'bg-gray-100',
                    'bg-green-100',
                    'bg-yellow-100',
                    'bg-orange-100',
                    'bg-red-100'
                ];

                return `<td class="text-center py-2 ${colors[activity]}">${activity}</td>`;
            }).join('');

            return `<tr><td class="font-medium py-2">${hourStr}</td>${cells}</tr>`;
        }).join('');
    }

    document.getElementById('refreshSessions').addEventListener('click', () => {
        updateSessions();
    });

    // Initial load
    updateTime();
    updateSessions();
    renderActivityHeatmap();

    // Update every second
    setInterval(updateTime, 1000);
    setInterval(updateSessions, 60000); // Update sessions every minute
});
</script>
@endpush
@endsection




