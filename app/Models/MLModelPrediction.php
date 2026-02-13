<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MLModelPrediction extends Model
{
    protected $fillable = [
        'ml_model_id',
        'instrument',
        'prediction',
        'confidence',
        'predicted_price',
        'predicted_volatility',
        'actual_price',
        'actual_return',
        'was_correct',
        'input_features',
        'model_output',
        'predicted_at',
        'validated_at',
    ];

    protected $casts = [
        'confidence' => 'decimal:4',
        'predicted_price' => 'decimal:5',
        'predicted_volatility' => 'decimal:5',
        'actual_price' => 'decimal:5',
        'actual_return' => 'decimal:5',
        'was_correct' => 'boolean',
        'input_features' => 'array',
        'model_output' => 'array',
        'predicted_at' => 'datetime',
        'validated_at' => 'datetime',
    ];

    /**
     * Get the ML model
     */
    public function mlModel(): BelongsTo
    {
        return $this->belongsTo(MLModel::class);
    }
}

