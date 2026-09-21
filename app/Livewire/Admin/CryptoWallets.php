<?php

namespace App\Livewire\Admin;

use App\Models\CryptoWallet;
use App\Models\WalletTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Crypto Wallets')]
#[Layout('layouts.admin')]
class CryptoWallets extends Component
{
    public bool $showModal = false;

    public ?int $editingId = null;

    public string $currency = 'BTC';

    public string $network = '';

    public string $address = '';

    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

    public string $deletingLabel = '';

    public int $deletingDepositsCount = 0;

    public string $deleteConfirmation = '';

    public function updatedCurrency(): void
    {
        // The valid network list is different per currency, so whatever was
        // picked before almost certainly doesn't apply to the new one.
        $this->network = '';
    }

    public function openCreateModal(): void
    {
        $this->reset(['editingId', 'network', 'address']);
        $this->currency = 'BTC';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEditModal(int $walletId): void
    {
        $wallet = CryptoWallet::findOrFail($walletId);

        $this->editingId = $wallet->id;
        $this->currency = $wallet->currency;
        $this->network = (string) $wallet->network;
        $this->address = $wallet->address;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId', 'network', 'address']);
        $this->currency = 'BTC';
    }

    public function save(): void
    {
        $this->validate([
            'currency' => ['required', Rule::in(CryptoWallet::CURRENCIES)],
            'network' => ['required', Rule::in(CryptoWallet::networksFor($this->currency))],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $exists = CryptoWallet::where('currency', $this->currency)
            ->where('network', $this->network)
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($exists) {
            $this->addError('address', 'A wallet address for this currency and network already exists.');

            return;
        }

        $data = [
            'currency' => $this->currency,
            'network' => $this->network,
            'address' => $this->address,
        ];

        if ($this->editingId) {
            CryptoWallet::findOrFail($this->editingId)->update($data);
        } else {
            CryptoWallet::create($data);
        }

        $this->closeModal();
    }

    public function toggleActive(int $walletId): void
    {
        $wallet = CryptoWallet::findOrFail($walletId);
        $wallet->update(['is_active' => ! $wallet->is_active]);
    }

    public function confirmDelete(int $walletId): void
    {
        $wallet = CryptoWallet::findOrFail($walletId);

        $this->deletingId = $wallet->id;
        $this->deletingLabel = $wallet->label();
        $this->deletingDepositsCount = WalletTransaction::where('reference_type', CryptoWallet::class)
            ->where('reference_id', $wallet->id)
            ->count();
        $this->deleteConfirmation = '';
        $this->resetValidation();
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->reset(['deletingId', 'deletingLabel', 'deletingDepositsCount', 'deleteConfirmation']);
    }

    public function deleteWallet(): void
    {
        $this->validate([
            'deleteConfirmation' => ['required', 'in:DELETE'],
        ], [
            'deleteConfirmation.in' => 'Type DELETE (all caps) to confirm.',
        ]);

        CryptoWallet::findOrFail($this->deletingId)->delete();

        $this->closeDeleteModal();
    }

    public function render(): View
    {
        return view('livewire.admin.crypto-wallets', [
            'wallets' => CryptoWallet::orderBy('currency')->orderBy('network')->get(),
            'networkOptions' => CryptoWallet::networksFor($this->currency),
        ]);
    }
}
