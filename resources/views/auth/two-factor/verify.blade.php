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
                    <div class="inline-flex items-center justify-center mb-4">
                        <img src="{{ asset('/logo.png') }}" alt="FXEngine Logo" class="h-20 w-20 object-contain" onerror="this.style.display='none'">
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
                    
                    <!-- Toggle between TOTP and Recovery Code -->
                    <div class="flex items-center justify-center space-x-4 mb-4">
                        <button 
                            type="button"
                            id="totpToggle"
                            class="px-4 py-2 rounded-lg font-medium transition-all flex-1 bg-blue-500 text-white shadow-md"
                        >
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Authenticator Code
                        </button>
                        <button 
                            type="button"
                            id="recoveryToggle"
                            class="px-4 py-2 rounded-lg font-medium transition-all flex-1 bg-gray-200 text-gray-700 hover:bg-gray-300"
                        >
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Recovery Code
                        </button>
                    </div>

                    <!-- TOTP Code Input -->
                    <div id="totpInputSection">
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
                        <p class="text-xs text-gray-500 text-center mt-2">Enter the 6-digit code from your authenticator app</p>
                    </div>

                    <!-- Recovery Code Input -->
                    <div id="recoveryInputSection" class="hidden">
                        <label for="recoveryCode" class="block text-sm font-medium text-gray-700 mb-2 text-center">
                            Recovery Code
                        </label>
                        <input 
                            type="text" 
                            id="recoveryCode" 
                            name="code" 
                            required 
                            maxlength="17"
                            class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-center text-xl font-mono tracking-wider uppercase"
                            placeholder="XXXX-XXXX-XXXX"
                            autocomplete="off"
                        >
                        <p class="text-xs text-gray-500 text-center mt-2">Enter one of your recovery codes (format: XXXX-XXXX-XXXX)</p>
                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-xs text-yellow-800">
                                <strong>Lost your recovery codes?</strong> Recovery codes are shown when you enable 2FA. If you don't have them, contact support.
                            </p>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-blue-600 hover:to-purple-700 transition-all transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Verify Code
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('code');
        const recoveryCodeInput = document.getElementById('recoveryCode');
        const form = document.getElementById('verifyForm');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const progressBar = document.getElementById('progressBar');
        const submitBtn = document.getElementById('submitBtn');
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        
        const totpToggle = document.getElementById('totpToggle');
        const recoveryToggle = document.getElementById('recoveryToggle');
        const totpInputSection = document.getElementById('totpInputSection');
        const recoveryInputSection = document.getElementById('recoveryInputSection');
        
        let isRecoveryMode = false;

        // Toggle between TOTP and Recovery Code
        totpToggle.addEventListener('click', function() {
            isRecoveryMode = false;
            totpToggle.classList.remove('bg-gray-200', 'text-gray-700');
            totpToggle.classList.add('bg-blue-500', 'text-white', 'shadow-md');
            recoveryToggle.classList.remove('bg-blue-500', 'text-white', 'shadow-md');
            recoveryToggle.classList.add('bg-gray-200', 'text-gray-700');
            totpInputSection.classList.remove('hidden');
            recoveryInputSection.classList.add('hidden');
            codeInput.required = true;
            recoveryCodeInput.required = false;
            recoveryCodeInput.value = '';
            codeInput.focus();
        });

        recoveryToggle.addEventListener('click', function() {
            isRecoveryMode = true;
            recoveryToggle.classList.remove('bg-gray-200', 'text-gray-700');
            recoveryToggle.classList.add('bg-blue-500', 'text-white', 'shadow-md');
            totpToggle.classList.remove('bg-blue-500', 'text-white', 'shadow-md');
            totpToggle.classList.add('bg-gray-200', 'text-gray-700');
            recoveryInputSection.classList.remove('hidden');
            totpInputSection.classList.add('hidden');
            recoveryCodeInput.required = true;
            codeInput.required = false;
            codeInput.value = '';
            recoveryCodeInput.focus();
        });

        // Auto-format TOTP code input (numbers only, max 6 digits)
        codeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
            
            // Auto-submit when 6 digits are entered
            if (this.value.length === 6 && !isRecoveryMode) {
                submitForm();
            }
        });

        // Auto-format recovery code input (alphanumeric, auto-add dash)
        recoveryCodeInput.addEventListener('input', function(e) {
            // Remove all non-alphanumeric characters
            let value = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
            
            // Add dash after 8 characters
            if (value.length > 8) {
                value = value.substring(0, 8) + '-' + value.substring(8, 16);
            }
            
            // Limit to 17 characters (8-8-1 for dash)
            value = value.substring(0, 17);
            
            this.value = value;
        });

        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm();
        });

        function submitForm() {
            const code = isRecoveryMode 
                ? recoveryCodeInput.value.trim().toUpperCase().replace(/[^A-Z0-9]/g, '')
                : codeInput.value.trim();
            
            // Validate input
            if (isRecoveryMode) {
                // Recovery code should be 16 characters (8-8 format)
                if (code.length !== 16) {
                    showError('Recovery code must be 16 characters (format: XXXX-XXXX-XXXX)');
                    return;
                }
            } else {
                // TOTP code should be 6 digits
                if (code.length !== 6) {
                    return;
                }
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

            // Format recovery code with dash if needed
            let formattedCode = code;
            if (isRecoveryMode && code.length === 16) {
                formattedCode = code.substring(0, 8) + '-' + code.substring(8, 16);
            }

            // Submit form
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: new URLSearchParams({
                    code: formattedCode,
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
                        const errorMsg = isRecoveryMode 
                            ? 'Invalid recovery code. Please check and try again.'
                            : 'Invalid verification code. Please try again.';
                        showError(data.message || errorMsg);
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
            if (isRecoveryMode) {
                recoveryCodeInput.value = '';
                recoveryCodeInput.focus();
            } else {
                codeInput.value = '';
                codeInput.focus();
            }
        }
    });
    </script>
</body>
</html>


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
                    <div class="inline-flex items-center justify-center mb-4">
                        <img src="{{ asset('/logo.png') }}" alt="FXEngine Logo" class="h-20 w-20 object-contain" onerror="this.style.display='none'">
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
                    
                    <!-- Toggle between TOTP and Recovery Code -->
                    <div class="flex items-center justify-center space-x-4 mb-4">
                        <button 
                            type="button"
                            id="totpToggle"
                            class="px-4 py-2 rounded-lg font-medium transition-all flex-1 bg-blue-500 text-white shadow-md"
                        >
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Authenticator Code
                        </button>
                        <button 
                            type="button"
                            id="recoveryToggle"
                            class="px-4 py-2 rounded-lg font-medium transition-all flex-1 bg-gray-200 text-gray-700 hover:bg-gray-300"
                        >
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Recovery Code
                        </button>
                    </div>

                    <!-- TOTP Code Input -->
                    <div id="totpInputSection">
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
                        <p class="text-xs text-gray-500 text-center mt-2">Enter the 6-digit code from your authenticator app</p>
                    </div>

                    <!-- Recovery Code Input -->
                    <div id="recoveryInputSection" class="hidden">
                        <label for="recoveryCode" class="block text-sm font-medium text-gray-700 mb-2 text-center">
                            Recovery Code
                        </label>
                        <input 
                            type="text" 
                            id="recoveryCode" 
                            name="code" 
                            required 
                            maxlength="17"
                            class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-center text-xl font-mono tracking-wider uppercase"
                            placeholder="XXXX-XXXX-XXXX"
                            autocomplete="off"
                        >
                        <p class="text-xs text-gray-500 text-center mt-2">Enter one of your recovery codes (format: XXXX-XXXX-XXXX)</p>
                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-xs text-yellow-800">
                                <strong>Lost your recovery codes?</strong> Recovery codes are shown when you enable 2FA. If you don't have them, contact support.
                            </p>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-blue-600 hover:to-purple-700 transition-all transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Verify Code
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('code');
        const recoveryCodeInput = document.getElementById('recoveryCode');
        const form = document.getElementById('verifyForm');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const progressBar = document.getElementById('progressBar');
        const submitBtn = document.getElementById('submitBtn');
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        
        const totpToggle = document.getElementById('totpToggle');
        const recoveryToggle = document.getElementById('recoveryToggle');
        const totpInputSection = document.getElementById('totpInputSection');
        const recoveryInputSection = document.getElementById('recoveryInputSection');
        
        let isRecoveryMode = false;

        // Toggle between TOTP and Recovery Code
        totpToggle.addEventListener('click', function() {
            isRecoveryMode = false;
            totpToggle.classList.remove('bg-gray-200', 'text-gray-700');
            totpToggle.classList.add('bg-blue-500', 'text-white', 'shadow-md');
            recoveryToggle.classList.remove('bg-blue-500', 'text-white', 'shadow-md');
            recoveryToggle.classList.add('bg-gray-200', 'text-gray-700');
            totpInputSection.classList.remove('hidden');
            recoveryInputSection.classList.add('hidden');
            codeInput.required = true;
            recoveryCodeInput.required = false;
            recoveryCodeInput.value = '';
            codeInput.focus();
        });

        recoveryToggle.addEventListener('click', function() {
            isRecoveryMode = true;
            recoveryToggle.classList.remove('bg-gray-200', 'text-gray-700');
            recoveryToggle.classList.add('bg-blue-500', 'text-white', 'shadow-md');
            totpToggle.classList.remove('bg-blue-500', 'text-white', 'shadow-md');
            totpToggle.classList.add('bg-gray-200', 'text-gray-700');
            recoveryInputSection.classList.remove('hidden');
            totpInputSection.classList.add('hidden');
            recoveryCodeInput.required = true;
            codeInput.required = false;
            codeInput.value = '';
            recoveryCodeInput.focus();
        });

        // Auto-format TOTP code input (numbers only, max 6 digits)
        codeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
            
            // Auto-submit when 6 digits are entered
            if (this.value.length === 6 && !isRecoveryMode) {
                submitForm();
            }
        });

        // Auto-format recovery code input (alphanumeric, auto-add dash)
        recoveryCodeInput.addEventListener('input', function(e) {
            // Remove all non-alphanumeric characters
            let value = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
            
            // Add dash after 8 characters
            if (value.length > 8) {
                value = value.substring(0, 8) + '-' + value.substring(8, 16);
            }
            
            // Limit to 17 characters (8-8-1 for dash)
            value = value.substring(0, 17);
            
            this.value = value;
        });

        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm();
        });

        function submitForm() {
            const code = isRecoveryMode 
                ? recoveryCodeInput.value.trim().toUpperCase().replace(/[^A-Z0-9]/g, '')
                : codeInput.value.trim();
            
            // Validate input
            if (isRecoveryMode) {
                // Recovery code should be 16 characters (8-8 format)
                if (code.length !== 16) {
                    showError('Recovery code must be 16 characters (format: XXXX-XXXX-XXXX)');
                    return;
                }
            } else {
                // TOTP code should be 6 digits
                if (code.length !== 6) {
                    return;
                }
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

            // Format recovery code with dash if needed
            let formattedCode = code;
            if (isRecoveryMode && code.length === 16) {
                formattedCode = code.substring(0, 8) + '-' + code.substring(8, 16);
            }

            // Submit form
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: new URLSearchParams({
                    code: formattedCode,
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
                        const errorMsg = isRecoveryMode 
                            ? 'Invalid recovery code. Please check and try again.'
                            : 'Invalid verification code. Please try again.';
                        showError(data.message || errorMsg);
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
            if (isRecoveryMode) {
                recoveryCodeInput.value = '';
                recoveryCodeInput.focus();
            } else {
                codeInput.value = '';
                codeInput.focus();
            }
        }
    });
    </script>
</body>
</html>

