@extends('layouts.app')

@section('title', 'Security Settings - FxEngne')
@section('page-title', 'Security Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Security Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Manage your account security</p>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
        <p class="text-sm text-green-600">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-sm text-red-600">{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Change Password -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
            <form action="{{ route('profile.change-password') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-input" required>
                    @error('current_password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-input" required minlength="8">
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Two-Factor Authentication</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">2FA Status</p>
                        <p class="text-sm {{ auth()->user()->hasTwoFactorEnabled() ? 'text-green-600' : 'text-gray-600' }}">
                            @if(auth()->user()->hasTwoFactorEnabled())
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Enabled
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
                    @if(auth()->user()->hasTwoFactorEnabled())
                        <div class="flex space-x-2">
                            <a href="{{ route('auth.two-factor.recovery-codes') }}" class="btn btn-secondary text-sm">
                                View Recovery Codes
                            </a>
                        </div>
                    @endif
                </div>

                @if(auth()->user()->hasTwoFactorEnabled())
                    <!-- Disable 2FA -->
                    <div class="border-t border-gray-200 pt-4">
                        <details class="group">
                            <summary class="cursor-pointer text-sm font-medium text-red-600 hover:text-red-700 mb-3">
                                Disable Two-Factor Authentication
                            </summary>
                            <form action="{{ route('auth.two-factor.disable') }}" method="POST" class="mt-3 space-y-3">
                                @csrf
                                <div>
                                    <label class="form-label">Enter your password to confirm</label>
                                    <input type="password" name="password" class="form-input" required>
                                    @error('password')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-danger text-sm" onclick="return confirm('Are you sure you want to disable 2FA? This will make your account less secure.')">
                                    Disable 2FA
                                </button>
                            </form>
                        </details>
                    </div>
                @else
                    <!-- Enable 2FA -->
                    <a href="{{ route('auth.two-factor.setup') }}" class="btn btn-primary w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Enable Two-Factor Authentication
                    </a>
                @endif

                <!-- Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <p class="text-xs text-blue-800">
                        <strong>What is 2FA?</strong> Two-factor authentication adds an extra layer of security to your account. 
                        After enabling, you'll need both your password and a code from your authenticator app to sign in.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
