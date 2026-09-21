<?php

namespace App\Livewire\Wallet;

use App\Models\WalletTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Withdraw Funds')]
class Withdraw extends Component
{
    const CRYPTO_CURRENCIES = ['BTC', 'ETH', 'USDT'];

    public int $step = 1;

    public bool $awaitingCurrency = false;

    public string $method = '';

    public string $currency = '';

    public string $amount = '';

    public string $destination = '';

    public function selectMethod(string $method): void
    {
        if ($method === 'crypto') {
            $this->method = 'crypto';
            $this->awaitingCurrency = true;

            return;
        }

        $this->method = 'bank_transfer';
        $this->currency = '';
        $this->destination = $this->savedDestination() ?? '';
        $this->step = 2;
    }

    public function selectCurrency(string $currency): void
    {
        $this->currency = $currency;
        $this->destination = $this->savedDestination() ?? '';
        $this->awaitingCurrency = false;
        $this->step = 2;
    }

    public function backToMethod(): void
    {
        $this->step = 1;
        $this->awaitingCurrency = false;
        $this->method = '';
        $this->currency = '';
        $this->destination = '';
    }

    public function proceedToReview(): void
    {
        $this->validateAmountAndDestination();

        $this->step = 3;
    }

    public function backToAmount(): void
    {
        $this->step = 2;
    }

    public function submit(): void
    {
        if (Auth::guard('web')->user()->withdrawals_paused) {
            session()->flash('error', 'Withdrawals are currently paused for your account. Please contact support.');

            return;
        }

        $wallet = Auth::guard('web')->user()->wallet;

        $this->validateAmountAndDestination();

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'withdrawal',
            'amount' => -(float) $this->amount, // stored negative — signed amount convention
            'currency' => $this->method === 'crypto' ? $this->currency : null,
            'status' => 'pending',
            'note' => "Withdrawal via {$this->methodLabel()} to {$this->destination}",
        ]);

        session()->flash('status', 'Withdrawal request submitted. Awaiting admin approval.');

        $this->reset(['step', 'awaitingCurrency', 'method', 'currency', 'amount', 'destination']);
    }

    public function methodLabel(): string
    {
        return $this->method === 'crypto' ? "{$this->currency} (Crypto)" : 'Bank Transfer';
    }

    public function methodColor(): string
    {
        if ($this->method !== 'crypto') {
            return 'blue';
        }

        return match ($this->currency) {
            'BTC' => 'blue',
            'ETH' => 'violet',
            'USDT' => 'teal',
            default => 'teal',
        };
    }

    protected function validateAmountAndDestination(): void
    {
        $wallet = Auth::guard('web')->user()->wallet;

        $this->validate([
            'amount' => ['required', 'numeric', 'min:10', 'max:'.$wallet->balance],
            'destination' => ['required', 'string', 'max:255'],
        ], [
            'amount.max' => 'Withdrawal amount cannot exceed your available balance.',
        ]);
    }

    protected function savedDestination(): ?string
    {
        $user = Auth::guard('web')->user();

        if ($this->method === 'bank_transfer') {
            return $user->bank_account_number
                ? trim("{$user->bank_name} — {$user->bank_account_name} ({$user->bank_account_number})")
                : null;
        }

        return match ($this->currency) {
            'BTC' => $user->btc_address,
            'ETH' => $user->eth_address,
            'USDT' => $user->usdt_address,
            default => null,
        };
    }

    public function render(): View
    {
        return view('livewire.wallet.withdraw', [
            'balance' => Auth::guard('web')->user()->wallet->balance,
            'cryptoCurrencies' => self::CRYPTO_CURRENCIES,
            'withdrawalsPaused' => Auth::guard('web')->user()->withdrawals_paused,
        ]);
    }
}
