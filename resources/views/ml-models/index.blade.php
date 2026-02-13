@extends('layouts.app')

@section('title', 'ML Models - FXEngine')
@section('page-title', 'ML Models')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Machine Learning Models</h2>
            <p class="text-sm text-gray-600 mt-1">Train and deploy AI models for trading signals</p>
        </div>
        <a href="{{ route('ml-models.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create New Model
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Models</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Models</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['active'] }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Trained Models</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1">{{ $stats['trained'] }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card hover:shadow-lg transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Training</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $stats['training'] }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Model Types Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('ml-models.create') }}?type=price_direction" class="card hover:shadow-xl transition-all transform hover:-translate-y-2 border-2 border-transparent hover:border-blue-200">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-lg mb-2">Price Direction</h3>
                <p class="text-sm text-gray-600 mb-3">Predict BUY/SELL signals</p>
                <p class="text-xs text-gray-500">Recommended: TFT</p>
            </div>
        </a>

        <a href="{{ route('ml-models.create') }}?type=volatility" class="card hover:shadow-xl transition-all transform hover:-translate-y-2 border-2 border-transparent hover:border-green-200">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-lg mb-2">Volatility</h3>
                <p class="text-sm text-gray-600 mb-3">Forecast price movement</p>
                <p class="text-xs text-gray-500">Recommended: XGBoost</p>
            </div>
        </a>

        <a href="{{ route('ml-models.create') }}?type=sentiment" class="card hover:shadow-xl transition-all transform hover:-translate-y-2 border-2 border-transparent hover:border-purple-200">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-lg mb-2">Sentiment</h3>
                <p class="text-sm text-gray-600 mb-3">Analyze news sentiment</p>
                <p class="text-xs text-gray-500">Recommended: FinBERT</p>
            </div>
        </a>

        <a href="{{ route('ml-models.create') }}?type=parameter_optimization" class="card hover:shadow-xl transition-all transform hover:-translate-y-2 border-2 border-transparent hover:border-yellow-200">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-lg mb-2">Optimization</h3>
                <p class="text-sm text-gray-600 mb-3">Optimize parameters</p>
                <p class="text-xs text-gray-500">Recommended: Grid Search</p>
            </div>
        </a>
    </div>

    <!-- Models List -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Your Models</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Architecture</th>
                        <th>Status</th>
                        <th>Accuracy</th>
                        <th>Predictions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($models as $model)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td>
                            <div class="font-medium text-gray-900">{{ $model->name }}</div>
                            @if($model->is_active)
                            <span class="text-xs text-green-600 font-medium">● Active</span>
                            @endif
                        </td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst(str_replace('_', ' ', $model->type)) }}
                            </span>
                        </td>
                        <td class="text-sm text-gray-600">{{ $model->architecture }}</td>
                        <td>
                            @if($model->status === 'trained')
                                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Trained</span>
                            @elseif($model->status === 'training')
                                <span class="px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Training</span>
                            @elseif($model->status === 'active')
                                <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">Active</span>
                            @else
                                <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($model->status) }}</span>
                            @endif
                        </td>
                        <td class="text-sm">
                            @if($model->accuracy)
                                <span class="font-semibold {{ $model->accuracy >= 0.7 ? 'text-green-600' : ($model->accuracy >= 0.55 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($model->accuracy * 100, 1) }}%
                                </span>
                            @else
                                <span class="text-gray-400">--</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-600">{{ number_format($model->total_predictions) }}</td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('ml-models.show', $model) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View</a>
                                @if($model->isReadyForDeployment())
                                <form action="{{ route('ml-models.toggle-active', $model) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm {{ $model->is_active ? 'text-red-600 hover:text-red-700' : 'text-green-600 hover:text-green-700' }}">
                                        {{ $model->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                            </svg>
                            <p>No models created yet</p>
                            <a href="{{ route('ml-models.create') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-2 inline-block">Create your first model</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


