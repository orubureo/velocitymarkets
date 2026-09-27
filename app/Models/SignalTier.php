<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SignalTier extends Model
{
    protected $fillable = [
        'name',
        'description',
        'percent',
        'price',
        'win_rate_percent',
        'roi_percent',
        'duration_days',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'percent' => 'integer',
        'price' => 'decimal:2',
        'win_rate_percent' => 'decimal:2',
        'roi_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * @return HasMany<UserSignal, $this>
     */
    public function userSignals(): HasMany
    {
        return $this->hasMany(UserSignal::class);
    }

    public function dailyRoiPercent(): float
    {
        return round((float) $this->roi_percent / max(1, $this->duration_days), 2);
    }
}
