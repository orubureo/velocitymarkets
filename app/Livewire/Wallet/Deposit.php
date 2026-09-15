<?php

namespace App\Livewire\Wallet;

use App\Models\CryptoWallet;
use App\Models\WalletTransaction;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Deposit Funds')]
class Deposit extends Component
{
    use WithFileUploads;

    public int $step = 1;

    public bool $awaitingNetwork = false;

    public string $currency = '';

    public string $network = '';

    public ?int $cryptoWalletId = null;

    public string $amount = '';

    public $proofFile = null;

    public function selectCurrency(string $currency)
    {
        if ($currency === 'USDT') {
            $this->currency = 'USDT';
            $this->awaitingNetwork = true;

            return;
        }

        $wallet = CryptoWallet::where('currency', $currency)
            ->whereNull('network')
            ->where('is_active', true)
            ->first();

        if (! $wallet) {
            session()->flash('error', 'No deposit address is configured for this option yet. Please choose another.');

            return;
        }

        $this->currency = $currency;
        $this->network = '';
        $this->cryptoWalletId = $wallet->id;
        $this->step = 2;
    }

    public function selectNetwork(string $network)
    {
        $wallet = CryptoWallet::where('currency', 'USDT')
            ->where('network', $network)
            ->where('is_active', true)
            ->first();

        if (! $wallet) {
            session()->flash('error', 'No deposit address is configured for this network yet. Please choose another.');

            return;
        }

        $this->network = $network;
        $this->cryptoWalletId = $wallet->id;
        $this->awaitingNetwork = false;
        $this->step = 2;
    }

    public function backToMethod()
    {
        $this->step = 1;
        $this->awaitingNetwork = false;
        $this->currency = '';
        $this->network = '';
        $this->cryptoWalletId = null;
    }

    public function proceedToPayment()
    {
        $this->validate([
            'amount' => ['required', 'numeric', 'min:10'],
        ]);

        $this->step = 3;
    }

    public function backToAmount()
    {
        $this->step = 2;
    }

    public function currencyColor(): string
    {
        return match ($this->currency) {
            'BTC' => 'blue',
            'ETH' => 'violet',
            'USDT' => 'teal',
            'SOL' => 'purple',
            default => 'teal',
        };
    }

    public function confirmSent()
    {
        $this->validate([
            'amount' => ['required', 'numeric', 'min:10'],
            'proofFile' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $wallet = CryptoWallet::findOrFail($this->cryptoWalletId);

        $proofPath = $this->proofFile
            ? $this->proofFile->store('deposit-proofs/'.Auth::id(), 'local')
            : null;

        WalletTransaction::create([
            'wallet_id' => Auth::user()->wallet->id,
            'type' => 'deposit',
            'amount' => $this->amount,
            'currency' => $wallet->currency,
            'status' => 'pending',
            'reference_type' => CryptoWallet::class,
            'reference_id' => $wallet->id,
            'proof_path' => $proofPath,
            'note' => "Crypto deposit — {$wallet->label()} to {$wallet->address}",
        ]);

        session()->flash('status', 'Deposit request submitted. It will be credited once the admin confirms your payment.');

        $this->reset(['step', 'awaitingNetwork', 'currency', 'network', 'cryptoWalletId', 'amount', 'proofFile']);
    }

    public function render(QrCodeService $qr)
    {
        $activeWallets = CryptoWallet::where('is_active', true)->get();

        $availableCurrencies = $activeWallets->pluck('currency')->unique()->values();
        $usdtNetworks = $activeWallets->where('currency', 'USDT')->pluck('network')->filter()->values();

        $selectedWallet = $this->cryptoWalletId
            ? $activeWallets->firstWhere('id', $this->cryptoWalletId)
            : null;

        return view('livewire.wallet.deposit', [
            'availableCurrencies' => $availableCurrencies,
            'usdtNetworks' => $usdtNetworks,
            'selectedWallet' => $selectedWallet,
            'qrCodeSvg' => ($this->step === 3 && $selectedWallet)
                ? $qr->svgFor($selectedWallet->address)
                : null,
        ]);
    }
}
