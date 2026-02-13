<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ml_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['price_direction', 'volatility', 'sentiment', 'parameter_optimization'])->default('price_direction');
            $table->enum('architecture', ['TFT', 'LSTM', 'XGBoost', 'RandomForest', 'FinBERT', 'Hybrid', 'GridSearch'])->default('TFT');
            $table->enum('status', ['draft', 'training', 'trained', 'active', 'inactive', 'failed'])->default('draft');
            $table->text('description')->nullable();
            $table->json('config')->nullable(); // Model configuration (hyperparameters, features, etc.)
            $table->json('training_config')->nullable(); // Training parameters (epochs, batch size, etc.)
            $table->string('model_file_path')->nullable(); // Path to saved model file
            $table->json('performance_metrics')->nullable(); // Training/test metrics
            $table->json('feature_importance')->nullable(); // Feature importance scores
            $table->integer('training_samples')->default(0);
            $table->integer('test_samples')->default(0);
            $table->decimal('accuracy', 5, 2)->nullable();
            $table->decimal('precision', 5, 2)->nullable();
            $table->decimal('recall', 5, 2)->nullable();
            $table->decimal('f1_score', 5, 2)->nullable();
            $table->decimal('sharpe_ratio', 5, 2)->nullable();
            $table->decimal('win_rate', 5, 2)->nullable();
            $table->timestamp('trained_at')->nullable();
            $table->timestamp('last_prediction_at')->nullable();
            $table->integer('total_predictions')->default(0);
            $table->integer('successful_predictions')->default(0);
            $table->boolean('is_active')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'type', 'status']);
            $table->index('is_active');
        });

        // Create model predictions table
        Schema::create('ml_model_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ml_model_id')->constrained()->onDelete('cascade');
            $table->string('instrument');
            $table->enum('prediction', ['BUY', 'SELL', 'HOLD'])->nullable();
            $table->decimal('confidence', 5, 4)->nullable(); // 0.0000 to 1.0000
            $table->decimal('predicted_price', 15, 5)->nullable();
            $table->decimal('predicted_volatility', 15, 5)->nullable();
            $table->decimal('actual_price', 15, 5)->nullable();
            $table->decimal('actual_return', 15, 5)->nullable();
            $table->boolean('was_correct')->nullable();
            $table->json('input_features')->nullable(); // Features used for prediction
            $table->json('model_output')->nullable(); // Raw model output
            $table->timestamp('predicted_at');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            
            $table->index(['ml_model_id', 'instrument', 'predicted_at']);
            $table->index('was_correct');
        });

        // Create model training logs table
        Schema::create('ml_model_training_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ml_model_id')->constrained()->onDelete('cascade');
            $table->enum('phase', ['data_preparation', 'training', 'validation', 'testing', 'deployment'])->default('training');
            $table->string('status'); // started, completed, failed, in_progress
            $table->text('message')->nullable();
            $table->json('metrics')->nullable(); // Phase-specific metrics
            $table->integer('progress_percentage')->default(0);
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['ml_model_id', 'phase', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_model_training_logs');
        Schema::dropIfExists('ml_model_predictions');
        Schema::dropIfExists('ml_models');
    }
};
