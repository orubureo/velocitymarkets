<?php

namespace App\Livewire\Admin;

use App\Models\UserSignal;
use App\Models\WalletTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Signals')]
#[Layout('layouts.admin')]
class Signals extends Component
{
    public bool $showCreditModal = false;

    public ?int $creditingSignalId = null;

    public string $creditAmount = '';

    public function openCreditModal(int $signalId): void
    {
        $this->creditingSignalId = $signalId;
        $this->creditAmount = '';
        $this->showCreditModal = true;
    }

    public function closeCreditModal(): void
    {
        $this->showCreditModal = false;
        $this->reset(['creditingSignalId', 'creditAmount']);
    }

    public function creditRoi(): void
    {
        $this->validate([
            'creditAmount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $signal = UserSignal::with('tier')->findOrFail($this->creditingSignalId);
        $amount = (float) $this->creditAmount;

        WalletTransaction::create([
            'wallet_id' => $signal->wallet_id,
            'type' => 'roi_payout',
            'amount' => $amount,
            'status' => 'completed',
            'reference_type' => UserSignal::class,
            'reference_id' => $signal->id,
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
            'note' => "ROI payout — {$signal->tier->name}",
        ]);

        $signal->wallet->increment('balance', $amount);

        $this->closeCreditModal();
    }

    public function markCompleted(int $signalId): void
    {
        UserSignal::findOrFail($signalId)->update(['status' => 'completed']);
    }

    public function render(): View
    {
        return view('livewire.admin.signals', [
            'signals' => UserSignal::where('status', 'active')->with(['user', 'tier'])->latest()->get(),
        ]);
    }
}
