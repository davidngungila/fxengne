@extends('layouts.app')

@section('title', 'API Settings - FXEngine')
@section('page-title', 'API Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">API Settings</h2>
            <p class="text-sm text-gray-600 mt-1">Configure your broker API credentials</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $oandaEnabled ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                {{ $oandaEnabled ? 'Configured' : 'Not Configured' }}
            </span>
        </div>
    </div>

    <!-- OANDA API Configuration -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">OANDA API Configuration</h3>
                <p class="text-sm text-gray-600 mt-1">Configure your OANDA API credentials for live trading</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded-full {{ $oandaEnabled ? 'bg-green-500 animate-pulse' : 'bg-gray-300' }}"></div>
                <span class="text-sm text-gray-600">{{ $oandaEnabled ? 'Active' : 'Inactive' }}</span>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <h4 class="font-medium text-yellow-800">Important Security Notice</h4>
                    <p class="text-sm text-yellow-700 mt-1">API credentials are stored securely in environment variables. Never commit API keys to version control. Update your <code class="bg-yellow-100 px-1 rounded">.env</code> file to configure these settings.</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- API Key -->
            <div>
                <label class="form-label">API Key</label>
                <div class="flex items-center space-x-2">
                    <input type="password" id="apiKey" value="{{ $oandaEnabled ? '••••••••••••••••' : '' }}" 
                           class="form-input flex-1" placeholder="Enter your OANDA API key" 
                           {{ $oandaEnabled ? 'disabled' : '' }}>
                    <button type="button" onclick="toggleApiKey()" class="btn btn-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Get your API key from OANDA account settings</p>
            </div>

            <!-- Account ID -->
            <div>
                <label class="form-label">Account ID</label>
                <input type="text" id="accountId" value="{{ $oandaEnabled ? 'Configured' : '' }}" 
                       class="form-input" placeholder="Enter your OANDA Account ID"
                       {{ $oandaEnabled ? 'disabled' : '' }}>
                <p class="text-xs text-gray-500 mt-1">Your OANDA account identifier</p>
            </div>

            <!-- Environment -->
            <div>
                <label class="form-label">Environment</label>
                <select id="environment" class="form-input" {{ $oandaEnabled ? 'disabled' : '' }}>
                    <option value="practice" {{ $environment === 'practice' ? 'selected' : '' }}>Practice (Demo)</option>
                    <option value="live" {{ $environment === 'live' ? 'selected' : '' }}>Live (Real Money)</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    <span class="font-medium text-yellow-600">⚠️ Warning:</span> 
                    Live environment uses real money. Always test with Practice account first.
                </p>
            </div>

            <!-- Configuration Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-900 mb-2">Configuration Instructions</h4>
                <ol class="list-decimal list-inside space-y-1 text-sm text-blue-800">
                    <li>Open your <code class="bg-blue-100 px-1 rounded">.env</code> file in the project root</li>
                    <li>Add the following variables:
                        <pre class="bg-blue-100 p-2 rounded mt-2 text-xs"><code>OANDA_API_KEY=your_api_key_here
OANDA_ACCOUNT_ID=your_account_id_here
OANDA_ENVIRONMENT=practice</code></pre>
                    </li>
                    <li>Save the file and run: <code class="bg-blue-100 px-1 rounded">php artisan config:clear</code></li>
                    <li>Refresh this page to verify the configuration</li>
                </ol>
            </div>

            <!-- Test Connection -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div>
                    <h4 class="font-medium text-gray-900">Test Configuration</h4>
                    <p class="text-sm text-gray-600">Verify your API credentials are working correctly</p>
                </div>
                <a href="{{ route('broker.connection') }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Test Connection
                </a>
            </div>
        </div>
    </div>

    <!-- API Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">API Endpoints</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Practice:</span>
                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">api-fxpractice.oanda.com</code>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Live:</span>
                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">api-fxtrade.oanda.com</code>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">API Version:</span>
                    <span class="font-medium">v3</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Rate Limits</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Requests/Second:</span>
                    <span class="font-medium">20</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Concurrent Requests:</span>
                    <span class="font-medium">5</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Daily Limit:</span>
                    <span class="font-medium">Unlimited</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Best Practices -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Security Best Practices</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-medium text-gray-900">Use Environment Variables</h4>
                    <p class="text-sm text-gray-600">Never hardcode API keys in source code</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-medium text-gray-900">Practice Account First</h4>
                    <p class="text-sm text-gray-600">Always test with demo account before live trading</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-medium text-gray-900">Rotate Keys Regularly</h4>
                    <p class="text-sm text-gray-600">Change API keys periodically for security</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-medium text-gray-900">Monitor Usage</h4>
                    <p class="text-sm text-gray-600">Check execution logs regularly for suspicious activity</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleApiKey() {
    const input = document.getElementById('apiKey');
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}
</script>
@endpush
@endsection



