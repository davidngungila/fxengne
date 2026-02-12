@extends('layouts.app')

@section('title', 'Security Settings - FxEngne')
@section('page-title', 'Security Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Security Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Manage your account security and privacy settings</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Security Score Overview -->
    <div class="card bg-gradient-to-br from-blue-50 to-purple-50 border-blue-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Security Score</h3>
                <p class="text-sm text-gray-600 mt-1">Your account security strength</p>
            </div>
            <div class="text-right">
                <div class="text-4xl font-bold text-blue-600" id="securityScore">85%</div>
                <p class="text-xs text-gray-600 mt-1">Good</p>
            </div>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-500" style="width: 85%" id="securityScoreBar"></div>
        </div>
        <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 {{ auth()->user()->hasTwoFactorEnabled() ? 'text-green-500' : 'text-gray-400' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span class="{{ auth()->user()->hasTwoFactorEnabled() ? 'text-green-700 font-medium' : 'text-gray-600' }}">
                    {{ auth()->user()->hasTwoFactorEnabled() ? '2FA Enabled' : '2FA Disabled' }}
                </span>
            </div>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span class="text-green-700 font-medium">Strong Password</span>
            </div>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span class="text-green-700 font-medium">Email Verified</span>
            </div>
        </div>
    </div>

    <!-- Main Security Settings -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Change Password -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Change Password</h3>
                <div class="flex items-center space-x-2 text-xs text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Last changed: {{ auth()->user()->updated_at->diffForHumans() }}</span>
                </div>
            </div>
            <form id="passwordForm" action="{{ route('profile.change-password') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Current Password</label>
                    <div class="relative">
                        <input type="password" name="current_password" id="currentPassword" class="form-input pr-10" required>
                        <button type="button" onclick="togglePassword('currentPassword')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">New Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="newPassword" class="form-input pr-10" required minlength="8" oninput="checkPasswordStrength(this.value)">
                        <button type="button" onclick="togglePassword('newPassword')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div id="passwordStrength" class="mt-2 hidden">
                        <div class="flex items-center space-x-2 mb-1">
                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div id="strengthBar" class="h-full transition-all duration-300"></div>
                            </div>
                            <span id="strengthText" class="text-xs font-medium"></span>
                        </div>
                        <div id="passwordRequirements" class="text-xs text-gray-600 space-y-1"></div>
                    </div>
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Confirm Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="confirmPassword" class="form-input pr-10" required oninput="checkPasswordMatch()">
                        <button type="button" onclick="togglePassword('confirmPassword')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="passwordMatch" class="mt-1 text-xs"></div>
                </div>
                <button type="submit" class="btn btn-primary w-full">Update Password</button>
            </form>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Two-Factor Authentication</h3>
                @if(auth()->user()->hasTwoFactorEnabled())
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">Inactive</span>
                @endif
            </div>
            <div class="space-y-4">
                <div class="p-4 border-2 {{ auth()->user()->hasTwoFactorEnabled() ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50' }} rounded-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <svg class="w-6 h-6 {{ auth()->user()->hasTwoFactorEnabled() ? 'text-green-600' : 'text-gray-400' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <p class="font-semibold text-gray-900">2FA Status</p>
                            </div>
                            <p class="text-sm {{ auth()->user()->hasTwoFactorEnabled() ? 'text-green-700' : 'text-gray-600' }} mb-1">
                                @if(auth()->user()->hasTwoFactorEnabled())
                                    <span class="inline-flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                        Enabled and Active
                                    </span>
                                @else
                                    Not enabled
                                @endif
                            </p>
                            @if(auth()->user()->hasTwoFactorEnabled())
                                <p class="text-xs text-gray-500 mt-1">
                                    Enabled on {{ auth()->user()->two_factor_confirmed_at->format('M d, Y') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                @if(auth()->user()->hasTwoFactorEnabled())
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('auth.two-factor.recovery-codes') }}" class="btn btn-secondary flex-1">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            View Recovery Codes
                        </a>
                        <a href="{{ route('auth.two-factor.recovery-codes') }}" class="btn btn-secondary flex-1">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Regenerate Codes
                        </a>
                    </div>
                    
                    <!-- Disable 2FA -->
                    <details class="group">
                        <summary class="cursor-pointer p-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-red-700">Disable Two-Factor Authentication</span>
                                <svg class="w-5 h-5 text-red-600 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </summary>
                        <form action="{{ route('auth.two-factor.disable') }}" method="POST" class="mt-3 space-y-3">
                            @csrf
                            <div>
                                <label class="form-label text-sm">Enter your password to confirm</label>
                                <input type="password" name="password" class="form-input" required>
                                @error('password')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-danger text-sm w-full" onclick="return confirm('Are you sure you want to disable 2FA? This will make your account less secure.')">
                                Disable 2FA
                            </button>
                        </form>
                    </details>
                @else
                    <a href="{{ route('auth.two-factor.setup') }}" class="btn btn-primary w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Enable Two-Factor Authentication
                    </a>
                @endif

                <!-- Info -->
                <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-900 mb-1">What is 2FA?</p>
                            <p class="text-xs text-blue-800">
                                Two-factor authentication adds an extra layer of security to your account. 
                                After enabling, you'll need both your password and a code from your authenticator app to sign in.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Sessions -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Active Sessions</h3>
            <button class="text-sm text-red-600 hover:text-red-700 font-medium">End All Other Sessions</button>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg bg-green-50">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Current Session</p>
                        <p class="text-sm text-gray-600">{{ request()->ip() }} • {{ request()->userAgent() }}</p>
                        <p class="text-xs text-gray-500 mt-1">Active now</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Current</span>
            </div>
            
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Windows • Chrome</p>
                        <p class="text-sm text-gray-600">192.168.1.100 • United States</p>
                        <p class="text-xs text-gray-500 mt-1">Last active: 2 hours ago</p>
                    </div>
                </div>
                <button class="text-sm text-red-600 hover:text-red-700 font-medium">Revoke</button>
            </div>
        </div>
    </div>

    <!-- Login History -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Login Activity</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Device</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Just now</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ request()->ip() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">United States</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Windows • Chrome</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Success</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2 hours ago</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">192.168.1.100</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">United States</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Windows • Chrome</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Success</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Yesterday</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">192.168.1.100</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">United States</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Mobile • Safari</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Success</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3 days ago</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">203.0.113.45</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Unknown</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Unknown</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Failed</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Security Recommendations -->
    <div class="card bg-yellow-50 border-yellow-200">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-yellow-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-yellow-900 mb-2">Security Recommendations</h3>
                <ul class="space-y-2 text-sm text-yellow-800">
                    @if(!auth()->user()->hasTwoFactorEnabled())
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Enable two-factor authentication to add an extra layer of security</span>
                    </li>
                    @endif
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Use a unique, strong password that you don't use elsewhere</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Review your active sessions regularly and revoke access from unknown devices</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Keep your recovery codes in a safe place</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
}

function checkPasswordStrength(password) {
    const strengthDiv = document.getElementById('passwordStrength');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const requirementsDiv = document.getElementById('passwordRequirements');
    
    if (password.length === 0) {
        strengthDiv.classList.add('hidden');
        return;
    }
    
    strengthDiv.classList.remove('hidden');
    
    let strength = 0;
    let requirements = [];
    
    // Length check
    if (password.length >= 8) {
        strength += 1;
        requirements.push('<span class="text-green-600">✓ At least 8 characters</span>');
    } else {
        requirements.push('<span class="text-gray-500">✗ At least 8 characters</span>');
    }
    
    // Uppercase check
    if (/[A-Z]/.test(password)) {
        strength += 1;
        requirements.push('<span class="text-green-600">✓ Contains uppercase letter</span>');
    } else {
        requirements.push('<span class="text-gray-500">✗ Contains uppercase letter</span>');
    }
    
    // Lowercase check
    if (/[a-z]/.test(password)) {
        strength += 1;
        requirements.push('<span class="text-green-600">✓ Contains lowercase letter</span>');
    } else {
        requirements.push('<span class="text-gray-500">✗ Contains lowercase letter</span>');
    }
    
    // Number check
    if (/[0-9]/.test(password)) {
        strength += 1;
        requirements.push('<span class="text-green-600">✓ Contains number</span>');
    } else {
        requirements.push('<span class="text-gray-500">✗ Contains number</span>');
    }
    
    // Special character check
    if (/[^A-Za-z0-9]/.test(password)) {
        strength += 1;
        requirements.push('<span class="text-green-600">✓ Contains special character</span>');
    } else {
        requirements.push('<span class="text-gray-500">✗ Contains special character</span>');
    }
    
    // Update strength bar
    const percentage = (strength / 5) * 100;
    strengthBar.style.width = percentage + '%';
    
    let color, text;
    if (strength <= 2) {
        color = 'bg-red-500';
        text = 'Weak';
    } else if (strength <= 3) {
        color = 'bg-yellow-500';
        text = 'Fair';
    } else if (strength <= 4) {
        color = 'bg-blue-500';
        text = 'Good';
    } else {
        color = 'bg-green-500';
        text = 'Strong';
    }
    
    strengthBar.className = 'h-full transition-all duration-300 ' + color;
    strengthText.textContent = text;
    strengthText.className = 'text-xs font-medium ' + (strength <= 2 ? 'text-red-600' : strength <= 3 ? 'text-yellow-600' : strength <= 4 ? 'text-blue-600' : 'text-green-600');
    
    requirementsDiv.innerHTML = requirements.join('');
}

function checkPasswordMatch() {
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const matchDiv = document.getElementById('passwordMatch');
    
    if (confirmPassword.length === 0) {
        matchDiv.textContent = '';
        return;
    }
    
    if (newPassword === confirmPassword) {
        matchDiv.innerHTML = '<span class="text-green-600">✓ Passwords match</span>';
    } else {
        matchDiv.innerHTML = '<span class="text-red-600">✗ Passwords do not match</span>';
    }
}

// Calculate security score
document.addEventListener('DOMContentLoaded', function() {
    let score = 30; // Base score
    
    // Email verified
    score += 20;
    
    // Strong password (assumed)
    score += 20;
    
    // 2FA enabled
    if ({{ auth()->user()->hasTwoFactorEnabled() ? 'true' : 'false' }}) {
        score += 30;
    }
    
    // Update security score
    const scoreElement = document.getElementById('securityScore');
    const scoreBar = document.getElementById('securityScoreBar');
    
    if (scoreElement && scoreBar) {
        scoreElement.textContent = score + '%';
        scoreBar.style.width = score + '%';
        
        // Update color based on score
        if (score >= 80) {
            scoreBar.className = 'bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full transition-all duration-500';
            scoreElement.className = 'text-4xl font-bold text-green-600';
        } else if (score >= 60) {
            scoreBar.className = 'bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500';
            scoreElement.className = 'text-4xl font-bold text-blue-600';
        } else if (score >= 40) {
            scoreBar.className = 'bg-gradient-to-r from-yellow-500 to-yellow-600 h-3 rounded-full transition-all duration-500';
            scoreElement.className = 'text-4xl font-bold text-yellow-600';
        } else {
            scoreBar.className = 'bg-gradient-to-r from-red-500 to-red-600 h-3 rounded-full transition-all duration-500';
            scoreElement.className = 'text-4xl font-bold text-red-600';
        }
    }
});
</script>
@endpush
@endsection
