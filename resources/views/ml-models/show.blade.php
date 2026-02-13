@extends('layouts.app')

@section('title', $model->name . ' - FXEngine')
@section('page-title', 'ML Model Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $model->name }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ $model->description ?? 'No description' }}</p>
        </div>
        <div class="flex items-center space-x-3">
            @if($model->isReadyForDeployment())
            <form action="{{ route('ml-models.toggle-active', $model) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn {{ $model->is_active ? 'btn-secondary' : 'btn-primary' }}">
                    {{ $model->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
            @endif
            @if($model->status === 'draft' || $model->status === 'trained')
            <form action="{{ route('ml-models.train', $model) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    {{ $model->status === 'trained' ? 'Retrain' : 'Start Training' }}
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Model Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="text-lg font-bold mt-1">
                        @if($model->status === 'trained')
                            <span class="text-green-600">Trained</span>
                        @elseif($model->status === 'training')
                            <span class="text-yellow-600">Training</span>
                        @else
                            <span class="text-gray-600">{{ ucfirst($model->status) }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Accuracy</p>
                    <p class="text-lg font-bold mt-1 {{ $model->accuracy >= 0.7 ? 'text-green-600' : ($model->accuracy >= 0.55 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ $model->accuracy ? number_format($model->accuracy * 100, 1) . '%' : '--' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Predictions</p>
                    <p class="text-lg font-bold mt-1">{{ number_format($model->total_predictions) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Success: {{ number_format($statistics['success_rate'] ?? 0, 1) }}%</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Avg Confidence</p>
                    <p class="text-lg font-bold mt-1">{{ $statistics['average_confidence'] ? number_format($statistics['average_confidence'] * 100, 1) . '%' : '--' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Model Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Configuration -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Configuration</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Type:</span>
                    <span class="text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $model->type)) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Architecture:</span>
                    <span class="text-sm font-medium">{{ $model->architecture }}</span>
                </div>
                @if($model->config)
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Instrument:</span>
                    <span class="text-sm font-medium">{{ $model->config['instrument'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Timeframe:</span>
                    <span class="text-sm font-medium">{{ $model->config['timeframe'] ?? 'N/A' }}</span>
                </div>
                @endif
                @if($model->trained_at)
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Trained:</span>
                    <span class="text-sm font-medium">{{ $model->trained_at->format('M d, Y H:i') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Recommendations -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recommendations</h3>
            @if($recommendations)
            <div class="space-y-3">
                <div>
                    <p class="text-sm font-medium text-gray-900">Best Architecture:</p>
                    <p class="text-sm text-gray-600">{{ $recommendations['best'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Description:</p>
                    <p class="text-sm text-gray-600">{{ $recommendations['description'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Data Required:</p>
                    <p class="text-sm text-gray-600">{{ $recommendations['data_required'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Training Time:</p>
                    <p class="text-sm text-gray-600">{{ $recommendations['training_time'] ?? 'N/A' }}</p>
                </div>
            </div>
            @else
            <p class="text-sm text-gray-500">No recommendations available</p>
            @endif
        </div>
    </div>

    <!-- Performance Metrics -->
    @if($model->performance_metrics)
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Metrics</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @if($model->precision)
            <div>
                <p class="text-sm text-gray-600">Precision</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($model->precision * 100, 1) }}%</p>
            </div>
            @endif
            @if($model->recall)
            <div>
                <p class="text-sm text-gray-600">Recall</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($model->recall * 100, 1) }}%</p>
            </div>
            @endif
            @if($model->f1_score)
            <div>
                <p class="text-sm text-gray-600">F1 Score</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($model->f1_score * 100, 1) }}%</p>
            </div>
            @endif
            @if($model->sharpe_ratio)
            <div>
                <p class="text-sm text-gray-600">Sharpe Ratio</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($model->sharpe_ratio, 2) }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Recent Predictions -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent Predictions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Instrument</th>
                        <th>Prediction</th>
                        <th>Confidence</th>
                        <th>Actual</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPredictions as $prediction)
                    <tr class="hover:bg-gray-50">
                        <td class="text-sm text-gray-600">{{ $prediction->predicted_at->format('M d, H:i') }}</td>
                        <td class="font-medium">{{ $prediction->instrument }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $prediction->prediction === 'BUY' ? 'bg-green-100 text-green-800' : ($prediction->prediction === 'SELL' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $prediction->prediction ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="text-sm">{{ $prediction->confidence ? number_format($prediction->confidence * 100, 1) . '%' : '--' }}</td>
                        <td class="text-sm">{{ $prediction->actual_price ? number_format($prediction->actual_price, 5) : '--' }}</td>
                        <td>
                            @if($prediction->was_correct !== null)
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $prediction->was_correct ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $prediction->was_correct ? 'Correct' : 'Incorrect' }}
                                </span>
                            @else
                                <span class="text-gray-400">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">No predictions yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Training Logs -->
    @if($trainingLogs->count() > 0)
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Training Logs</h3>
        <div class="space-y-3">
            @foreach($trainingLogs as $log)
            <div class="border-l-4 {{ $log->status === 'completed' ? 'border-green-500' : ($log->status === 'failed' ? 'border-red-500' : 'border-yellow-500') }} pl-4 py-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ ucfirst($log->phase) }} - {{ ucfirst($log->status) }}</p>
                        <p class="text-xs text-gray-600">{{ $log->message }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $log->started_at->format('M d, Y H:i:s') }}</p>
                    </div>
                    @if($log->progress_percentage > 0)
                    <div class="text-sm text-gray-600">{{ $log->progress_percentage }}%</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

