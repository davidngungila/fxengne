<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MLModelTrainingLog extends Model
{
    protected $table = 'ml_model_training_logs';
    
    protected $fillable = [
        'ml_model_id',
        'phase',
        'status',
        'message',
        'metrics',
        'progress_percentage',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'metrics' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the ML model
     */
    public function mlModel(): BelongsTo
    {
        return $this->belongsTo(MLModel::class, 'ml_model_id');
    }
}

