<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvestmentPlan extends Model
{
    protected $fillable = [
        'name', 'description', 'min_amount', 'max_amount',
        'roi_percent', 'duration_days', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'roi_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function investments(): HasMany
    {
        return $this->hasMany(UserInvestment::class);
    }

    public function dailyRoiPercent(): float
    {
        return round((float) $this->roi_percent / max(1, $this->duration_days), 2);
    }
}
