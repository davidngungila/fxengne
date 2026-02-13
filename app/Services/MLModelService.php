<?php

namespace App\Services;

use App\Models\MLModel;
use App\Models\MLModelPrediction;
use App\Models\MLModelTrainingLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MLModelService
{
    /**
     * Create a new ML model
     */
    public function createModel(array $data): MLModel
    {
        return MLModel::create($data);
    }

    /**
     * Start training a model
     */
    public function startTraining(MLModel $model): void
    {
        $model->update([
            'status' => 'training',
        ]);

        $this->logTraining($model, 'training', 'started', 'Training started', [
            'config' => $model->config,
            'training_config' => $model->training_config,
        ]);
    }

    /**
     * Complete training and save metrics
     */
    public function completeTraining(MLModel $model, array $metrics, string $modelFilePath = null): void
    {
        $model->update([
            'status' => 'trained',
            'model_file_path' => $modelFilePath,
            'performance_metrics' => $metrics,
            'accuracy' => $metrics['accuracy'] ?? null,
            'precision' => $metrics['precision'] ?? null,
            'recall' => $metrics['recall'] ?? null,
            'f1_score' => $metrics['f1_score'] ?? null,
            'sharpe_ratio' => $metrics['sharpe_ratio'] ?? null,
            'win_rate' => $metrics['win_rate'] ?? null,
            'training_samples' => $metrics['training_samples'] ?? 0,
            'test_samples' => $metrics['test_samples'] ?? 0,
            'trained_at' => now(),
        ]);

        $this->logTraining($model, 'training', 'completed', 'Training completed successfully', $metrics);
    }

    /**
     * Record a prediction
     */
    public function recordPrediction(MLModel $model, array $predictionData): MLModelPrediction
    {
        $prediction = MLModelPrediction::create(array_merge([
            'ml_model_id' => $model->id,
            'predicted_at' => now(),
        ], $predictionData));

        $model->increment('total_predictions');
        $model->update(['last_prediction_at' => now()]);

        return $prediction;
    }

    /**
     * Validate a prediction
     */
    public function validatePrediction(MLModelPrediction $prediction, float $actualPrice, string $actualDirection = null): void
    {
        $wasCorrect = null;
        
        if ($prediction->prediction && $actualDirection) {
            $wasCorrect = $prediction->prediction === $actualDirection;
        } elseif ($prediction->predicted_price) {
            $priceDiff = abs($prediction->predicted_price - $actualPrice);
            $wasCorrect = $priceDiff < ($actualPrice * 0.01); // Within 1%
        }

        $prediction->update([
            'actual_price' => $actualPrice,
            'was_correct' => $wasCorrect,
            'validated_at' => now(),
        ]);

        if ($wasCorrect) {
            $prediction->mlModel->increment('successful_predictions');
        }
    }

    /**
     * Log training progress
     */
    public function logTraining(MLModel $model, string $phase, string $status, string $message, array $metrics = [], int $progress = 0): MLModelTrainingLog
    {
        return MLModelTrainingLog::create([
            'ml_model_id' => $model->id,
            'phase' => $phase,
            'status' => $status,
            'message' => $message,
            'metrics' => $metrics,
            'progress_percentage' => $progress,
            'started_at' => now(),
            'completed_at' => $status === 'completed' || $status === 'failed' ? now() : null,
        ]);
    }

    /**
     * Get model recommendations based on type
     */
    public function getModelRecommendations(string $type): array
    {
        $recommendations = [
            'price_direction' => [
                'best' => 'TFT',
                'alternatives' => ['LSTM', 'Hybrid TFT + N-BEATS'],
                'description' => 'Temporal Fusion Transformer with attention mechanism. Best for direct integration into trading bot.',
                'data_required' => '2+ years of 15M OHLC data',
                'training_time' => '1-2 hours (GPU) / 2-4 hours (CPU)',
            ],
            'volatility' => [
                'best' => 'XGBoost',
                'alternatives' => ['Random Forest', 'GARCH'],
                'description' => 'XGBoost significantly outperforms econometric models for gold volatility forecasting.',
                'data_required' => '10+ years daily data',
                'training_time' => '< 30 minutes',
            ],
            'sentiment' => [
                'best' => 'FinBERT',
                'alternatives' => ['Zero-shot FinBERT'],
                'description' => 'Fine-tuned FinBERT for financial sentiment. Note: Reactive, not predictive.',
                'data_required' => '1500+ labeled headlines',
                'training_time' => '1-2 hours',
            ],
            'parameter_optimization' => [
                'best' => 'Grid Search',
                'alternatives' => ['Random Search', 'Bayesian Optimization'],
                'description' => 'Optimize traditional indicator parameters using ML-driven search.',
                'data_required' => '1+ year M1-M15 data',
                'training_time' => '10-60 minutes',
            ],
        ];

        return $recommendations[$type] ?? [];
    }

    /**
     * Get model statistics
     */
    public function getModelStatistics(MLModel $model): array
    {
        $predictions = $model->predictions()->whereNotNull('was_correct')->get();
        
        return [
            'total_predictions' => $model->total_predictions,
            'successful_predictions' => $model->successful_predictions,
            'success_rate' => $model->success_rate,
            'average_confidence' => $model->predictions()->avg('confidence'),
            'recent_accuracy' => $predictions->where('predicted_at', '>=', now()->subDays(7))->where('was_correct', true)->count() / max($predictions->where('predicted_at', '>=', now()->subDays(7))->count(), 1) * 100,
        ];
    }
}


