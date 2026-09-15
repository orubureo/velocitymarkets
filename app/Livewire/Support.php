<?php

namespace App\Livewire;

use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Support')]
class Support extends Component
{
    public string $subject = '';

    public string $category = 'account';

    public string $message = '';

    public function submitTicket(): void
    {
        $this->validate([
            'subject' => ['required', 'string', 'max:150'],
            'category' => ['required', 'in:account,deposits,withdrawals,trading,other'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $this->subject,
            'category' => $this->category,
            'message' => $this->message,
            'status' => 'open',
        ]);

        $this->reset(['subject', 'category', 'message']);

        session()->flash('status', 'Your support ticket has been submitted.');
    }

    public function render()
    {
        return view('livewire.support', [
            'tickets' => SupportTicket::where('user_id', Auth::id())->latest()->get(),
        ]);
    }
}
