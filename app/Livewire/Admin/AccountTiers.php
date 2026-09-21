<?php

namespace App\Livewire\Admin;

use App\Models\AccountTier;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Account Tiers')]
#[Layout('layouts.admin')]
class AccountTiers extends Component
{
    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public string $price = '';

    public string $dailyProfitPercent = '';

    public string $totalReturnPercent = '';

    public string $referralBonusPercent = '';

    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

    public string $deletingName = '';

    public int $deletingUsersCount = 0;

    public string $deleteConfirmation = '';

    public function openCreateModal(): void
    {
        $this->reset(['editingId', 'name', 'description', 'price', 'dailyProfitPercent', 'totalReturnPercent', 'referralBonusPercent']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEditModal(int $tierId): void
    {
        $tier = AccountTier::findOrFail($tierId);

        $this->editingId = $tier->id;
        $this->name = $tier->name;
        $this->description = (string) $tier->description;
        $this->price = (string) $tier->price;
        $this->dailyProfitPercent = (string) $tier->daily_profit_percent;
        $this->totalReturnPercent = (string) $tier->total_return_percent;
        $this->referralBonusPercent = (string) $tier->referral_bonus_percent;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId', 'name', 'description', 'price', 'dailyProfitPercent', 'totalReturnPercent', 'referralBonusPercent']);
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0'],
            'dailyProfitPercent' => ['required', 'numeric', 'min:0'],
            'totalReturnPercent' => ['required', 'numeric', 'min:0'],
            'referralBonusPercent' => ['required', 'numeric', 'min:0'],
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description ?: null,
            'price' => $this->price,
            'daily_profit_percent' => $this->dailyProfitPercent,
            'total_return_percent' => $this->totalReturnPercent,
            'referral_bonus_percent' => $this->referralBonusPercent,
        ];

        if ($this->editingId) {
            AccountTier::findOrFail($this->editingId)->update($data);
        } else {
            AccountTier::create([...$data, 'sort_order' => (AccountTier::max('sort_order') ?? 0) + 1]);
        }

        $this->closeModal();
    }

    public function toggleActive(int $tierId): void
    {
        $tier = AccountTier::findOrFail($tierId);
        $tier->update(['is_active' => ! $tier->is_active]);
    }

    public function confirmDelete(int $tierId): void
    {
        $tier = AccountTier::withCount('users')->findOrFail($tierId);

        $this->deletingId = $tier->id;
        $this->deletingName = $tier->name;
        $this->deletingUsersCount = $tier->users_count;
        $this->deleteConfirmation = '';
        $this->resetValidation();
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->reset(['deletingId', 'deletingName', 'deletingUsersCount', 'deleteConfirmation']);
    }

    public function deleteTier(): void
    {
        $this->validate([
            'deleteConfirmation' => ['required', 'in:DELETE'],
        ], [
            'deleteConfirmation.in' => 'Type DELETE (all caps) to confirm.',
        ]);

        AccountTier::findOrFail($this->deletingId)->delete();

        $this->closeDeleteModal();
    }

    public function render(): View
    {
        return view('livewire.admin.account-tiers', [
            'tiers' => AccountTier::orderBy('sort_order')->get(),
        ]);
    }
}
