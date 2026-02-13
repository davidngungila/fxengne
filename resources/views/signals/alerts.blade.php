@extends('layouts.app')

@section('title', 'Alert Settings - FXEngine')
@section('page-title', 'Alert Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Signal Alert Settings</h2>
            <p class="text-sm text-gray-600 mt-1">Configure alerts for trading signals</p>
        </div>
        <button id="saveSettings" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Save Settings
        </button>
    </div>

    <!-- Notification Channels -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Notification Channels</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Telegram</h4>
                        <p class="text-sm text-gray-600">Receive signals via Telegram bot</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="telegramEnabled" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Email</h4>
                        <p class="text-sm text-gray-600">Receive signals via email</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="emailEnabled" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">In-App Notifications</h4>
                        <p class="text-sm text-gray-600">Show notifications in the application</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="inAppEnabled" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                </label>
            </div>
        </div>
    </div>

    <!-- Signal Filters -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Signal Filters</h3>
        <div class="space-y-4">
            <div>
                <label class="form-label">Minimum Confidence</label>
                <div class="flex items-center space-x-4">
                    <input type="range" id="minConfidence" min="0" max="100" value="60" class="flex-1">
                    <span class="text-lg font-semibold text-gray-900 w-16 text-right" id="minConfidenceValue">60%</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Only receive alerts for signals with confidence above this threshold</p>
            </div>

            <div>
                <label class="form-label">Signal Strength</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="strengthStrong" checked class="form-checkbox">
                        <span class="ml-2">Strong</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="strengthModerate" checked class="form-checkbox">
                        <span class="ml-2">Moderate</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="strengthWeak" class="form-checkbox">
                        <span class="ml-2">Weak</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="form-label">Instruments</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-2">
                    <label class="flex items-center">
                        <input type="checkbox" id="instrumentEURUSD" checked class="form-checkbox">
                        <span class="ml-2 text-sm">EUR/USD</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="instrumentGBPUSD" checked class="form-checkbox">
                        <span class="ml-2 text-sm">GBP/USD</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="instrumentUSDJPY" checked class="form-checkbox">
                        <span class="ml-2 text-sm">USD/JPY</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="instrumentXAUUSD" checked class="form-checkbox">
                        <span class="ml-2 text-sm">XAU/USD</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="form-label">Strategies</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-2">
                    <label class="flex items-center">
                        <input type="checkbox" id="strategyEMA" checked class="form-checkbox">
                        <span class="ml-2 text-sm">EMA Crossover</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="strategyRSI" checked class="form-checkbox">
                        <span class="ml-2 text-sm">RSI</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="strategyMACD" checked class="form-checkbox">
                        <span class="ml-2 text-sm">MACD</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="strategySR" checked class="form-checkbox">
                        <span class="ml-2 text-sm">Support/Resistance</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="strategyBB" class="form-checkbox">
                        <span class="ml-2 text-sm">Bollinger Bands</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="strategyMAC" class="form-checkbox">
                        <span class="ml-2 text-sm">MA Convergence</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Frequency -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Alert Frequency</h3>
        <div class="space-y-4">
            <div>
                <label class="form-label">Cooldown Period</label>
                <select id="cooldownPeriod" class="form-input">
                    <option value="0">No cooldown</option>
                    <option value="300" selected>5 minutes</option>
                    <option value="900">15 minutes</option>
                    <option value="1800">30 minutes</option>
                    <option value="3600">1 hour</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Prevent duplicate alerts for the same instrument</p>
            </div>

            <div>
                <label class="form-label">Maximum Alerts Per Hour</label>
                <input type="number" id="maxAlertsPerHour" min="1" max="100" value="20" class="form-input">
                <p class="text-xs text-gray-500 mt-1">Limit the number of alerts you receive per hour</p>
            </div>
        </div>
    </div>

    <!-- Test Alert -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Test Alert</h3>
        <p class="text-sm text-gray-600 mb-4">Send a test alert to verify your notification settings</p>
        <button id="testAlert" class="btn btn-secondary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            Send Test Alert
        </button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load saved settings
    function loadSettings() {
        const settings = JSON.parse(localStorage.getItem('signalAlertSettings') || '{}');
        
        if (settings.telegramEnabled !== undefined) {
            document.getElementById('telegramEnabled').checked = settings.telegramEnabled;
        }
        if (settings.emailEnabled !== undefined) {
            document.getElementById('emailEnabled').checked = settings.emailEnabled;
        }
        if (settings.inAppEnabled !== undefined) {
            document.getElementById('inAppEnabled').checked = settings.inAppEnabled;
        }
        if (settings.minConfidence !== undefined) {
            document.getElementById('minConfidence').value = settings.minConfidence;
            document.getElementById('minConfidenceValue').textContent = settings.minConfidence + '%';
        }
    }

    // Save settings
    function saveSettings() {
        const settings = {
            telegramEnabled: document.getElementById('telegramEnabled').checked,
            emailEnabled: document.getElementById('emailEnabled').checked,
            inAppEnabled: document.getElementById('inAppEnabled').checked,
            minConfidence: parseInt(document.getElementById('minConfidence').value),
            strengthStrong: document.getElementById('strengthStrong').checked,
            strengthModerate: document.getElementById('strengthModerate').checked,
            strengthWeak: document.getElementById('strengthWeak').checked,
            instruments: {
                EURUSD: document.getElementById('instrumentEURUSD').checked,
                GBPUSD: document.getElementById('instrumentGBPUSD').checked,
                USDJPY: document.getElementById('instrumentUSDJPY').checked,
                XAUUSD: document.getElementById('instrumentXAUUSD').checked
            },
            strategies: {
                EMA: document.getElementById('strategyEMA').checked,
                RSI: document.getElementById('strategyRSI').checked,
                MACD: document.getElementById('strategyMACD').checked,
                SR: document.getElementById('strategySR').checked,
                BB: document.getElementById('strategyBB').checked,
                MAC: document.getElementById('strategyMAC').checked
            },
            cooldownPeriod: parseInt(document.getElementById('cooldownPeriod').value),
            maxAlertsPerHour: parseInt(document.getElementById('maxAlertsPerHour').value)
        };

        localStorage.setItem('signalAlertSettings', JSON.stringify(settings));
        
        alert('Settings saved successfully!');
    }

    // Update confidence display
    document.getElementById('minConfidence').addEventListener('input', function(e) {
        document.getElementById('minConfidenceValue').textContent = e.target.value + '%';
    });

    // Test alert
    document.getElementById('testAlert').addEventListener('click', function() {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('Test Signal Alert', {
                body: 'This is a test alert. Your notification settings are working correctly!',
                icon: '/favicon.ico'
            });
        } else if ('Notification' in window && Notification.permission !== 'denied') {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    new Notification('Test Signal Alert', {
                        body: 'This is a test alert. Your notification settings are working correctly!',
                        icon: '/favicon.ico'
                    });
                }
            });
        }
        
        alert('Test alert sent! Check your configured notification channels.');
    });

    // Save button
    document.getElementById('saveSettings').addEventListener('click', saveSettings);

    // Initial load
    loadSettings();
});
</script>
@endpush
@endsection




