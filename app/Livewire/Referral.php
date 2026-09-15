<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Referrals')]
class Referral extends Component
{
    public function copyLink(): void
    {
        $this->dispatch('copy-referral-link');
    }

    public function render()
    {
        $user     = Auth::user();
        $referrer = $user->referrer;
        $referrals = $user->referrals()->with('wallet')->latest()->get();

        $referralEarnings = $user->wallet
            ? $user->wallet->totalReferralBonus()
            : 0.0;

        return view('livewire.referral', [
            'referralCode'     => $user->referral_code ?? 'N/A',
            'referralLink'     => url('/register?ref=' . ($user->referral_code ?? '')),
            'sponsor'          => $referrer?->name ?? '—',
            'totalReferrals'   => $referrals->count(),
            'referralEarnings' => $referralEarnings,
            'referrals'        => $referrals,
        ]);
    }
}
