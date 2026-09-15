<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balance', 'currency'];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<WalletTransaction, $this>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function totalDeposits(): float
    {
        return (float) $this->transactions()
            ->where('type', 'deposit')->where('status', 'approved')->sum('amount');
    }

    public function totalWithdrawals(): float
    {
        return abs((float) $this->transactions()
            ->where('type', 'withdrawal')->where('status', 'approved')->sum('amount'));
    }

    public function totalProfit(): float
    {
        return (float) $this->transactions()
            ->whereIn('type', ['trade_profit', 'roi_payout', 'copy_trade_profit'])
            ->where('status', 'completed')->sum('amount');
    }

    public function totalReferralBonus(): float
    {
        return (float) $this->transactions()
            ->where('type', 'referral_bonus')->where('status', 'completed')->sum('amount');
    }
}
