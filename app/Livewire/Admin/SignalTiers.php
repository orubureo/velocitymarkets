<?php

namespace App\Livewire\Admin;

use App\Models\SignalTier;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Signal Tiers')]
#[Layout('layouts.admin')]
class SignalTiers extends Component
{
    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public string $percent = '';

    public string $winRatePercent = '';

    public string $roiPercent = '';

    public string $durationDays = '';

    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

    public string $deletingName = '';

    public int $deletingSignalsCount = 0;

    public string $deleteConfirmation = '';

    public function openCreateModal(): void
    {
        $this->reset(['editingId', 'name', 'description', 'percent', 'winRatePercent', 'roiPercent', 'durationDays']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEditModal(int $tierId): void
    {
        $tier = SignalTier::findOrFail($tierId);

        $this->editingId = $tier->id;
        $this->name = $tier->name;
        $this->description = (string) $tier->description;
        $this->percent = (string) $tier->percent;
        $this->winRatePercent = (string) $tier->win_rate_percent;
        $this->roiPercent = (string) $tier->roi_percent;
        $this->durationDays = (string) $tier->duration_days;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId', 'name', 'description', 'percent', 'winRatePercent', 'roiPercent', 'durationDays']);
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'percent' => ['required', 'integer', 'min:1', 'max:100'],
            'winRatePercent' => ['required', 'numeric', 'min:0', 'max:100'],
            'roiPercent' => ['required', 'numeric', 'min:0'],
            'durationDays' => ['required', 'integer', 'min:1'],
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description ?: null,
            'percent' => $this->percent,
            'win_rate_percent' => $this->winRatePercent,
            'roi_percent' => $this->roiPercent,
            'duration_days' => $this->durationDays,
        ];

        if ($this->editingId) {
            SignalTier::findOrFail($this->editingId)->update($data);
        } else {
            SignalTier::create([...$data, 'sort_order' => (SignalTier::max('sort_order') ?? 0) + 1]);
        }

        $this->closeModal();
    }

    public function toggleActive(int $tierId): void
    {
        $tier = SignalTier::findOrFail($tierId);
        $tier->update(['is_active' => ! $tier->is_active]);
    }

    public function confirmDelete(int $tierId): void
    {
        $tier = SignalTier::withCount('userSignals')->findOrFail($tierId);

        $this->deletingId = $tier->id;
        $this->deletingName = $tier->name;
        $this->deletingSignalsCount = $tier->user_signals_count;
        $this->deleteConfirmation = '';
        $this->resetValidation();
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->reset(['deletingId', 'deletingName', 'deletingSignalsCount', 'deleteConfirmation']);
    }

    public function deleteTier(): void
    {
        $this->validate([
            'deleteConfirmation' => ['required', 'in:DELETE'],
        ], [
            'deleteConfirmation.in' => 'Type DELETE (all caps) to confirm.',
        ]);

        SignalTier::findOrFail($this->deletingId)->delete();

        $this->closeDeleteModal();
    }

    public function render(): View
    {
        return view('livewire.admin.signal-tiers', [
            'tiers' => SignalTier::orderBy('sort_order')->get(),
        ]);
    }
}
