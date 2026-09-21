<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoWallet extends Model
{
    const CURRENCIES = ['BTC', 'ETH', 'USDT', 'SOL'];

    const NETWORKS = [
        'BTC' => ['BTC', 'BEP20'],
        'ETH' => ['ERC20', 'ARBITRUM', 'BASE', 'BEP20'],
        'USDT' => ['TRC20', 'ERC20', 'BEP20'],
        'SOL' => ['SOL', 'BEP20'],
    ];

    protected $fillable = ['currency', 'network', 'address', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * @return array<int, string>
     */
    public static function networksFor(string $currency): array
    {
        return self::NETWORKS[$currency] ?? [];
    }

    public function label(): string
    {
        return $this->network ? "{$this->currency} ({$this->network})" : $this->currency;
    }
}
