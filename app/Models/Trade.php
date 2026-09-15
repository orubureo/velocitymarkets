<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $fillable = [
        'user_id',
        'wallet_id',
        'asset',
        'direction',
        'stake',
        'entry_price',
        'exit_price',
        'payout',
        'status',
        'expires_at',
        'settled_at',
    ];

    protected $casts = [
        'stake' => 'decimal:2',
        'entry_price' => 'decimal:8',
        'exit_price' => 'decimal:8',
        'payout' => 'decimal:2',
        'expires_at' => 'datetime',
        'settled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
