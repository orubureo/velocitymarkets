<?php

namespace App\Livewire\Admin;

use App\Models\SupportTicket;
use App\Notifications\SupportTicketReplyNotification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Support Tickets')]
#[Layout('layouts.admin')]
class SupportTickets extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public string $statusFilter = 'all';

    public bool $showRespondModal = false;

    public ?int $respondingTicketId = null;

    public string $response = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function markInProgress(int $ticketId): void
    {
        $ticket = SupportTicket::findOrFail($ticketId);

        if (! $ticket->isOpen()) {
            return;
        }

        $ticket->update([
            'status' => 'in_progress',
        ]);
    }

    public function openRespondModal(int $ticketId): void
    {
        $this->respondingTicketId = $ticketId;
        $this->response = '';
        $this->showRespondModal = true;
    }

    public function closeRespondModal(): void
    {
        $this->showRespondModal = false;
        $this->reset(['respondingTicketId', 'response']);
    }

    public function submitResponse(): void
    {
        $this->validate([
            'response' => ['required', 'string', 'max:2000'],
        ]);

        $ticket = SupportTicket::findOrFail($this->respondingTicketId);

        if ($ticket->isResolved()) {
            $this->closeRespondModal();

            return;
        }

        $ticket->update([
            'admin_response' => $this->response,
            'responded_at' => now(),
            'status' => 'resolved',
        ]);

        $ticket->user->notify(new SupportTicketReplyNotification($ticket));

        $this->closeRespondModal();
    }

    public function render(): View
    {
        $query = SupportTicket::with('user')
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('subject', 'like', "%{$this->search}%")
                        ->orWhereHas('user', function ($q3) {
                            $q3->where('name', 'like', "%{$this->search}%")
                                ->orWhere('email', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->orderByRaw("CASE status WHEN 'open' THEN 0 WHEN 'in_progress' THEN 1 WHEN 'resolved' THEN 2 ELSE 3 END")
            ->latest();

        return view('livewire.admin.support-tickets', [
            'tickets' => $query->paginate(15),
        ]);
    }
}
