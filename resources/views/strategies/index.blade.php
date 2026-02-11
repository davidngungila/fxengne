@extends('layouts.app')

@section('title', 'Strategies - FxEngne')
@section('page-title', 'Strategies')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Trading Strategies</h2>
        <a href="{{ route('strategies.create') }}" class="btn btn-primary">Create Strategy</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">MA Crossover</h3>
                <span class="badge badge-success">Active</span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">EURUSD, GBPUSD</p>
            <div class="flex space-x-2">
                <button class="btn btn-secondary text-sm">Edit</button>
                <button class="btn btn-danger text-sm">Pause</button>
            </div>
        </div>
    </div>
</div>
@endsection

