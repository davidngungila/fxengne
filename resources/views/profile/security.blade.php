@extends('layouts.app')

@section('title', 'Security Settings - FxEngne')
@section('page-title', 'Security Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Security Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Manage your account security</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
            <form class="space-y-4">
                <div>
                    <label class="form-label">Current Password</label>
                    <input type="password" class="form-input">
                </div>
                <div>
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-input">
                </div>
                <div>
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-input">
                </div>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Two-Factor Authentication</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">2FA Status</p>
                        <p class="text-sm text-gray-600">Not enabled</p>
                    </div>
                    <button class="btn btn-secondary">Enable</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

