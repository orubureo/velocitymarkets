<?php

namespace App\Livewire\Signal;

use App\Models\UserSignal;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('My Signals')]
class MySignals extends Component
{
    public function render(): View
    {
        return view('livewire.signal.my-signals', [
            'mySignals' => UserSignal::where('user_id', Auth::id())->with('tier')->latest()->get(),
        ]);
    }
}
