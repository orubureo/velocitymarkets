<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CopyTradeSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'wallet_id',
        'trader_id',
        'amount',
        'status',
        'started_at',
        'stopped_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'started_at' => 'datetime',
        'stopped_at' => 'datetime',
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
     * @return BelongsTo<Trader, $this>
     */
    public function trader(): BelongsTo
    {
        return $this->belongsTo(Trader::class);
    }

    /**
     * @return MorphMany<WalletTransaction, $this>
     */
    public function transactions(): MorphMany
    {
        return $this->morphMany(WalletTransaction::class, 'reference');
    }

    public function totalProfit(): float
    {
        return (float) $this->transactions()
            ->where('type', 'copy_trade_profit')
            ->where('status', 'completed')
            ->sum('amount');
    }

    public function totalLoss(): float
    {
        return abs((float) $this->transactions()
            ->where('type', 'copy_trade_loss')
            ->where('status', 'completed')
            ->sum('amount'));
    }

    public function netPnl(): float
    {
        return (float) $this->transactions()
            ->whereIn('type', ['copy_trade_profit', 'copy_trade_loss'])
            ->where('status', 'completed')
            ->sum('amount');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
