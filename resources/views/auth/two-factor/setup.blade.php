@extends('layouts.app')

@section('title', 'Setup Two-Factor Authentication - FxEngne')
@section('page-title', 'Setup Two-Factor Authentication')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Setup Two-Factor Authentication</h2>
            <p class="text-sm text-gray-600 mt-1">Scan the QR code with your authenticator app</p>
        </div>

        <!-- QR Code -->
        <div class="flex justify-center mb-6">
            <div class="bg-white p-4 rounded-lg border-2 border-gray-200">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrCodeUrl) }}" alt="QR Code" class="w-48 h-48">
            </div>
        </div>

        <!-- Secret Key -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Secret Key</label>
            <div class="flex items-center space-x-2">
                <input type="text" value="{{ $secret }}" readonly class="form-input font-mono text-sm" id="secretKey">
                <button onclick="copySecret()" class="btn btn-secondary text-sm">Copy</button>
            </div>
            <p class="text-xs text-gray-500 mt-2">If you can't scan the QR code, enter this secret key manually</p>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-blue-900 mb-2">Instructions:</h3>
            <ol class="list-decimal list-inside space-y-1 text-sm text-blue-800">
                <li>Install an authenticator app on your phone (Google Authenticator, Authy, Microsoft Authenticator)</li>
                <li>Scan the QR code above or enter the secret key manually</li>
                <li>Enter the 6-digit code from your app below to verify</li>
            </ol>
        </div>

        <!-- Verification Form -->
        <form action="{{ route('auth.two-factor.enable') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                    Enter Verification Code
                </label>
                <input 
                    type="text" 
                    id="code" 
                    name="code" 
                    required 
                    maxlength="6"
                    pattern="[0-9]{6}"
                    class="form-input text-center text-2xl font-mono tracking-widest"
                    placeholder="000000"
                    autocomplete="off"
                >
                @error('code')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="btn btn-primary flex-1">Enable 2FA</button>
                <a href="{{ route('profile.security') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function copySecret() {
    const secretInput = document.getElementById('secretKey');
    secretInput.select();
    document.execCommand('copy');
    
    const btn = event.target;
    const originalText = btn.textContent;
    btn.textContent = 'Copied!';
    btn.classList.add('bg-green-500');
    
    setTimeout(() => {
        btn.textContent = originalText;
        btn.classList.remove('bg-green-500');
    }, 2000);
}

// Auto-focus and format code input
document.getElementById('code').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
@endsection

