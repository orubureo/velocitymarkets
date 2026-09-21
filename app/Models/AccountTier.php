<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountTier extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'daily_profit_percent',
        'total_return_percent',
        'referral_bonus_percent',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'daily_profit_percent' => 'decimal:2',
        'total_return_percent' => 'decimal:2',
        'referral_bonus_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
