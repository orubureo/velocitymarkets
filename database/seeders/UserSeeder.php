<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstWhere('email', 'user@velocitymarkets.test');

        if (! $user) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'user@velocitymarkets.test',
                'password' => Hash::make('password'),
            ]);
        }

        // DatabaseSeeder mutes model events (WithoutModelEvents), so the
        // User::creating/created listeners that normally set the referral
        // code and auto-create the wallet won't fire here — do both explicitly.
        if (! $user->referral_code) {
            $user->forceFill(['referral_code' => Str::upper(Str::random(8))])->save();
        }

        $wallet = $user->wallet ?? Wallet::create(['user_id' => $user->id, 'balance' => 0]);

        $wallet->update(['balance' => 10000]);
    }
}
