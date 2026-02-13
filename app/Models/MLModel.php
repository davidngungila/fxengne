<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MLModel extends Model
{
    protected $table = 'ml_models';
    
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'architecture',
        'status',
        'description',
        'config',
        'training_config',
        'model_file_path',
        'performance_metrics',
        'feature_importance',
        'training_samples',
        'test_samples',
        'accuracy',
        'precision',
        'recall',
        'f1_score',
        'sharpe_ratio',
        'win_rate',
        'trained_at',
        'last_prediction_at',
        'total_predictions',
        'successful_predictions',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'config' => 'array',
        'training_config' => 'array',
        'performance_metrics' => 'array',
        'feature_importance' => 'array',
        'accuracy' => 'decimal:2',
        'precision' => 'decimal:2',
        'recall' => 'decimal:2',
        'f1_score' => 'decimal:2',
        'sharpe_ratio' => 'decimal:2',
        'win_rate' => 'decimal:2',
        'trained_at' => 'datetime',
        'last_prediction_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the model
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get predictions for this model
     */
    public function predictions(): HasMany
    {
        return $this->hasMany(MLModelPrediction::class, 'ml_model_id');
    }

    /**
     * Get training logs for this model
     */
    public function trainingLogs(): HasMany
    {
        return $this->hasMany(MLModelTrainingLog::class, 'ml_model_id');
    }

    /**
     * Scope for active models
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for trained models
     */
    public function scopeTrained($query)
    {
        return $query->where('status', 'trained');
    }

    /**
     * Scope by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get success rate
     */
    public function getSuccessRateAttribute(): float
    {
        if ($this->total_predictions === 0) {
            return 0;
        }
        return ($this->successful_predictions / $this->total_predictions) * 100;
    }

    /**
     * Check if model is ready for deployment
     */
    public function isReadyForDeployment(): bool
    {
        return $this->status === 'trained' 
            && !is_null($this->model_file_path)
            && $this->accuracy >= 0.55; // Minimum 55% accuracy
    }
}

    
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'architecture',
        'status',
        'description',
        'config',
        'training_config',
        'model_file_path',
        'performance_metrics',
        'feature_importance',
        'training_samples',
        'test_samples',
        'accuracy',
        'precision',
        'recall',
        'f1_score',
        'sharpe_ratio',
        'win_rate',
        'trained_at',
        'last_prediction_at',
        'total_predictions',
        'successful_predictions',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'config' => 'array',
        'training_config' => 'array',
        'performance_metrics' => 'array',
        'feature_importance' => 'array',
        'accuracy' => 'decimal:2',
        'precision' => 'decimal:2',
        'recall' => 'decimal:2',
        'f1_score' => 'decimal:2',
        'sharpe_ratio' => 'decimal:2',
        'win_rate' => 'decimal:2',
        'trained_at' => 'datetime',
        'last_prediction_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the model
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get predictions for this model
     */
    public function predictions(): HasMany
    {
        return $this->hasMany(MLModelPrediction::class, 'ml_model_id');
    }

    /**
     * Get training logs for this model
     */
    public function trainingLogs(): HasMany
    {
        return $this->hasMany(MLModelTrainingLog::class, 'ml_model_id');
    }

    /**
     * Scope for active models
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for trained models
     */
    public function scopeTrained($query)
    {
        return $query->where('status', 'trained');
    }

    /**
     * Scope by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get success rate
     */
    public function getSuccessRateAttribute(): float
    {
        if ($this->total_predictions === 0) {
            return 0;
        }
        return ($this->successful_predictions / $this->total_predictions) * 100;
    }

    /**
     * Check if model is ready for deployment
     */
    public function isReadyForDeployment(): bool
    {
        return $this->status === 'trained' 
            && !is_null($this->model_file_path)
            && $this->accuracy >= 0.55; // Minimum 55% accuracy
    }
}
