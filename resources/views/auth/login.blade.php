<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - FxEngne</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-purple-50">
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
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Setting up your trading experience...</h2>
                
                <!-- Secondary Text -->
                <p class="text-blue-600 text-sm mb-6">Curating the best tools for you</p>
                
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

        <!-- Login Form -->
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl mb-4">
                        <span class="text-white font-bold text-2xl">FX</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">FxEngne</h1>
                    <p class="text-gray-600 mt-2">Sign in to your trading account</p>
                </div>

                <!-- Error Messages -->
                <div id="errorMessage" class="hidden mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-600"></p>
                </div>

                <!-- Login Form -->
                <form id="loginForm" class="space-y-6">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required 
                            autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="you@example.com"
                        >
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="••••••••"
                        >
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-700">Forgot password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-blue-600 hover:to-purple-700 transition-all transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Sign In
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account? 
                        <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Sign up</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const progressBar = document.getElementById('progressBar');
        const errorMessage = document.getElementById('errorMessage');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Hide error message
            errorMessage.classList.add('hidden');
            
            // Show loading overlay
            loadingOverlay.classList.remove('hidden');
            loadingOverlay.classList.add('flex');
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Signing in...';
            
            // Animate progress bar
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90;
                progressBar.style.width = progress + '%';
            }, 200);

            try {
                const formData = new FormData(form);
                const response = await fetch('{{ route("login") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                // Complete progress bar
                clearInterval(progressInterval);
                progressBar.style.width = '100%';

                // Wait a bit for animation
                await new Promise(resolve => setTimeout(resolve, 500));

                if (response.ok && data.success) {
                    // Success - redirect
                    window.location.href = data.redirect || '{{ route("dashboard.index") }}';
                } else {
                    // Show error
                    throw new Error(data.message || 'Login failed');
                }
            } catch (error) {
                // Hide loading overlay
                loadingOverlay.classList.add('hidden');
                loadingOverlay.classList.remove('flex');
                
                // Reset progress bar
                progressBar.style.width = '0%';
                clearInterval(progressInterval);
                
                // Show error message
                const errorText = error.message || 'Invalid credentials. Please try again.';
                errorMessage.querySelector('p').textContent = errorText;
                errorMessage.classList.remove('hidden');
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Sign In';
            }
        });
    });
    </script>
</body>
</html>

