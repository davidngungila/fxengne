<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trade extends Model
{
    protected $fillable = [
        'oanda_trade_id',
        'user_id',
        'instrument',
        'type',
        'state',
        'units',
        'entry_price',
        'current_price',
        'exit_price',
        'stop_loss',
        'take_profit',
        'unrealized_pl',
        'realized_pl',
        'margin_used',
        'strategy',
        'signal_data',
        'opened_at',
        'closed_at',
        'close_reason',
        'oanda_data',
    ];

    protected $casts = [
        'units' => 'decimal:2',
        'entry_price' => 'decimal:5',
        'current_price' => 'decimal:5',
        'exit_price' => 'decimal:5',
        'stop_loss' => 'decimal:5',
        'take_profit' => 'decimal:5',
        'unrealized_pl' => 'decimal:2',
        'realized_pl' => 'decimal:2',
        'margin_used' => 'decimal:2',
        'signal_data' => 'array',
        'oanda_data' => 'array',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the trade
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for open trades
     */
    public function scopeOpen($query)
    {
        return $query->where('state', 'OPEN');
    }

    /**
     * Scope for closed trades
     */
    public function scopeClosed($query)
    {
        return $query->where('state', 'CLOSED');
    }

    /**
     * Scope for user trades
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get formatted instrument name
     */
    public function getFormattedInstrumentAttribute(): string
    {
        return str_replace('_', '/', $this->instrument);
    }

    /**
     * Get P/L percentage
     */
    public function getPlPercentageAttribute(): float
    {
        if (!$this->entry_price || $this->entry_price == 0) {
            return 0;
        }

        $pl = $this->state === 'OPEN' ? $this->unrealized_pl : $this->realized_pl;
        $notional = abs($this->units * $this->entry_price);
        
        return $notional > 0 ? ($pl / $notional) * 100 : 0;
    }

    /**
     * Check if trade is profitable
     */
    public function isProfitable(): bool
    {
        $pl = $this->state === 'OPEN' ? $this->unrealized_pl : $this->realized_pl;
        return $pl > 0;
    }

    /**
     * Get trade duration in minutes
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->opened_at) {
            return null;
        }

        $endTime = $this->closed_at ?? now();
        return $endTime->diffInMinutes($this->opened_at);
    }
}
