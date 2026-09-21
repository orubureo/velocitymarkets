<?php

namespace App\Livewire\Admin;

use App\Models\InvestmentPlan;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Investment Plans')]
#[Layout('layouts.admin')]
class InvestmentPlans extends Component
{
    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public string $minAmount = '';

    public string $maxAmount = '';

    public string $roiPercent = '';

    public string $durationDays = '';

    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

    public string $deletingName = '';

    public int $deletingInvestmentsCount = 0;

    public string $deleteConfirmation = '';

    public function openCreateModal(): void
    {
        $this->reset(['editingId', 'name', 'description', 'minAmount', 'maxAmount', 'roiPercent', 'durationDays']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEditModal(int $planId): void
    {
        $plan = InvestmentPlan::findOrFail($planId);

        $this->editingId = $plan->id;
        $this->name = $plan->name;
        $this->description = (string) $plan->description;
        $this->minAmount = (string) $plan->min_amount;
        $this->maxAmount = (string) $plan->max_amount;
        $this->roiPercent = (string) $plan->roi_percent;
        $this->durationDays = (string) $plan->duration_days;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId', 'name', 'description', 'minAmount', 'maxAmount', 'roiPercent', 'durationDays']);
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'minAmount' => ['required', 'numeric', 'min:0'],
            'maxAmount' => ['required', 'numeric', 'gt:minAmount'],
            'roiPercent' => ['required', 'numeric', 'min:0'],
            'durationDays' => ['required', 'integer', 'min:1'],
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description ?: null,
            'min_amount' => $this->minAmount,
            'max_amount' => $this->maxAmount,
            'roi_percent' => $this->roiPercent,
            'duration_days' => $this->durationDays,
        ];

        if ($this->editingId) {
            InvestmentPlan::findOrFail($this->editingId)->update($data);
        } else {
            InvestmentPlan::create([...$data, 'sort_order' => (InvestmentPlan::max('sort_order') ?? 0) + 1]);
        }

        $this->closeModal();
    }

    public function toggleActive(int $planId): void
    {
        $plan = InvestmentPlan::findOrFail($planId);
        $plan->update(['is_active' => ! $plan->is_active]);
    }

    public function confirmDelete(int $planId): void
    {
        $plan = InvestmentPlan::withCount('investments')->findOrFail($planId);

        $this->deletingId = $plan->id;
        $this->deletingName = $plan->name;
        $this->deletingInvestmentsCount = $plan->investments_count;
        $this->deleteConfirmation = '';
        $this->resetValidation();
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->reset(['deletingId', 'deletingName', 'deletingInvestmentsCount', 'deleteConfirmation']);
    }

    public function deletePlan(): void
    {
        $this->validate([
            'deleteConfirmation' => ['required', 'in:DELETE'],
        ], [
            'deleteConfirmation.in' => 'Type DELETE (all caps) to confirm.',
        ]);

        InvestmentPlan::findOrFail($this->deletingId)->delete();

        $this->closeDeleteModal();
    }

    public function render(): View
    {
        return view('livewire.admin.investment-plans', [
            'plans' => InvestmentPlan::orderBy('sort_order')->get(),
        ]);
    }
}
