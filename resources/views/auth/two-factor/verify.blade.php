<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verify Two-Factor Authentication - FXEngine</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased">
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-white z-50 flex items-center justify-center hidden">
        <div class="text-center">
            <!-- Rotating Loading Icon -->
            <div class="relative w-20 h-20 mx-auto mb-6">
                <div class="absolute inset-0 rounded-full border-4 border-blue-200"></div>
                <div class="absolute inset-0 rounded-full border-4 border-blue-600 border-t-transparent animate-spin"></div>
                <div class="absolute inset-2 rounded-full bg-blue-50"></div>
                <div class="absolute inset-4 rounded-full bg-blue-600 opacity-20 animate-pulse"></div>
            </div>
            
            <!-- Main Text -->
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Verifying your identity...</h2>
            
            <!-- Secondary Text -->
            <p class="text-blue-600 text-sm mb-6">Securing your trading account</p>
            
            <!-- Progress Bar -->
            <div class="w-64 mx-auto h-2 bg-gray-200 rounded-full overflow-hidden">
                <div id="progressBar" class="h-full bg-gradient-to-r from-blue-500 to-purple-600 rounded-full transition-all duration-1000" style="width: 0%"></div>
            </div>
            
            <!-- Loading Dots -->
            <div class="flex justify-center space-x-2 mt-6">
                <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
            </div>
        </div>
    </div>

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl mb-4">
                        <span class="text-white font-bold text-2xl">FX</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">Two-Factor Authentication</h1>
                    <p class="text-gray-600 mt-2">Enter the code from your authenticator app</p>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-600">{{ $errors->first() }}</p>
                </div>
                @endif

                <!-- Verification Form -->
                <form id="verifyForm" action="{{ route('auth.two-factor.verify') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2 text-center">
                            Verification Code
                        </label>
                        <input 
                            type="text" 
                            id="code" 
                            name="code" 
                            required 
                            maxlength="6"
                            pattern="[0-9]{6}"
                            class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-center text-3xl font-mono tracking-widest"
                            placeholder="000000"
                            autocomplete="off"
                            autofocus
                        >
                    </div>

                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-blue-600 hover:to-purple-700 transition-all transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Verify Code
                    </button>
                </form>

                <!-- Recovery Code Option -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <details class="group">
                        <summary class="cursor-pointer text-sm text-gray-600 hover:text-gray-900">
                            Lost access to your authenticator? Use a recovery code
                        </summary>
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800 mb-2">
                                Enter one of your recovery codes instead of the verification code above.
                            </p>
                            <p class="text-xs text-yellow-700">
                                Recovery codes are shown when you enable 2FA. If you don't have them, contact support.
                            </p>
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('code');
        const form = document.getElementById('verifyForm');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const progressBar = document.getElementById('progressBar');
        const submitBtn = document.getElementById('submitBtn');
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

        // Auto-format code input
        codeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Auto-submit when 6 digits are entered
            if (this.value.length === 6) {
                submitForm();
            }
        });

        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm();
        });

        function submitForm() {
            const code = codeInput.value.trim();
            
            if (code.length !== 6) {
                return;
            }

            // Hide error messages
            const errorDiv = document.querySelector('.bg-red-50');
            if (errorDiv) {
                errorDiv.classList.add('hidden');
            }

            // Show loading overlay
            loadingOverlay.classList.remove('hidden');
            loadingOverlay.classList.add('flex');
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Verifying...';
            
            // Animate progress bar
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90;
                progressBar.style.width = progress + '%';
            }, 200);

            // Submit form
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: new URLSearchParams({
                    code: code,
                    _token: CSRF_TOKEN
                })
            })
            .then(response => {
                clearInterval(progressInterval);
                progressBar.style.width = '100%';

                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch {
                        // If not JSON, check if it's a redirect
                        if (response.redirected || response.status === 200) {
                            return { success: true, redirect: response.url || '{{ route("dashboard.index") }}' };
                        }
                        return { success: false, message: 'Verification failed' };
                    }
                });
            })
            .then(data => {
                // Wait a bit for animation
                setTimeout(() => {
                    if (data.success || data.redirect) {
                        // Success - redirect
                        window.location.href = data.redirect || '{{ route("dashboard.index") }}';
                    } else {
                        // Show error
                        showError(data.message || 'Invalid verification code. Please try again.');
                    }
                }, 500);
            })
            .catch(error => {
                clearInterval(progressInterval);
                showError('An error occurred. Please try again.');
            });
        }

        function showError(message) {
            // Hide loading overlay
            loadingOverlay.classList.add('hidden');
            loadingOverlay.classList.remove('flex');
            
            // Reset progress bar
            progressBar.style.width = '0%';
            
            // Show error message
            let errorDiv = document.querySelector('.bg-red-50');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'mb-4 p-4 bg-red-50 border border-red-200 rounded-lg';
                form.insertBefore(errorDiv, form.firstChild);
            }
            errorDiv.innerHTML = '<p class="text-sm text-red-600">' + message + '</p>';
            errorDiv.classList.remove('hidden');
            
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'Verify Code';
            
            // Clear and focus input
            codeInput.value = '';
            codeInput.focus();
        }
    });
    </script>
</body>
</html>

