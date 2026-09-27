<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;

class Trader extends Model
{
    /**
     * Uploaded trader photos routinely arrive as multi-megapixel, multi-MB
     * camera/stock originals (seen up to ~6000px and 2MB) — displayed at a
     * tiny avatar size, browsers downscale these inconsistently and the page
     * ships megabytes for a thumbnail. This normalizes any source image into
     * a small, sharp, EXIF-corrected square JPEG and stores it on the public
     * disk, returning the relative path to save as avatar_path.
     */
    public static function storeAvatarFromPath(string $sourcePath): string
    {
        $manager = new ImageManager(new Driver);

        $encoded = $manager->decodePath($sourcePath)
            ->orient()
            ->cover(500, 500)
            ->encode(new JpegEncoder(quality: 85));

        $path = 'traders/'.Str::random(40).'.jpg';

        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }

    protected $fillable = [
        'name',
        'avatar_initials',
        'avatar_path',
        'tagline',
        'bio',
        'tier',
        'risk_level',
        'win_rate',
        'roi_30d',
        'base_copiers',
        'min_copy_amount',
        'max_copy_amount',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'win_rate' => 'decimal:2',
        'roi_30d' => 'decimal:2',
        'min_copy_amount' => 'decimal:2',
        'max_copy_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * @return HasMany<CopyTradeSubscription, $this>
     */
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
            'pro' => 'green',
            'verified' => 'cyan',
        };
    }

    public function riskColor(): string
    {
        return match ($this->risk_level) {
            'low' => 'blue',
            'medium' => 'yellow',
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
     * The admin-uploaded profile photo, or — when none has been set — a
     * deterministic, stylized placeholder generated from the trader's name so
     * it stays consistent across reloads. Views must handle load failure (see
     * resources/js/app.js avatarImgFallback) since the placeholder is a live
     * third-party request.
     */
    public function avatarUrl(): string
    {
        if ($this->avatar_path) {
            // A root-relative path (not Storage::url(), which bakes in the
            // fixed APP_URL host) so the image still resolves correctly when
            // the app is reached through a different origin than APP_URL —
            // e.g. Herd's share/tunnel URL on a phone, which can't resolve
            // the local .test hostname that Storage::url() would hardcode.
            return '/storage/'.$this->avatar_path;
        }

        return 'https://api.dicebear.com/9.x/notionists/svg?seed='.urlencode($this->name);
    }
}
