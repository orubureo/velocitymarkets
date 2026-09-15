<?php

namespace App\Livewire\Admin;

use App\Models\CryptoWallet;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Crypto Wallets')]
#[Layout('layouts.admin')]
class CryptoWallets extends Component
{
    public string $currency = 'BTC';

    public string $network = '';

    public string $address = '';

    public function updatedCurrency(): void
    {
        if ($this->currency !== 'USDT') {
            $this->network = '';
        }
    }

    public function addWallet(): void
    {
        $this->validate([
            'currency' => ['required', Rule::in(CryptoWallet::CURRENCIES)],
            'network' => $this->currency === 'USDT'
                ? ['required', Rule::in(CryptoWallet::NETWORKS)]
                : ['nullable'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $network = $this->currency === 'USDT' ? $this->network : null;

        $exists = CryptoWallet::where('currency', $this->currency)
            ->when($network, fn ($q) => $q->where('network', $network), fn ($q) => $q->whereNull('network'))
            ->exists();

        if ($exists) {
            $this->addError('address', 'A wallet address for this currency'.($network ? ' and network' : '').' already exists.');

            return;
        }

        CryptoWallet::create([
            'currency' => $this->currency,
            'network' => $network,
            'address' => $this->address,
        ]);

        $this->reset(['address', 'network']);
    }

    public function toggleActive(int $walletId): void
    {
        $wallet = CryptoWallet::findOrFail($walletId);
        $wallet->update(['is_active' => ! $wallet->is_active]);
    }

    public function render(): View
    {
        return view('livewire.admin.crypto-wallets', [
            'wallets' => CryptoWallet::orderBy('currency')->orderBy('network')->get(),
        ]);
    }
}
