@extends('layouts.app')

@section('title', 'Trading Mistakes - FXEngine')
@section('page-title', 'Trading Mistakes')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Trading Mistakes</h2>
        <p class="text-sm text-gray-600 mt-1">Learn from past mistakes</p>
    </div>

    <div class="card">
        <div class="space-y-4">
            <div class="p-4 border border-red-200 rounded-lg bg-red-50">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900">Overtrading</h3>
                        <p class="text-sm text-gray-600 mt-1">Took too many trades in a single day</p>
                        <p class="text-xs text-gray-500 mt-2">Date: 2024-01-10</p>
                    </div>
                    <span class="px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">High</span>
                </div>
            </div>
            <div class="p-4 border border-yellow-200 rounded-lg bg-yellow-50">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900">No Stop Loss</h3>
                        <p class="text-sm text-gray-600 mt-1">Entered trade without stop loss</p>
                        <p class="text-xs text-gray-500 mt-2">Date: 2024-01-08</p>
                    </div>
                    <span class="px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Medium</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




