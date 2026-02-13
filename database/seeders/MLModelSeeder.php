<?php

namespace Database\Seeders;

use App\Models\MLModel;
use App\Models\MLModelPrediction;
use App\Models\MLModelTrainingLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MLModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user or create one
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@fxengne.com',
            ]);
        }

        // 1. PRICE DIRECTION MODEL - TFT (Active)
        $tftModel = MLModel::create([
            'user_id' => $user->id,
            'name' => 'XAUUSD TFT Price Direction Model',
            'type' => 'price_direction',
            'architecture' => 'TFT',
            'status' => 'active',
            'description' => 'Temporal Fusion Transformer model for predicting XAUUSD price direction. Trained on 2+ years of 15M OHLC data with technical indicators.',
            'config' => [
                'features' => ['OHLC', 'RSI', 'MACD', 'BB', 'ATR', 'Volume', 'Time'],
                'timeframe' => 'M15',
                'instrument' => 'XAU_USD',
                'hidden_size' => 64,
                'num_heads' => 4,
                'num_layers' => 3,
                'dropout' => 0.1,
                'quantiles' => [0.1, 0.5, 0.9],
            ],
            'training_config' => [
                'period_years' => 2,
                'test_split' => 25,
                'epochs' => 150,
                'batch_size' => 64,
                'learning_rate' => 0.001,
                'optimizer' => 'AdamW',
            ],
            'model_file_path' => 'models/tft_xauusd_v1.2.3.pkl',
            'performance_metrics' => [
                'train_loss' => 0.0234,
                'val_loss' => 0.0289,
                'test_loss' => 0.0312,
                'mae' => 2.45,
                'rmse' => 3.12,
                'r2_score' => 0.78,
            ],
            'feature_importance' => [
                'RSI' => 0.23,
                'MACD' => 0.19,
                'ATR' => 0.17,
                'BB_upper' => 0.15,
                'BB_lower' => 0.12,
                'Volume' => 0.08,
                'Time' => 0.06,
            ],
            'training_samples' => 43200,
            'test_samples' => 10800,
            'accuracy' => 0.78,
            'precision' => 0.75,
            'recall' => 0.82,
            'f1_score' => 0.78,
            'sharpe_ratio' => 1.85,
            'win_rate' => 0.68,
            'trained_at' => Carbon::now()->subDays(5),
            'last_prediction_at' => Carbon::now()->subMinutes(2),
            'total_predictions' => 1247,
            'successful_predictions' => 848,
            'is_active' => true,
            'notes' => 'Production-ready model. Deployed to live trading system. Regular retraining every quarter.',
        ]);

        // Add training logs for TFT model
        $this->createTrainingLogs($tftModel, [
            ['phase' => 'data_preparation', 'status' => 'completed', 'message' => 'Data collected and preprocessed', 'progress' => 100],
            ['phase' => 'training', 'status' => 'completed', 'message' => 'Model training completed successfully', 'progress' => 100],
            ['phase' => 'validation', 'status' => 'completed', 'message' => 'Validation passed with 78% accuracy', 'progress' => 100],
            ['phase' => 'testing', 'status' => 'completed', 'message' => 'Out-of-sample testing completed', 'progress' => 100],
            ['phase' => 'deployment', 'status' => 'completed', 'message' => 'Model deployed to production', 'progress' => 100],
        ]);

        // Add recent predictions for TFT model
        $this->createPredictions($tftModel, 20, 'XAU_USD');

        // 2. PRICE DIRECTION MODEL - LSTM (Trained, Inactive)
        $lstmModel = MLModel::create([
            'user_id' => $user->id,
            'name' => 'XAUUSD LSTM Hybrid Model',
            'type' => 'price_direction',
            'architecture' => 'LSTM',
            'status' => 'trained',
            'description' => 'Hybrid LSTM model with technical indicators. Alternative to TFT for comparison.',
            'config' => [
                'features' => ['OHLC', 'RSI', 'EMA', 'ATR'],
                'timeframe' => 'M15',
                'instrument' => 'XAU_USD',
                'lstm_units' => 128,
                'dense_units' => 64,
                'dropout' => 0.2,
            ],
            'training_config' => [
                'period_years' => 1.5,
                'test_split' => 25,
                'epochs' => 100,
                'batch_size' => 32,
                'learning_rate' => 0.0005,
            ],
            'model_file_path' => 'models/lstm_xauusd_v1.0.1.pkl',
            'performance_metrics' => [
                'train_loss' => 0.0312,
                'val_loss' => 0.0389,
                'test_loss' => 0.0415,
                'mae' => 3.12,
                'rmse' => 4.01,
            ],
            'feature_importance' => [
                'RSI' => 0.28,
                'EMA_9' => 0.22,
                'EMA_21' => 0.19,
                'ATR' => 0.16,
                'OHLC' => 0.15,
            ],
            'training_samples' => 32400,
            'test_samples' => 8100,
            'accuracy' => 0.72,
            'precision' => 0.70,
            'recall' => 0.75,
            'f1_score' => 0.72,
            'sharpe_ratio' => 1.52,
            'win_rate' => 0.65,
            'trained_at' => Carbon::now()->subDays(10),
            'last_prediction_at' => Carbon::now()->subDays(2),
            'total_predictions' => 856,
            'successful_predictions' => 556,
            'is_active' => false,
            'notes' => 'Backup model. Lower performance than TFT. Kept for comparison purposes.',
        ]);

        $this->createTrainingLogs($lstmModel, [
            ['phase' => 'data_preparation', 'status' => 'completed', 'message' => 'Data preparation completed', 'progress' => 100],
            ['phase' => 'training', 'status' => 'completed', 'message' => 'Training completed', 'progress' => 100],
            ['phase' => 'validation', 'status' => 'completed', 'message' => 'Validation completed', 'progress' => 100],
        ]);

        // 3. VOLATILITY MODEL - XGBoost (Active)
        $xgbModel = MLModel::create([
            'user_id' => $user->id,
            'name' => 'XAUUSD Volatility Forecast XGBoost',
            'type' => 'volatility',
            'architecture' => 'XGBoost',
            'status' => 'active',
            'description' => 'XGBoost regressor for forecasting XAUUSD volatility. Outperforms GARCH and ARIMA models. Used for dynamic position sizing and stop-loss placement.',
            'config' => [
                'features' => ['OHLC', 'ATR', 'BB', 'Volume', 'Historical_Volatility'],
                'timeframe' => 'D1',
                'instrument' => 'XAU_USD',
                'n_estimators' => 200,
                'max_depth' => 8,
                'learning_rate' => 0.05,
                'subsample' => 0.8,
            ],
            'training_config' => [
                'period_years' => 10,
                'test_split' => 20,
                'cross_validation' => 5,
            ],
            'model_file_path' => 'models/xgboost_volatility_v2.1.0.pkl',
            'performance_metrics' => [
                'mae' => 1.23,
                'rmse' => 1.89,
                'mape' => 8.5,
                'r2_score' => 0.85,
                'vs_garch' => '+12.3%',
                'vs_arima' => '+18.7%',
            ],
            'feature_importance' => [
                'ATR' => 0.31,
                'Historical_Volatility' => 0.24,
                'BB_width' => 0.19,
                'Volume' => 0.14,
                'OHLC' => 0.12,
            ],
            'training_samples' => 3650,
            'test_samples' => 730,
            'accuracy' => 0.85,
            'precision' => null,
            'recall' => null,
            'f1_score' => null,
            'sharpe_ratio' => null,
            'win_rate' => null,
            'trained_at' => Carbon::now()->subDays(3),
            'last_prediction_at' => Carbon::now()->subMinutes(5),
            'total_predictions' => 234,
            'successful_predictions' => 199,
            'is_active' => true,
            'notes' => 'Best-in-class volatility forecasting. Retrained monthly with latest data.',
        ]);

        $this->createTrainingLogs($xgbModel, [
            ['phase' => 'data_preparation', 'status' => 'completed', 'message' => '10 years of daily data collected', 'progress' => 100],
            ['phase' => 'training', 'status' => 'completed', 'message' => 'XGBoost training completed', 'progress' => 100],
            ['phase' => 'validation', 'status' => 'completed', 'message' => 'Cross-validation completed', 'progress' => 100],
        ]);

        // Add volatility predictions
        $this->createVolatilityPredictions($xgbModel, 15);

        // 4. VOLATILITY MODEL - Random Forest (Trained)
        $rfModel = MLModel::create([
            'user_id' => $user->id,
            'name' => 'XAUUSD Volatility Random Forest',
            'type' => 'volatility',
            'architecture' => 'RandomForest',
            'status' => 'trained',
            'description' => 'Random Forest baseline for volatility forecasting. Inferior to XGBoost but kept for comparison.',
            'config' => [
                'features' => ['OHLC', 'ATR', 'BB', 'Volume'],
                'timeframe' => 'D1',
                'instrument' => 'XAU_USD',
                'n_estimators' => 100,
                'max_depth' => 10,
            ],
            'training_config' => [
                'period_years' => 10,
                'test_split' => 20,
            ],
            'model_file_path' => 'models/rf_volatility_v1.0.0.pkl',
            'performance_metrics' => [
                'mae' => 1.45,
                'rmse' => 2.12,
                'mape' => 10.2,
                'r2_score' => 0.78,
            ],
            'feature_importance' => [
                'ATR' => 0.29,
                'Historical_Volatility' => 0.22,
                'BB_width' => 0.18,
                'Volume' => 0.16,
                'OHLC' => 0.15,
            ],
            'training_samples' => 3650,
            'test_samples' => 730,
            'accuracy' => 0.78,
            'trained_at' => Carbon::now()->subDays(15),
            'last_prediction_at' => Carbon::now()->subDays(5),
            'total_predictions' => 156,
            'successful_predictions' => 122,
            'is_active' => false,
            'notes' => 'Baseline model. Not used in production.',
        ]);

        // 5. SENTIMENT MODEL - FinBERT (Trained)
        $finbertModel = MLModel::create([
            'user_id' => $user->id,
            'name' => 'Financial News Sentiment FinBERT',
            'type' => 'sentiment',
            'architecture' => 'FinBERT',
            'status' => 'trained',
            'description' => 'Fine-tuned FinBERT model for analyzing financial news sentiment. Note: Reactive, not predictive. Used as confirmation filter.',
            'config' => [
                'features' => ['News_Headlines', 'News_Body', 'Source'],
                'model_name' => 'yiyanghkust/finbert-tone',
                'max_length' => 512,
                'batch_size' => 16,
            ],
            'training_config' => [
                'labeled_samples' => 1500,
                'epochs' => 5,
                'learning_rate' => 2e-5,
                'validation_split' => 0.2,
            ],
            'model_file_path' => 'models/finbert_sentiment_v1.3.0.pkl',
            'performance_metrics' => [
                'f1_score' => 0.707,
                'precision' => 0.72,
                'recall' => 0.69,
                'accuracy' => 0.71,
                'zero_shot_baseline' => 0.555,
            ],
            'feature_importance' => [
                'Headline_Text' => 0.45,
                'Body_Text' => 0.35,
                'Source_Credibility' => 0.12,
                'Timestamp' => 0.08,
            ],
            'training_samples' => 1200,
            'test_samples' => 300,
            'accuracy' => 0.71,
            'precision' => 0.72,
            'recall' => 0.69,
            'f1_score' => 0.707,
            'trained_at' => Carbon::now()->subDays(7),
            'last_prediction_at' => Carbon::now()->subHours(1),
            'total_predictions' => 456,
            'successful_predictions' => 324,
            'is_active' => false,
            'notes' => 'Used as confirmation filter only. Does not predict future prices. F1 score: 0.707 vs 0.555 zero-shot baseline.',
        ]);

        $this->createTrainingLogs($finbertModel, [
            ['phase' => 'data_preparation', 'status' => 'completed', 'message' => '1500 headlines manually annotated', 'progress' => 100],
            ['phase' => 'training', 'status' => 'completed', 'message' => 'FinBERT fine-tuning completed', 'progress' => 100],
            ['phase' => 'validation', 'status' => 'completed', 'message' => 'Validation F1: 0.707', 'progress' => 100],
        ]);

        // Add sentiment predictions
        $this->createSentimentPredictions($finbertModel, 12);

        // 6. PARAMETER OPTIMIZATION - Grid Search (Trained)
        $gridSearchModel = MLModel::create([
            'user_id' => $user->id,
            'name' => 'RSI/BB/MACD Parameter Optimizer',
            'type' => 'parameter_optimization',
            'architecture' => 'GridSearch',
            'status' => 'trained',
            'description' => 'Grid search optimization for traditional indicator parameters. Optimized RSI, Bollinger Bands, and MACD settings.',
            'config' => [
                'indicators' => ['RSI', 'Bollinger_Bands', 'MACD'],
                'timeframe' => 'M15',
                'instrument' => 'XAU_USD',
                'optimization_metric' => 'Sharpe_Ratio',
                'target_sharpe' => 1.5,
            ],
            'training_config' => [
                'period_years' => 1,
                'rsi_range' => [10, 20],
                'bb_period_range' => [15, 30],
                'bb_std_range' => [1.5, 2.5],
                'macd_fast_range' => [8, 12],
                'macd_slow_range' => [21, 26],
            ],
            'model_file_path' => 'models/gridsearch_params_v1.0.0.json',
            'performance_metrics' => [
                'optimal_rsi' => 14,
                'optimal_bb_period' => 20,
                'optimal_bb_std' => 2.0,
                'optimal_macd_fast' => 12,
                'optimal_macd_slow' => 26,
                'sharpe_ratio' => 1.68,
                'win_rate' => 0.62,
                'total_trades' => 1247,
            ],
            'feature_importance' => [
                'RSI_Period' => 0.35,
                'BB_Period' => 0.28,
                'BB_Std' => 0.22,
                'MACD_Fast' => 0.10,
                'MACD_Slow' => 0.05,
            ],
            'training_samples' => 35040,
            'test_samples' => 8760,
            'accuracy' => 0.62,
            'sharpe_ratio' => 1.68,
            'win_rate' => 0.62,
            'trained_at' => Carbon::now()->subDays(12),
            'last_prediction_at' => null,
            'total_predictions' => 0,
            'successful_predictions' => 0,
            'is_active' => false,
            'notes' => 'Optimal parameters exported to MT5 EA. RSI(14), BB(20,2), MACD(12,26,9).',
        ]);

        $this->createTrainingLogs($gridSearchModel, [
            ['phase' => 'data_preparation', 'status' => 'completed', 'message' => '1 year M15 data prepared', 'progress' => 100],
            ['phase' => 'training', 'status' => 'completed', 'message' => 'Grid search completed. 1,247 parameter combinations tested', 'progress' => 100],
            ['phase' => 'validation', 'status' => 'completed', 'message' => 'Optimal parameters validated', 'progress' => 100],
        ]);

        // 7. PRICE DIRECTION - Hybrid TFT + N-BEATS (Draft)
        $hybridModel = MLModel::create([
            'user_id' => $user->id,
            'name' => 'Hybrid TFT + N-BEATS Experimental',
            'type' => 'price_direction',
            'architecture' => 'Hybrid',
            'status' => 'draft',
            'description' => 'Experimental hybrid model combining TFT for feature selection and N-BEATS for decomposition. Early stage development.',
            'config' => [
                'features' => ['OHLC', 'RSI', 'MACD', 'BB', 'ATR', 'News_Sentiment'],
                'timeframe' => 'M15',
                'instrument' => 'XAU_USD',
                'tft_config' => ['hidden_size' => 64, 'num_heads' => 4],
                'nbeats_config' => ['stack_types' => ['trend', 'seasonality'], 'num_blocks' => 3],
            ],
            'training_config' => [
                'period_years' => 2,
                'test_split' => 25,
                'epochs' => 200,
                'batch_size' => 32,
            ],
            'model_file_path' => null,
            'performance_metrics' => null,
            'feature_importance' => null,
            'training_samples' => 0,
            'test_samples' => 0,
            'is_active' => false,
            'notes' => 'Experimental model. Not yet trained. Requires additional development.',
        ]);

        $this->createTrainingLogs($hybridModel, [
            ['phase' => 'data_preparation', 'status' => 'in_progress', 'message' => 'Preparing training data', 'progress' => 45],
        ]);

        // 8. PRICE DIRECTION - TFT (Training)
        $trainingModel = MLModel::create([
            'user_id' => $user->id,
            'name' => 'XAUUSD TFT Model v2.0 (Retraining)',
            'type' => 'price_direction',
            'architecture' => 'TFT',
            'status' => 'training',
            'description' => 'Quarterly retraining of TFT model with latest market data. Includes recent volatility regime changes.',
            'config' => [
                'features' => ['OHLC', 'RSI', 'MACD', 'BB', 'ATR', 'Volume', 'Time'],
                'timeframe' => 'M15',
                'instrument' => 'XAU_USD',
                'hidden_size' => 64,
                'num_heads' => 4,
                'num_layers' => 3,
            ],
            'training_config' => [
                'period_years' => 2,
                'test_split' => 25,
                'epochs' => 150,
                'batch_size' => 64,
            ],
            'model_file_path' => null,
            'performance_metrics' => null,
            'feature_importance' => null,
            'training_samples' => 43200,
            'test_samples' => 10800,
            'is_active' => false,
            'notes' => 'Quarterly retraining in progress. Expected completion: 2 hours.',
        ]);

        $this->createTrainingLogs($trainingModel, [
            ['phase' => 'data_preparation', 'status' => 'completed', 'message' => 'Data preparation completed', 'progress' => 100],
            ['phase' => 'training', 'status' => 'in_progress', 'message' => 'Training epoch 87/150', 'progress' => 58],
        ]);

        $this->command->info('ML Models seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- 2 Active models (TFT Price Direction, XGBoost Volatility)');
        $this->command->info('- 4 Trained models');
        $this->command->info('- 1 Draft model');
        $this->command->info('- 1 Training model');
        $this->command->info('- Multiple predictions and training logs');
    }

    /**
     * Create training logs for a model
     */
    private function createTrainingLogs(MLModel $model, array $logs): void
    {
        foreach ($logs as $log) {
            MLModelTrainingLog::create([
                'ml_model_id' => $model->id,
                'phase' => $log['phase'],
                'status' => $log['status'],
                'message' => $log['message'],
                'metrics' => $log['metrics'] ?? null,
                'progress_percentage' => $log['progress'],
                'started_at' => Carbon::now()->subHours(rand(1, 48)),
                'completed_at' => $log['status'] === 'completed' ? Carbon::now()->subHours(rand(1, 24)) : null,
            ]);
        }
    }

    /**
     * Create predictions for price direction models
     */
    private function createPredictions(MLModel $model, int $count, string $instrument): void
    {
        $predictions = ['BUY', 'SELL', 'HOLD'];
        $basePrice = 2650.0;

        for ($i = 0; $i < $count; $i++) {
            $prediction = $predictions[array_rand($predictions)];
            $confidence = rand(55, 95) / 100;
            $predictedPrice = $basePrice + (rand(-50, 50) / 10);
            $actualPrice = $predictedPrice + (rand(-20, 20) / 10);
            $wasCorrect = rand(0, 100) < ($confidence * 100);

            MLModelPrediction::create([
                'ml_model_id' => $model->id,
                'instrument' => $instrument,
                'prediction' => $prediction,
                'confidence' => $confidence,
                'predicted_price' => $predictedPrice,
                'actual_price' => $actualPrice,
                'was_correct' => $wasCorrect,
                'input_features' => [
                    'RSI' => rand(30, 70),
                    'MACD' => rand(-5, 5) / 10,
                    'ATR' => rand(15, 25),
                ],
                'model_output' => [
                    'quantile_10' => $predictedPrice - 5,
                    'quantile_50' => $predictedPrice,
                    'quantile_90' => $predictedPrice + 5,
                ],
                'predicted_at' => Carbon::now()->subHours($count - $i),
                'validated_at' => Carbon::now()->subHours($count - $i - 1),
            ]);
        }
    }

    /**
     * Create volatility predictions
     */
    private function createVolatilityPredictions(MLModel $model, int $count): void
    {
        $baseVolatility = 18.5;

        for ($i = 0; $i < $count; $i++) {
            $predictedVolatility = $baseVolatility + (rand(-5, 5) / 10);
            $actualVolatility = $predictedVolatility + (rand(-2, 2) / 10);
            $wasCorrect = abs($predictedVolatility - $actualVolatility) < 1.5;

            MLModelPrediction::create([
                'ml_model_id' => $model->id,
                'instrument' => 'XAU_USD',
                'predicted_volatility' => $predictedVolatility,
                'actual_price' => null,
                'actual_return' => null,
                'was_correct' => $wasCorrect,
                'input_features' => [
                    'ATR' => rand(15, 25),
                    'Historical_Volatility' => rand(16, 22),
                    'BB_width' => rand(12, 18),
                ],
                'model_output' => [
                    'volatility_forecast' => $predictedVolatility,
                    'confidence_interval' => [$predictedVolatility - 2, $predictedVolatility + 2],
                ],
                'predicted_at' => Carbon::now()->subDays($count - $i),
                'validated_at' => Carbon::now()->subDays($count - $i - 1),
            ]);
        }
    }

    /**
     * Create sentiment predictions
     */
    private function createSentimentPredictions(MLModel $model, int $count): void
    {
        // Map sentiment to BUY/SELL/HOLD for prediction column
        $sentimentMap = [
            'Bullish' => 'BUY',
            'Bearish' => 'SELL',
            'Neutral' => 'HOLD',
        ];
        $sentiments = ['Bullish', 'Bearish', 'Neutral'];

        for ($i = 0; $i < $count; $i++) {
            $sentiment = $sentiments[array_rand($sentiments)];
            $prediction = $sentimentMap[$sentiment];
            $confidence = rand(60, 85) / 100;
            $wasCorrect = rand(0, 100) < ($confidence * 100);

            MLModelPrediction::create([
                'ml_model_id' => $model->id,
                'instrument' => 'XAU_USD',
                'prediction' => $prediction,
                'confidence' => $confidence,
                'was_correct' => $wasCorrect,
                'input_features' => [
                    'headline' => 'Gold prices ' . ($sentiment === 'Bullish' ? 'surge' : ($sentiment === 'Bearish' ? 'plunge' : 'stable')),
                    'source' => ['Reuters', 'Bloomberg', 'Financial Times'][array_rand([0, 1, 2])],
                ],
                'model_output' => [
                    'sentiment' => $sentiment,
                    'sentiment_score' => $sentiment === 'Bullish' ? 0.7 : ($sentiment === 'Bearish' ? -0.7 : 0.0),
                    'confidence' => $confidence,
                ],
                'predicted_at' => Carbon::now()->subHours($count - $i * 2),
                'validated_at' => Carbon::now()->subHours($count - $i * 2 - 1),
            ]);
        }
    }
}
