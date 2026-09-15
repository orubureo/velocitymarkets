<?php

namespace App\Livewire\Settings;

use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Payment settings')]
class Payment extends Component
{
    public string $bank_name = '';

    public string $bank_account_name = '';

    public string $bank_account_number = '';

    public string $swift_code = '';

    public string $btc_address = '';

    public string $eth_address = '';

    public string $ltc_address = '';

    public string $usdt_address = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->bank_name = $user->bank_name ?? '';
        $this->bank_account_name = $user->bank_account_name ?? '';
        $this->bank_account_number = $user->bank_account_number ?? '';
        $this->swift_code = $user->swift_code ?? '';
        $this->btc_address = $user->btc_address ?? '';
        $this->eth_address = $user->eth_address ?? '';
        $this->ltc_address = $user->ltc_address ?? '';
        $this->usdt_address = $user->usdt_address ?? '';
    }

    /**
     * Update the payment information for the currently authenticated user.
     */
    public function updatePaymentInformation(): void
    {
        $validated = $this->validate([
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:255'],
            'swift_code' => ['nullable', 'string', 'max:255'],
            'btc_address' => ['nullable', 'string', 'max:255'],
            'eth_address' => ['nullable', 'string', 'max:255'],
            'ltc_address' => ['nullable', 'string', 'max:255'],
            'usdt_address' => ['nullable', 'string', 'max:255'],
        ]);

        Auth::user()->fill($validated)->save();

        Flux::toast(variant: 'success', text: __('Payment methods updated.'));
    }
}
