<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\SupportTicket;
use App\Models\Trade;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Populates the admin dashboard with a realistic 30 days of activity.
 *
 * Deliberately NOT registered in DatabaseSeeder — run it explicitly:
 *   php artisan db:seed --class=DemoDataSeeder
 *
 * Every row it writes hangs off a user at DEMO_DOMAIN, so re-running it wipes
 * only its own previous output and leaves real accounts untouched.
 */
class DemoDataSeeder extends Seeder
{
    private const DEMO_DOMAIN = 'demo.velocitymarkets.test';

    private const WINDOW_DAYS = 30;

    /** Registrations per day, oldest first — a visible growth curve rather than noise. */
    private const SIGNUP_CURVE = [
        1, 0, 2, 1, 3, 1, 0, 2, 4, 2,
        3, 5, 2, 1, 4, 6, 3, 2, 5, 7,
        4, 3, 6, 8, 5, 4, 7, 9, 6, 4,
    ];

    private const ASSETS = ['BTC/USD', 'ETH/USD', 'SOL/USD', 'XRP/USD', 'EUR/USD', 'GBP/USD'];

    private const COUNTRIES = ['United States', 'United Kingdom', 'Canada', 'Germany', 'Nigeria', 'South Africa', 'Australia', 'India', 'Brazil', 'Singapore'];

    public function run(): void
    {
        // Stable output across runs, so screenshots and charts don't reshuffle.
        fake()->seed(20260911);
        mt_srand(20260911);

        $this->purgePreviousRun();

        $admin = Admin::first();
        $users = $this->seedUsers();

        $this->command?->info(sprintf('Seeded %d demo users.', count($users)));

        $this->seedTransactions($users, $admin);
        $this->seedTrades($users);
        $this->seedSupportTickets($users);

        $this->command?->info('Demo data seeded across the last '.self::WINDOW_DAYS.' days.');
    }

    /**
     * Remove the previous run. Deletes children first rather than trusting
     * ON DELETE CASCADE — SQLite ignores foreign keys unless the pragma is on.
     */
    private function purgePreviousRun(): void
    {
        $userIds = User::where('email', 'like', '%@'.self::DEMO_DOMAIN)->pluck('id');

        if ($userIds->isEmpty()) {
            return;
        }

        $walletIds = Wallet::whereIn('user_id', $userIds)->pluck('id');

        WalletTransaction::whereIn('wallet_id', $walletIds)->delete();
        Trade::whereIn('user_id', $userIds)->delete();
        SupportTicket::whereIn('user_id', $userIds)->delete();
        Wallet::whereIn('id', $walletIds)->delete();
        DB::table('notifications')
            ->where('notifiable_type', User::class)
            ->whereIn('notifiable_id', $userIds)
            ->delete();
        User::whereIn('id', $userIds)->delete();

        $this->command?->warn(sprintf('Cleared %d users from a previous demo run.', $userIds->count()));
    }

    /**
     * @return array<int, array{user: User, wallet: Wallet, joined: Carbon}>
     */
    private function seedUsers(): array
    {
        $seeded = [];

        foreach (self::SIGNUP_CURVE as $index => $count) {
            $daysAgo = self::WINDOW_DAYS - 1 - $index;

            for ($i = 0; $i < $count; $i++) {
                $joined = $this->momentOn($daysAgo);
                $name = fake()->name();

                $user = User::create([
                    'name' => $name,
                    'email' => $this->demoEmail($name, count($seeded)),
                    'password' => Hash::make('password'),
                    'phone' => fake()->e164PhoneNumber(),
                    'country' => fake()->randomElement(self::COUNTRIES),
                ]);

                $this->applyKycState($user, $joined);

                // created_at drives the registration chart, so it must be backdated
                // after insert — Eloquent stamps now() on create.
                $user->forceFill([
                    'created_at' => $joined,
                    'updated_at' => $joined,
                    'email_verified_at' => mt_rand(1, 10) > 2 ? $this->clampToNow($joined->copy()->addMinutes(mt_rand(2, 180))) : null,
                    'referral_code' => $user->referral_code ?: Str::upper(Str::random(8)),
                ])->save();

                // AppServiceProvider's User::created hook makes the wallet, but that
                // hook is muted when this seeder runs inside DatabaseSeeder.
                $wallet = $user->wallet()->first()
                    ?? Wallet::create(['user_id' => $user->id, 'balance' => 0]);

                $wallet->forceFill(['created_at' => $joined, 'updated_at' => $joined])->save();

                $seeded[] = ['user' => $user, 'wallet' => $wallet, 'joined' => $joined];
            }
        }

        return $seeded;
    }

    private function applyKycState(User $user, Carbon $joined): void
    {
        $roll = mt_rand(1, 100);

        if ($roll > 70) {
            return; // most users never start KYC
        }

        $submitted = $this->clampToNow($joined->copy()->addHours(mt_rand(1, 72)));

        $state = match (true) {
            $roll <= 15 => ['kyc_status' => 'pending', 'kyc_reviewed_at' => null],
            $roll <= 25 => ['kyc_status' => 'rejected', 'kyc_reviewed_at' => $this->clampToNow($submitted->copy()->addHours(mt_rand(2, 40))), 'kyc_rejection_reason' => 'Document image was unreadable.'],
            default => ['kyc_status' => 'approved', 'kyc_reviewed_at' => $this->clampToNow($submitted->copy()->addHours(mt_rand(2, 40)))],
        };

        $user->fill($state + [
            'kyc_document_type' => fake()->randomElement(['passport', 'drivers_license', 'national_id']),
            'kyc_submitted_at' => $submitted,
        ])->save();
    }

    /**
     * @param  array<int, array{user: User, wallet: Wallet, joined: Carbon}>  $users
     */
    private function seedTransactions(array $users, ?Admin $admin): void
    {
        $rows = 0;

        foreach ($users as $record) {
            /** @var Wallet $wallet */
            $wallet = $record['wallet'];
            $joined = $record['joined'];

            // Days between signing up and today — the span this user can transact in.
            $activeDays = max(0, $joined->diffInDays(Carbon::now()));
            $balance = 0.0;

            // Almost everyone funds the account shortly after signing up.
            if (mt_rand(1, 10) > 2) {
                $balance += $this->createTransaction(
                    $wallet,
                    'deposit',
                    $this->roundedAmount(mt_rand(150, 5000)),
                    $this->weightedStatus(['completed' => 75, 'pending' => 15, 'rejected' => 10]),
                    $this->clampToNow($joined->copy()->addHours(mt_rand(1, 48))),
                    $admin,
                );
                $rows++;
            }

            // Follow-up deposits, spread across the rest of their lifetime.
            foreach (range(1, mt_rand(0, 3)) as $ignored) {
                if ($activeDays < 1) {
                    break;
                }

                $balance += $this->createTransaction(
                    $wallet,
                    'deposit',
                    $this->roundedAmount(mt_rand(100, 8000)),
                    $this->weightedStatus(['completed' => 80, 'pending' => 12, 'rejected' => 8]),
                    $this->momentOn(mt_rand(0, (int) $activeDays)),
                    $admin,
                );
                $rows++;
            }

            // Withdrawals — stored negative, per the app's signed-amount convention.
            foreach (range(1, mt_rand(0, 2)) as $ignored) {
                if ($activeDays < 2 || $balance <= 200) {
                    break;
                }

                $balance += $this->createTransaction(
                    $wallet,
                    'withdrawal',
                    -$this->roundedAmount(mt_rand(50, (int) min(3000, $balance * 0.6))),
                    $this->weightedStatus(['completed' => 55, 'pending' => 30, 'rejected' => 15]),
                    $this->momentOn(mt_rand(0, (int) $activeDays)),
                    $admin,
                );
                $rows++;
            }

            // A sprinkling of platform activity so the feed shows more than money in/out.
            if ($activeDays >= 3 && mt_rand(1, 10) > 6) {
                $amount = $this->roundedAmount(mt_rand(200, 2500));
                $balance -= $amount;
                $this->createTransaction($wallet, 'investment_purchase', -$amount, 'completed', $this->momentOn(mt_rand(0, (int) $activeDays)), $admin);
                $rows++;

                if (mt_rand(1, 10) > 4) {
                    $payout = $this->roundedAmount($amount * (mt_rand(105, 135) / 100));
                    $balance += $payout;
                    $this->createTransaction($wallet, 'roi_payout', $payout, 'completed', $this->momentOn(mt_rand(0, (int) min(6, $activeDays))), $admin);
                    $rows++;
                }
            }

            $wallet->forceFill(['balance' => max(0, round($balance, 2))])->save();
            $record['user']->forceFill(['balance' => max(0, round($balance, 2))])->save();
        }

        $this->command?->info("Seeded {$rows} wallet transactions.");
    }

    private function createTransaction(
        Wallet $wallet,
        string $type,
        float $amount,
        string $status,
        Carbon $at,
        ?Admin $admin,
    ): float {
        $approved = $status !== 'pending';

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => $type,
            'amount' => $amount,
            'currency' => in_array($type, ['deposit', 'withdrawal'], true)
                ? fake()->randomElement(['BTC', 'ETH', 'USDT', 'SOL'])
                : null,
            'status' => $status,
            'note' => $this->noteFor($type),
            'approved_by' => $approved ? $admin?->id : null,
            'approved_at' => $approved ? $this->clampToNow($at->copy()->addHours(mt_rand(1, 12))) : null,
        ])->forceFill(['created_at' => $at, 'updated_at' => $at])->save();

        // Only settled money moves the balance.
        return $status === 'completed' ? $amount : 0.0;
    }

    private function noteFor(string $type): string
    {
        return match ($type) {
            'deposit' => 'Crypto deposit awaiting confirmation',
            'withdrawal' => 'Withdrawal via crypto',
            'investment_purchase' => 'Investment plan subscription',
            'roi_payout' => 'Investment ROI payout',
            default => ucfirst(str_replace('_', ' ', $type)),
        };
    }

    /**
     * @param  array<int, array{user: User, wallet: Wallet, joined: Carbon}>  $users
     */
    private function seedTrades(array $users): void
    {
        $count = 0;

        foreach ($users as $record) {
            if (mt_rand(1, 10) > 4) {
                continue;
            }

            $activeDays = max(0, (int) $record['joined']->diffInDays(Carbon::now()));

            foreach (range(1, mt_rand(1, 4)) as $ignored) {
                $placed = $this->momentOn(mt_rand(0, $activeDays));
                $stake = $this->roundedAmount(mt_rand(20, 800));
                $entry = mt_rand(10000, 90000) + (mt_rand(0, 99) / 100);
                $status = fake()->randomElement(['won', 'won', 'lost', 'lost', 'open']);
                $settled = $status === 'open' ? null : $this->clampToNow($placed->copy()->addMinutes(mt_rand(5, 60)));

                Trade::create([
                    'user_id' => $record['user']->id,
                    'wallet_id' => $record['wallet']->id,
                    'asset' => fake()->randomElement(self::ASSETS),
                    'direction' => fake()->randomElement(['rise', 'fall']),
                    'stake' => $stake,
                    'entry_price' => $entry,
                    'exit_price' => $status === 'open' ? null : $entry * (mt_rand(97, 103) / 100),
                    'payout' => $status === 'won' ? $this->roundedAmount($stake * 1.85) : 0,
                    'status' => $status,
                    'expires_at' => $placed->copy()->addMinutes(mt_rand(5, 60)),
                    'settled_at' => $settled,
                ])->forceFill(['created_at' => $placed, 'updated_at' => $settled ?? $placed])->save();

                $count++;
            }
        }

        $this->command?->info("Seeded {$count} trades.");
    }

    /**
     * @param  array<int, array{user: User, wallet: Wallet, joined: Carbon}>  $users
     */
    private function seedSupportTickets(array $users): void
    {
        $subjects = [
            'deposits' => 'Deposit not credited yet',
            'withdrawals' => 'Withdrawal still pending after 48h',
            'account' => 'Cannot complete KYC verification',
            'trading' => 'Trade settled at the wrong price',
            'other' => 'How do I change my registered email?',
        ];

        $count = 0;

        foreach (fake()->randomElements($users, min(12, count($users))) as $record) {
            $activeDays = max(0, (int) $record['joined']->diffInDays(Carbon::now()));
            $opened = $this->momentOn(mt_rand(0, $activeDays));
            $category = fake()->randomElement(array_keys($subjects));
            $resolved = mt_rand(1, 10) > 6;

            SupportTicket::create([
                'user_id' => $record['user']->id,
                'subject' => $subjects[$category],
                'category' => $category,
                'message' => fake()->paragraph(),
                'status' => $resolved ? 'resolved' : 'open',
                'admin_response' => $resolved ? 'Resolved — thanks for your patience.' : null,
                'responded_at' => $resolved ? $this->clampToNow($opened->copy()->addHours(mt_rand(2, 36))) : null,
            ])->forceFill(['created_at' => $opened, 'updated_at' => $opened])->save();

            $count++;
        }

        $this->command?->info("Seeded {$count} support tickets.");
    }

    private function momentOn(int $daysAgo): Carbon
    {
        // For "today" (daysAgo === 0), a random hour of 6-22 can land later than the
        // actual current time — clamp so a fresh signup never appears to happen "in the
        // future". Any earlier day is unaffected since its whole calendar day has passed.
        return $this->clampToNow(
            Carbon::now()->subDays($daysAgo)->setTime(mt_rand(6, 22), mt_rand(0, 59), mt_rand(0, 59))
        );
    }

    /**
     * Every "N hours/days after X" derivation below can overshoot the real clock when X is
     * recent — clamp so nothing (a review, a settlement, a verification) reads as happening
     * in the future.
     */
    private function clampToNow(Carbon $moment): Carbon
    {
        return $moment->greaterThan(Carbon::now()) ? Carbon::now() : $moment;
    }

    private function demoEmail(string $name, int $index): string
    {
        return Str::slug($name, '.').'.'.$index.'@'.self::DEMO_DOMAIN;
    }

    private function roundedAmount(float $value): float
    {
        return round($value, 2);
    }

    /**
     * @param  array<string, int>  $weights
     */
    private function weightedStatus(array $weights): string
    {
        $roll = mt_rand(1, array_sum($weights));

        foreach ($weights as $status => $weight) {
            $roll -= $weight;

            if ($roll <= 0) {
                return $status;
            }
        }

        return array_key_first($weights);
    }
}
