<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trader extends Model
{
    protected $fillable = [
        'name', 'avatar_initials', 'tagline', 'bio', 'tier', 'risk_level', 'win_rate', 'roi_30d',
        'base_copiers', 'min_copy_amount', 'max_copy_amount', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'win_rate' => 'decimal:2',
        'roi_30d' => 'decimal:2',
        'min_copy_amount' => 'decimal:2',
        'max_copy_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(CopyTradeSubscription::class);
    }

    /**
     * Displayed copier count: the trader's admin-set baseline plus any real
     * active subscriptions on this platform (requires ->loadCount('subscriptions')
     * or a withCount(['subscriptions' => ...]) query to be run first).
     */
    public function totalCopiers(): int
    {
        return $this->base_copiers + ($this->subscriptions_count ?? 0);
    }

    public function tierColor(): string
    {
        return match ($this->tier) {
            'elite' => 'violet',
            'pro' => 'lime',
            default => 'zinc',
        };
    }

    public function riskColor(): string
    {
        return match ($this->risk_level) {
            'low' => 'sky',
            'medium' => 'amber',
            'high' => 'red',
            default => 'zinc',
        };
    }

    /**
     * Soft pill classes for the risk badge — {pill: container classes, dot: bullet color}.
     *
     * @return array{pill: string, dot: string}
     */
    public function riskBadgeClasses(): array
    {
        return match ($this->risk_level) {
            'low' => ['pill' => 'bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20', 'dot' => 'bg-sky-500'],
            'medium' => ['pill' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20', 'dot' => 'bg-amber-500'],
            'high' => ['pill' => 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20', 'dot' => 'bg-red-500'],
            default => ['pill' => 'bg-zinc-500/10 text-zinc-600 dark:text-zinc-400 border border-zinc-500/20', 'dot' => 'bg-zinc-500'],
        };
    }

    /**
     * A deterministic, stylized profile image for this trader — never a real
     * person's photo, generated from their name so it stays consistent across
     * reloads. Views must handle load failure (see resources/js/app.js
     * avatarImgFallback) since this is a live third-party request.
     */
    public function avatarUrl(): string
    {
        return 'https://api.dicebear.com/9.x/notionists/svg?seed='.urlencode($this->name);
    }
}
