<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class UserSignal extends Model
{
    protected $fillable = [
        'user_id',
        'wallet_id',
        'signal_tier_id',
        'percent',
        'amount',
        'status',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'percent' => 'integer',
        'amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Wallet, $this>
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * @return BelongsTo<SignalTier, $this>
     */
    public function tier(): BelongsTo
    {
        return $this->belongsTo(SignalTier::class, 'signal_tier_id');
    }

    /**
     * @return MorphMany<WalletTransaction, $this>
     */
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
