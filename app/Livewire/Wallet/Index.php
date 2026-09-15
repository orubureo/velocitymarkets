<?php

namespace App\Livewire\Wallet;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Wallet')]
class Index extends Component
{
    #[Url]
    public string $tab = 'deposit';

    public function mount(): void
    {
        if (! in_array($this->tab, ['deposit', 'withdraw'], true)) {
            $this->tab = 'deposit';
        }
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function render(): View
    {
        return view('livewire.wallet.index', [
            'balance' => Auth::guard('web')->user()->wallet->balance,
        ]);
    }
}
