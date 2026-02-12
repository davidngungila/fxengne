@extends('layouts.app')

@section('title', 'Weekly Review - FXEngine')
@section('page-title', 'Weekly Review')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Weekly Review</h2>
        <p class="text-sm text-gray-600 mt-1">Review your weekly trading performance</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <p class="text-sm text-gray-600">Total Trades</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">18</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Win Rate</p>
            <p class="text-2xl font-bold text-green-600 mt-1">72.2%</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Total P/L</p>
            <p class="text-2xl font-bold text-green-600 mt-1">+$450</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Best Day</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">Monday</p>
        </div>
    </div>

    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Weekly Summary</h3>
        <div class="space-y-3">
            <div>
                <p class="font-medium text-gray-900">What went well:</p>
                <p class="text-sm text-gray-600">Good discipline in following trading plan</p>
            </div>
            <div>
                <p class="font-medium text-gray-900">Areas for improvement:</p>
                <p class="text-sm text-gray-600">Need to reduce overtrading on Fridays</p>
            </div>
        </div>
    </div>
</div>
@endsection

