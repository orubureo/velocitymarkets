<?php

namespace App\Livewire\Wallet;

use App\Models\CryptoWallet;
use App\Models\WalletTransaction;
use App\Services\QrCodeService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
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

    public ?TemporaryUploadedFile $proofFile = null;

    public function selectCurrency(string $currency): void
    {
        $wallets = CryptoWallet::where('currency', $currency)->where('is_active', true)->get();

        if ($wallets->isEmpty()) {
            session()->flash('error', 'No deposit address is configured for this option yet. Please choose another.');

            return;
        }

        $this->currency = $currency;

        if ($wallets->count() > 1) {
            // More than one active network configured for this currency —
            // ask which one, same as the old USDT-only flow but for any
            // currency the admin has set up multiple networks for.
            $this->awaitingNetwork = true;

            return;
        }

        $wallet = $wallets->first();
        $this->network = (string) $wallet->network;
        $this->cryptoWalletId = $wallet->id;
        $this->step = 2;
    }

    public function selectNetwork(string $network): void
    {
        $wallet = CryptoWallet::where('currency', $this->currency)
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

    public function backToMethod(): void
    {
        $this->step = 1;
        $this->awaitingNetwork = false;
        $this->currency = '';
        $this->network = '';
        $this->cryptoWalletId = null;
    }

    public function proceedToPayment(): void
    {
        $this->validate([
            'amount' => ['required', 'numeric', 'min:10'],
        ]);

        $this->step = 3;
    }

    public function backToAmount(): void
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

    public function confirmSent(): void
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
            'wallet_id' => Auth::guard('web')->user()->wallet->id,
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

    public function render(QrCodeService $qr): View
    {
        $activeWallets = CryptoWallet::where('is_active', true)->get();
        $walletsByCurrency = $activeWallets->groupBy('currency');

        $availableCurrencies = $walletsByCurrency->keys()->values();
        $multiNetworkCurrencies = $walletsByCurrency->filter(fn ($wallets) => $wallets->count() > 1)->keys()->values();
        $availableNetworks = $this->awaitingNetwork
            ? $walletsByCurrency->get($this->currency, collect())->pluck('network')->filter()->values()
            : collect();

        $selectedWallet = $this->cryptoWalletId
            ? $activeWallets->firstWhere('id', $this->cryptoWalletId)
            : null;

        return view('livewire.wallet.deposit', [
            'availableCurrencies' => $availableCurrencies,
            'multiNetworkCurrencies' => $multiNetworkCurrencies,
            'availableNetworks' => $availableNetworks,
            'selectedWallet' => $selectedWallet,
            'qrCodeSvg' => ($this->step === 3 && $selectedWallet)
                ? $qr->svgFor($selectedWallet->address)
                : null,
        ]);
    }
}
