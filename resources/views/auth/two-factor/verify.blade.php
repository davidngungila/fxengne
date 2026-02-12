<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verify Two-Factor Authentication - FxEngne</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased">
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
                <form action="{{ route('auth.two-factor.verify') }}" method="POST" class="space-y-6">
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
    // Auto-format code input
    document.getElementById('code').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    </script>
</body>
</html>

