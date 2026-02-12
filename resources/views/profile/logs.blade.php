@extends('layouts.app')

@section('title', 'Activity Logs - FxEngne')
@section('page-title', 'Activity Logs')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Activity Logs</h2>
        <p class="text-sm text-gray-600 mt-1">View your account activity history</p>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                        <th>IP Address</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="text-sm text-gray-600">2024-01-15 10:30</td>
                        <td class="font-medium">Login</td>
                        <td class="text-sm text-gray-600">192.168.1.1</td>
                        <td><span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Success</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

