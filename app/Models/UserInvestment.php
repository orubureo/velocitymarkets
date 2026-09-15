<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class UserInvestment extends Model
{
    protected $fillable = [
        'user_id', 'wallet_id', 'investment_plan_id', 'amount',
        'status', 'starts_at', 'ends_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(InvestmentPlan::class, 'investment_plan_id');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(WalletTransaction::class, 'reference');
    }

    public function totalRoiPaid(): float
    {
        return (float) $this->transactions()
            ->where('type', 'roi_payout')
            ->where('status', 'completed')
            ->sum('amount');
    }

    public function progressPercent(): int
    {
        if (! $this->starts_at || ! $this->ends_at) {
            return 0;
        }

        $total = $this->starts_at->diffInSeconds($this->ends_at);

        if ($total <= 0) {
            return 100;
        }

        $elapsed = $this->starts_at->diffInSeconds(now(), false);

        return (int) min(100, max(0, round($elapsed / $total * 100)));
    }

    public function daysRemaining(): int
    {
        if (! $this->ends_at || $this->ends_at->isPast()) {
            return 0;
        }

        return (int) now()->diffInDays($this->ends_at);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
