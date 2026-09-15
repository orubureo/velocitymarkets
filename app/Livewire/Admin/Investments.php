<?php

namespace App\Livewire\Admin;

use App\Models\UserInvestment;
use App\Models\WalletTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Investments')]
#[Layout('layouts.admin')]
class Investments extends Component
{
    public bool $showCreditModal = false;

    public ?int $creditingInvestmentId = null;

    public string $creditAmount = '';

    public function openCreditModal(int $investmentId): void
    {
        $this->creditingInvestmentId = $investmentId;
        $this->creditAmount = '';
        $this->showCreditModal = true;
    }

    public function closeCreditModal(): void
    {
        $this->showCreditModal = false;
        $this->reset(['creditingInvestmentId', 'creditAmount']);
    }

    public function creditRoi(): void
    {
        $this->validate([
            'creditAmount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $investment = UserInvestment::with('plan')->findOrFail($this->creditingInvestmentId);
        $amount = (float) $this->creditAmount;

        WalletTransaction::create([
            'wallet_id' => $investment->wallet_id,
            'type' => 'roi_payout',
            'amount' => $amount,
            'status' => 'completed',
            'reference_type' => UserInvestment::class,
            'reference_id' => $investment->id,
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
            'note' => "ROI payout — {$investment->plan->name}",
        ]);

        $investment->wallet->increment('balance', $amount);

        $this->closeCreditModal();
    }

    public function markCompleted(int $investmentId): void
    {
        UserInvestment::findOrFail($investmentId)->update(['status' => 'completed']);
    }

    public function render(): View
    {
        return view('livewire.admin.investments', [
            'investments' => UserInvestment::where('status', 'active')->with(['user', 'plan'])->latest()->get(),
        ]);
    }
}
