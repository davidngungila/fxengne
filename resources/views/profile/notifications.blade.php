@extends('layouts.app')

@section('title', 'Notification Settings - FxEngne')
@section('page-title', 'Notification Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Notification Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Configure how you receive notifications</p>
    </div>

    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Notification Channels</h3>
        <div class="space-y-4">
            <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                <div>
                    <p class="font-medium text-gray-900">Email Notifications</p>
                    <p class="text-sm text-gray-600">Receive notifications via email</p>
                </div>
                <input type="checkbox" class="form-checkbox" checked>
            </label>
            <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                <div>
                    <p class="font-medium text-gray-900">SMS Alerts</p>
                    <p class="text-sm text-gray-600">Receive SMS alerts</p>
                </div>
                <input type="checkbox" class="form-checkbox">
            </label>
        </div>
    </div>
</div>
@endsection

