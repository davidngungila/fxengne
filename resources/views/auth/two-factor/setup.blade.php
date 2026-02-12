@extends('layouts.app')

@section('title', 'Setup Two-Factor Authentication - FxEngne')
@section('page-title', 'Setup Two-Factor Authentication')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Progress Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4 flex-1">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-semibold">
                        1
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Scan QR Code</p>
                        <p class="text-xs text-gray-500">Get your authenticator app ready</p>
                    </div>
                </div>
                <div class="flex-1 h-0.5 bg-gray-200 mx-4">
                    <div class="h-full bg-blue-600 w-0 transition-all duration-500" id="progressBar"></div>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600 font-semibold" id="step2">
                        2
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Verify Code</p>
                        <p class="text-xs text-gray-500">Enter 6-digit code</p>
                    </div>
                </div>
                <div class="flex-1 h-0.5 bg-gray-200 mx-4"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600 font-semibold" id="step3">
                        3
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Complete</p>
                        <p class="text-xs text-gray-500">Save recovery codes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="text-sm font-medium text-red-800">Please fix the following errors:</p>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: QR Code & Secret -->
        <div class="lg:col-span-2 space-y-6">
            <!-- QR Code Section -->
            <div class="card bg-gradient-to-br from-blue-50 to-purple-50 border-blue-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Scan QR Code</h3>
                        <p class="text-sm text-gray-600 mt-1">Use your authenticator app to scan this code</p>
                    </div>
                    <div class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                        Step 1 of 3
                    </div>
                </div>
                
                <div class="flex flex-col items-center justify-center p-6 bg-white rounded-lg border-2 border-blue-200 shadow-sm">
                    <div class="relative mb-4">
                        <div class="absolute inset-0 bg-blue-100 rounded-lg blur-xl opacity-50"></div>
                        <div class="relative bg-white p-4 rounded-lg border-2 border-gray-200 shadow-lg">
                            <img 
                                src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($qrCodeUrl) }}" 
                                alt="QR Code" 
                                class="w-64 h-64"
                                id="qrCodeImage"
                            >
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 text-center max-w-md">
                        Open your authenticator app and scan this QR code. The app will generate a 6-digit code that changes every 30 seconds.
                    </p>
                </div>
            </div>

            <!-- Secret Key Section -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Manual Entry</h3>
                        <p class="text-sm text-gray-600 mt-1">Can't scan? Enter the secret key manually</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Secret Key</label>
                        <div class="flex items-center space-x-2">
                            <div class="relative flex-1">
                                <input 
                                    type="password" 
                                    value="{{ $secret }}" 
                                    readonly 
                                    class="form-input font-mono text-sm pr-20" 
                                    id="secretKey"
                                >
                                <button 
                                    type="button"
                                    onclick="toggleSecretVisibility()" 
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                    id="toggleSecretBtn"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIcon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            <button 
                                onclick="copySecret()" 
                                class="btn btn-secondary whitespace-nowrap"
                                id="copySecretBtn"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Copy Key
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            If you can't scan the QR code, enter this secret key manually into your authenticator app
                        </p>
                    </div>
                </div>
            </div>

            <!-- Verification Form -->
            <div class="card" id="verificationSection">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Verify Setup</h3>
                        <p class="text-sm text-gray-600 mt-1">Enter the 6-digit code from your authenticator app</p>
                    </div>
                    <div class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                        Step 2 of 3
                    </div>
                </div>
                
                <form action="{{ route('auth.two-factor.enable') }}" method="POST" id="verifyForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-3 text-center">
                            Enter Verification Code
                        </label>
                        <div class="flex justify-center">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="code" 
                                    name="code" 
                                    required 
                                    maxlength="6"
                                    pattern="[0-9]{6}"
                                    class="form-input text-center text-3xl font-mono tracking-[0.5em] w-48 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="000000"
                                    autocomplete="off"
                                    autofocus
                                >
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="flex space-x-2" id="codeDots">
                                        <div class="w-8 h-1 bg-gray-300 rounded"></div>
                                        <div class="w-8 h-1 bg-gray-300 rounded"></div>
                                        <div class="w-8 h-1 bg-gray-300 rounded"></div>
                                        <div class="w-8 h-1 bg-gray-300 rounded"></div>
                                        <div class="w-8 h-1 bg-gray-300 rounded"></div>
                                        <div class="w-8 h-1 bg-gray-300 rounded"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('code')
                            <p class="text-red-600 text-sm mt-2 text-center">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-3 text-center">
                            The code refreshes every 30 seconds. Make sure you enter the current code.
                        </p>
                    </div>

                    <div class="flex space-x-3 pt-4">
                        <button type="submit" class="btn btn-primary flex-1 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Verify & Enable 2FA
                        </button>
                        <a href="{{ route('profile.security') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Instructions & Tips -->
        <div class="space-y-6">
            <!-- Authenticator Apps -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recommended Apps</h3>
                <div class="space-y-3">
                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 text-sm">Google Authenticator</p>
                            <p class="text-xs text-gray-500">Free • iOS & Android</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="https://authy.com/" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.486 22 2 17.514 2 12S6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/>
                                <path d="M12 6v6l4 2"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 text-sm">Authy</p>
                            <p class="text-xs text-gray-500">Free • Multi-device</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="https://www.microsoft.com/en-us/security/mobile-authenticator" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.486 22 2 17.514 2 12S6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 text-sm">Microsoft Authenticator</p>
                            <p class="text-xs text-gray-500">Free • iOS & Android</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Step-by-Step Instructions -->
            <div class="card bg-blue-50 border-blue-200">
                <div class="flex items-start mb-4">
                    <svg class="w-6 h-6 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-blue-900">Quick Setup Guide</h3>
                </div>
                <ol class="space-y-3 text-sm text-blue-800">
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-xs font-semibold mr-3">1</span>
                        <span>Download and install an authenticator app from the list above</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-xs font-semibold mr-3">2</span>
                        <span>Open the app and tap "Add Account" or the "+" button</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-xs font-semibold mr-3">3</span>
                        <span>Scan the QR code displayed on the left, or enter the secret key manually</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-xs font-semibold mr-3">4</span>
                        <span>Enter the 6-digit code from your app to verify and complete setup</span>
                    </li>
                </ol>
            </div>

            <!-- Security Tips -->
            <div class="card bg-yellow-50 border-yellow-200">
                <div class="flex items-start mb-4">
                    <svg class="w-6 h-6 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-yellow-900">Security Tips</h3>
                </div>
                <ul class="space-y-2 text-sm text-yellow-800">
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Keep your authenticator app secure and don't share your device</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Save your recovery codes in a safe place - you'll need them if you lose access</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>The verification code changes every 30 seconds for security</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Don't screenshot or share your QR code or secret key</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let secretVisible = false;

function toggleSecretVisibility() {
    const input = document.getElementById('secretKey');
    const btn = document.getElementById('toggleSecretBtn');
    const icon = document.getElementById('eyeIcon');
    
    secretVisible = !secretVisible;
    input.type = secretVisible ? 'text' : 'password';
    
    if (secretVisible) {
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.736m-11.822 0A10.025 10.025 0 013 12c0-1.542.328-3.005.91-4.338m11.822 0A9.953 9.953 0 0112 5c-1.542 0-3.005.328-4.338.91"></path>';
    } else {
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
    }
}

function copySecret() {
    const secretInput = document.getElementById('secretKey');
    const btn = document.getElementById('copySecretBtn');
    
    // Temporarily show secret if hidden
    const wasHidden = secretInput.type === 'password';
    if (wasHidden) {
        secretInput.type = 'text';
    }
    
    secretInput.select();
    secretInput.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    if (wasHidden) {
        secretInput.type = 'password';
    }
    
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Copied!';
    btn.classList.add('bg-green-500', 'hover:bg-green-600');
    btn.classList.remove('bg-gray-200');
    
    setTimeout(() => {
        btn.innerHTML = originalHtml;
        btn.classList.remove('bg-green-500', 'hover:bg-green-600');
        btn.classList.add('bg-gray-200');
    }, 2000);
}

// Code input handling
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    const codeDots = document.getElementById('codeDots');
    const verifyForm = document.getElementById('verifyForm');
    const progressBar = document.getElementById('progressBar');
    
    // Update progress bar
    progressBar.style.width = '50%';
    document.getElementById('step2').classList.remove('bg-gray-200', 'text-gray-600');
    document.getElementById('step2').classList.add('bg-blue-600', 'text-white');
    
    // Format code input
    codeInput.addEventListener('input', function(e) {
        // Only allow numbers
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Update dots
        const value = this.value;
        const dots = codeDots.querySelectorAll('div');
        dots.forEach((dot, index) => {
            if (index < value.length) {
                dot.classList.remove('bg-gray-300');
                dot.classList.add('bg-blue-500');
            } else {
                dot.classList.remove('bg-blue-500');
                dot.classList.add('bg-gray-300');
            }
        });
        
        // Auto-submit when 6 digits are entered
        if (value.length === 6) {
            setTimeout(() => {
                verifyForm.submit();
            }, 300);
        }
    });
    
    // Focus on code input
    codeInput.focus();
    
    // Handle paste
    codeInput.addEventListener('paste', function(e) {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        const numbers = pasted.replace(/[^0-9]/g, '').substring(0, 6);
        this.value = numbers;
        this.dispatchEvent(new Event('input'));
    });
});
</script>
@endpush
@endsection
