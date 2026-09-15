<?php

namespace App\Livewire\Marketing;

use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Contact — VelocityMarkets')]
#[Layout('components.layouts.marketing', [
    'title' => 'Contact — VelocityMarkets',
    'description' => 'Get in touch with the VelocityMarkets team.',
])]
class Contact extends Component
{
    public string $name = '';

    public string $email = '';

    public string $subject = '';

    public string $message = '';

    public bool $sent = false;

    public function submit()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        Log::info('Contact form submission', [
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        $this->sent = true;
        $this->reset(['name', 'email', 'subject', 'message']);
    }

    public function render()
    {
        return view('livewire.marketing.contact');
    }
}
