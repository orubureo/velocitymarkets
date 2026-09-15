<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoWallet extends Model
{
    const CURRENCIES = ['BTC', 'ETH', 'USDT', 'SOL'];

    const NETWORKS = ['TRC20', 'ERC20', 'BEP20'];

    protected $fillable = ['currency', 'network', 'address', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function label(): string
    {
        return $this->network ? "{$this->currency} ({$this->network})" : $this->currency;
    }
}
