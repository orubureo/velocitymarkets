<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Manage KYC')]
#[Layout('layouts.admin')]
class Kyc extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = 'all';

    public bool $showRejectModal = false;

    public ?int $rejectingUserId = null;

    public string $rejectionReason = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function approve(int $userId): void
    {
        $user = User::findOrFail($userId);

        $user->update([
            'kyc_status' => 'approved',
            'kyc_reviewed_at' => now(),
            'kyc_rejection_reason' => null,
        ]);
    }

    public function openRejectModal(int $userId): void
    {
        $this->rejectingUserId = $userId;
        $this->rejectionReason = '';
        $this->showRejectModal = true;
    }

    public function closeRejectModal(): void
    {
        $this->showRejectModal = false;
        $this->reset(['rejectingUserId', 'rejectionReason']);
    }

    public function reject(): void
    {
        $this->validate([
            'rejectionReason' => ['required', 'string', 'max:255'],
        ]);

        $user = User::findOrFail($this->rejectingUserId);

        $user->update([
            'kyc_status' => 'rejected',
            'kyc_reviewed_at' => now(),
            'kyc_rejection_reason' => $this->rejectionReason,
        ]);

        $this->closeRejectModal();
    }

    public function render(): View
    {
        $query = User::query()
            ->where('kyc_status', '!=', 'none')
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('kyc_status', $this->statusFilter);
            })
            ->orderByRaw("CASE WHEN kyc_status = 'pending' THEN 0 ELSE 1 END")
            ->latest('kyc_submitted_at');

        return view('livewire.admin.kyc', [
            'kycApplications' => $query->paginate(15),
        ]);
    }
}
