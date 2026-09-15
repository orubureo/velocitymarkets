<?php

namespace App\Livewire\Investment;

use App\Models\InvestmentPlan;
use App\Models\UserInvestment;
use App\Models\WalletTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Investment Plans')]
class Plans extends Component
{
    public ?int $selectedPlanId = null;

    public string $amount = '';

    public bool $showInvestModal = false;

    public function openInvestModal(int $planId): void
    {
        $this->selectedPlanId = $planId;
        $this->amount = '';
        $this->showInvestModal = true;
    }

    public function closeInvestModal(): void
    {
        $this->showInvestModal = false;
        $this->reset(['selectedPlanId', 'amount']);
    }

    public function invest(): void
    {
        $plan = InvestmentPlan::findOrFail($this->selectedPlanId);
        $wallet = Auth::guard('web')->user()->wallet;

        $maxAllowed = min((float) $plan->max_amount, (float) $wallet->balance);

        $this->validate([
            'amount' => ['required', 'numeric', 'min:'.$plan->min_amount, 'max:'.$maxAllowed],
        ], [
            'amount.max' => 'Amount exceeds the plan maximum or your available balance.',
        ]);

        $amount = (float) $this->amount;

        $investment = UserInvestment::create([
            'user_id' => Auth::id(),
            'wallet_id' => $wallet->id,
            'investment_plan_id' => $plan->id,
            'amount' => $amount,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays($plan->duration_days),
        ]);

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'investment_purchase',
            'amount' => -$amount,
            'status' => 'completed',
            'reference_type' => UserInvestment::class,
            'reference_id' => $investment->id,
            'note' => "Invested in {$plan->name}",
        ]);

        $wallet->decrement('balance', $amount);

        session()->flash('status', "You've invested \${$this->amount} in {$plan->name}.");

        $this->closeInvestModal();
    }

    public function render(): View
    {
        return view('livewire.investment.plans', [
            'plans' => InvestmentPlan::where('is_active', true)->orderBy('sort_order')->get(),
            'myInvestments' => UserInvestment::where('user_id', Auth::id())->with('plan')->latest()->get(),
            'selectedPlan' => $this->selectedPlanId ? InvestmentPlan::find($this->selectedPlanId) : null,
            'balance' => Auth::guard('web')->user()->wallet->balance,
        ]);
    }
}
