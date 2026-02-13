@extends('layouts.app')

@section('title', 'Historical Data - FXEngine')
@section('page-title', 'Historical Data')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Historical Data</h2>
        <p class="text-sm text-gray-600 mt-1">Manage historical market data for backtesting</p>
    </div>

    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Available Data</h3>
            <button class="btn btn-primary">Download Data</button>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Instrument</th>
                        <th>Timeframe</th>
                        <th>Date Range</th>
                        <th>Records</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">EUR/USD</td>
                        <td>H1</td>
                        <td class="text-sm text-gray-600">2020-01-01 to 2024-01-31</td>
                        <td>35,040</td>
                        <td><span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Available</span></td>
                        <td><button class="text-blue-600 hover:text-blue-700 text-sm">View</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



