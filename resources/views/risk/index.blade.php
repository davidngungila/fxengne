@extends('layouts.app')

@section('title', 'Risk Management - FxEngne')
@section('page-title', 'Risk Management')

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Risk Management</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Risk Per Trade</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">1% of account balance</p>
        </div>
        
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Daily Limits</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Max daily loss: 5%</p>
        </div>
    </div>
</div>
@endsection

