<?php

namespace App\Livewire\Admin;

use App\Models\InvestmentPlan;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Investment Plans')]
#[Layout('layouts.admin')]
class InvestmentPlans extends Component
{
    public string $name = '';

    public string $description = '';

    public string $minAmount = '';

    public string $maxAmount = '';

    public string $roiPercent = '';

    public string $durationDays = '';

    public function addPlan()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'minAmount' => ['required', 'numeric', 'min:0'],
            'maxAmount' => ['required', 'numeric', 'gt:minAmount'],
            'roiPercent' => ['required', 'numeric', 'min:0'],
            'durationDays' => ['required', 'integer', 'min:1'],
        ]);

        InvestmentPlan::create([
            'name' => $this->name,
            'description' => $this->description ?: null,
            'min_amount' => $this->minAmount,
            'max_amount' => $this->maxAmount,
            'roi_percent' => $this->roiPercent,
            'duration_days' => $this->durationDays,
            'sort_order' => (InvestmentPlan::max('sort_order') ?? 0) + 1,
        ]);

        $this->reset(['name', 'description', 'minAmount', 'maxAmount', 'roiPercent', 'durationDays']);
    }

    public function toggleActive(int $planId)
    {
        $plan = InvestmentPlan::findOrFail($planId);
        $plan->update(['is_active' => ! $plan->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.investment-plans', [
            'plans' => InvestmentPlan::orderBy('sort_order')->get(),
        ]);
    }
}
