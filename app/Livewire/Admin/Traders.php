<?php

namespace App\Livewire\Admin;

use App\Models\Trader;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Title('Manage Traders')]
#[Layout('layouts.admin')]
class Traders extends Component
{
    use WithFileUploads;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $tagline = '';

    public string $bio = '';

    public string $tier = 'verified';

    public string $riskLevel = 'medium';

    public string $winRate = '';

    public string $roi30d = '';

    public string $baseCopiers = '0';

    public string $minCopyAmount = '50';

    public string $maxCopyAmount = '';

    public ?TemporaryUploadedFile $avatarFile = null;

    public bool $removeCurrentAvatar = false;

    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

    public string $deletingName = '';

    public int $deletingSubscriptionsCount = 0;

    public string $deleteConfirmation = '';

    public function openCreateModal(): void
    {
        $this->reset(['editingId', 'name', 'tagline', 'bio', 'winRate', 'roi30d', 'maxCopyAmount', 'avatarFile', 'removeCurrentAvatar']);
        $this->tier = 'verified';
        $this->riskLevel = 'medium';
        $this->baseCopiers = '0';
        $this->minCopyAmount = '50';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEditModal(int $traderId): void
    {
        $trader = Trader::findOrFail($traderId);

        $this->editingId = $trader->id;
        $this->name = $trader->name;
        $this->tagline = (string) $trader->tagline;
        $this->bio = (string) $trader->bio;
        $this->tier = $trader->tier;
        $this->riskLevel = $trader->risk_level;
        $this->winRate = (string) $trader->win_rate;
        $this->roi30d = (string) $trader->roi_30d;
        $this->baseCopiers = (string) $trader->base_copiers;
        $this->minCopyAmount = (string) $trader->min_copy_amount;
        $this->maxCopyAmount = (string) $trader->max_copy_amount;
        $this->avatarFile = null;
        $this->removeCurrentAvatar = false;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId', 'name', 'tagline', 'bio', 'winRate', 'roi30d', 'maxCopyAmount', 'avatarFile', 'removeCurrentAvatar']);
        $this->tier = 'verified';
        $this->riskLevel = 'medium';
        $this->baseCopiers = '0';
        $this->minCopyAmount = '50';
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:255'],
            'tier' => ['required', 'in:verified,pro,elite'],
            'riskLevel' => ['required', 'in:low,medium,high'],
            'winRate' => ['required', 'numeric', 'min:0', 'max:100'],
            'roi30d' => ['required', 'numeric'],
            'baseCopiers' => ['required', 'integer', 'min:0'],
            'minCopyAmount' => ['required', 'numeric', 'min:0'],
            'maxCopyAmount' => ['nullable', 'numeric', 'gt:minCopyAmount'],
            'avatarFile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = [
            'name' => $this->name,
            'avatar_initials' => $this->deriveInitials($this->name),
            'tagline' => $this->tagline ?: null,
            'bio' => $this->bio ?: null,
            'tier' => $this->tier,
            'risk_level' => $this->riskLevel,
            'win_rate' => $this->winRate,
            'roi_30d' => $this->roi30d,
            'base_copiers' => $this->baseCopiers,
            'min_copy_amount' => $this->minCopyAmount,
            'max_copy_amount' => $this->maxCopyAmount ?: null,
        ];

        $existing = $this->editingId ? Trader::find($this->editingId) : null;

        if ($this->avatarFile) {
            if ($existing?->avatar_path) {
                Storage::disk('public')->delete($existing->avatar_path);
            }

            $data['avatar_path'] = Trader::storeAvatarFromPath($this->avatarFile->getRealPath());
        } elseif ($this->removeCurrentAvatar && $existing?->avatar_path) {
            Storage::disk('public')->delete($existing->avatar_path);
            $data['avatar_path'] = null;
        }

        if ($this->editingId) {
            Trader::findOrFail($this->editingId)->update($data);
        } else {
            Trader::create([...$data, 'sort_order' => (Trader::max('sort_order') ?? 0) + 1]);
        }

        $this->closeModal();
    }

    public function toggleActive(int $traderId): void
    {
        $trader = Trader::findOrFail($traderId);
        $trader->update(['is_active' => ! $trader->is_active]);
    }

    public function confirmDelete(int $traderId): void
    {
        $trader = Trader::withCount('subscriptions')->findOrFail($traderId);

        $this->deletingId = $trader->id;
        $this->deletingName = $trader->name;
        $this->deletingSubscriptionsCount = $trader->subscriptions_count;
        $this->deleteConfirmation = '';
        $this->resetValidation();
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->reset(['deletingId', 'deletingName', 'deletingSubscriptionsCount', 'deleteConfirmation']);
    }

    public function deleteTrader(): void
    {
        $this->validate([
            'deleteConfirmation' => ['required', 'in:DELETE'],
        ], [
            'deleteConfirmation.in' => 'Type DELETE (all caps) to confirm.',
        ]);

        $trader = Trader::findOrFail($this->deletingId);

        if ($trader->avatar_path) {
            Storage::disk('public')->delete($trader->avatar_path);
        }

        $trader->delete();

        $this->closeDeleteModal();
    }

    private function deriveInitials(string $name): string
    {
        return strtoupper(collect(explode(' ', $name))->map(fn ($w) => substr($w, 0, 1))->take(2)->implode(''));
    }

    public function render(): View
    {
        return view('livewire.admin.traders', [
            'traders' => Trader::orderBy('sort_order')->get(),
            'editingTrader' => $this->editingId ? Trader::find($this->editingId) : null,
        ]);
    }
}
