@extends('layouts.app')

@section('title', 'VPS Status - FxEngne')
@section('page-title', 'VPS Status')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">VPS Status</h2>
            <p class="text-sm text-gray-600 mt-1">Monitor your Virtual Private Server status and performance</p>
        </div>
        <button id="refreshStatus" class="btn btn-secondary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Refresh
        </button>
    </div>

    <!-- Server Status -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Server Status</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="serverStatus">Online</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <div class="w-4 h-4 bg-green-500 rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Uptime</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="uptime">--</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">CPU Usage</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="cpuUsage">--</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Memory Usage</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="memoryUsage">--</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- CPU Usage Chart -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">CPU Usage</h3>
            <div class="relative" style="height: 200px;">
                <canvas id="cpuChart"></canvas>
            </div>
        </div>

        <!-- Memory Usage Chart -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Memory Usage</h3>
            <div class="relative" style="height: 200px;">
                <canvas id="memoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">System Information</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Hostname:</span>
                    <span class="font-medium text-gray-900" id="hostname">--</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Operating System:</span>
                    <span class="font-medium text-gray-900" id="os">--</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">PHP Version:</span>
                    <span class="font-medium text-gray-900">{{ PHP_VERSION }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Laravel Version:</span>
                    <span class="font-medium text-gray-900">{{ app()->version() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Server Time:</span>
                    <span class="font-medium text-gray-900" id="serverTime">--</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Network & Performance</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-gray-600">Network Latency</span>
                        <span class="font-semibold text-gray-900" id="networkLatency">--</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" id="latencyBar" style="width: 0%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-gray-600">Disk Usage</span>
                        <span class="font-semibold text-gray-900" id="diskUsage">--</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" id="diskBar" style="width: 0%"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                    <span class="text-gray-600">Last Update:</span>
                    <span class="text-sm text-gray-600" id="lastUpdate">--</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Trading Bot Status -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Trading Bot Status</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Bot Status</span>
                    <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800" id="botStatus">Unknown</span>
                </div>
                <p class="text-xs text-gray-500">Automated trading system</p>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Active Strategies</span>
                    <span class="font-semibold text-gray-900" id="activeStrategies">0</span>
                </div>
                <p class="text-xs text-gray-500">Currently running</p>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Last Execution</span>
                    <span class="text-sm font-medium text-gray-900" id="lastExecution">Never</span>
                </div>
                <p class="text-xs text-gray-500">Trading cycle</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '{{ url("/api") }}';
    let cpuChart = null;
    let memoryChart = null;
    let cpuData = [];
    let memoryData = [];
    let timeLabels = [];

    // Load VPS status
    async function loadVpsStatus() {
        try {
            const response = await fetch(`${API_BASE_URL}/broker/vps-status`);
            const result = await response.json();

            if (result.success && result.data) {
                const data = result.data;
                
                // Update status indicators
                document.getElementById('serverStatus').textContent = data.status === 'online' ? 'Online' : 'Offline';
                document.getElementById('serverStatus').className = data.status === 'online' 
                    ? 'text-2xl font-bold text-green-600 mt-1' 
                    : 'text-2xl font-bold text-red-600 mt-1';
                
                document.getElementById('uptime').textContent = data.uptime || '--';
                document.getElementById('cpuUsage').textContent = (data.cpu_usage || 0) + '%';
                document.getElementById('memoryUsage').textContent = (data.memory_usage || 0) + '%';
                document.getElementById('networkLatency').textContent = (data.network_latency || 0) + 'ms';
                document.getElementById('diskUsage').textContent = (data.disk_usage || 0) + '%';
                document.getElementById('lastUpdate').textContent = data.last_update || '--';
                document.getElementById('serverTime').textContent = new Date().toLocaleString();

                // Update progress bars
                document.getElementById('latencyBar').style.width = Math.min(100, (data.network_latency || 0) / 100 * 100) + '%';
                document.getElementById('diskBar').style.width = (data.disk_usage || 0) + '%';

                // Update charts
                updateCharts(data.cpu_usage || 0, data.memory_usage || 0);
            }
        } catch (error) {
            console.error('Error loading VPS status:', error);
        }
    }

    // Update charts
    function updateCharts(cpu, memory) {
        const now = new Date();
        const timeLabel = now.toLocaleTimeString();
        
        timeLabels.push(timeLabel);
        cpuData.push(cpu);
        memoryData.push(memory);

        // Keep only last 20 data points
        if (timeLabels.length > 20) {
            timeLabels.shift();
            cpuData.shift();
            memoryData.shift();
        }

        // CPU Chart
        const cpuCtx = document.getElementById('cpuChart');
        if (cpuCtx) {
            if (cpuChart) {
                cpuChart.destroy();
            }

            cpuChart = new Chart(cpuCtx, {
                type: 'line',
                data: {
                    labels: timeLabels,
                    datasets: [{
                        label: 'CPU Usage',
                        data: cpuData,
                        borderColor: TradingColors.movingAverages.ema9,
                        backgroundColor: TradingColors.toRgba(TradingColors.movingAverages.ema9, 0.1),
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Memory Chart
        const memoryCtx = document.getElementById('memoryChart');
        if (memoryCtx) {
            if (memoryChart) {
                memoryChart.destroy();
            }

            memoryChart = new Chart(memoryCtx, {
                type: 'line',
                data: {
                    labels: timeLabels,
                    datasets: [{
                        label: 'Memory Usage',
                        data: memoryData,
                        borderColor: TradingColors.movingAverages.ema21,
                        backgroundColor: TradingColors.toRgba(TradingColors.movingAverages.ema21, 0.1),
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Load bot status
    async function loadBotStatus() {
        try {
            const response = await fetch(`${API_BASE_URL}/bot/status`);
            const result = await response.json();

            if (result.success && result.data) {
                document.getElementById('botStatus').textContent = result.data.running ? 'Running' : 'Stopped';
                document.getElementById('botStatus').className = result.data.running 
                    ? 'px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800'
                    : 'px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800';
                document.getElementById('activeStrategies').textContent = result.data.active_strategies || 0;
            }
        } catch (error) {
            console.error('Error loading bot status:', error);
        }
    }

    // Event listeners
    document.getElementById('refreshStatus').addEventListener('click', function() {
        loadVpsStatus();
        loadBotStatus();
    });

    // Initial load
    loadVpsStatus();
    loadBotStatus();

    // Auto-refresh every 10 seconds
    setInterval(() => {
        loadVpsStatus();
        loadBotStatus();
    }, 10000);
});
</script>
@endpush
@endsection

