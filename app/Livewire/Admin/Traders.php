<?php

namespace App\Livewire\Admin;

use App\Models\Trader;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Traders')]
#[Layout('layouts.admin')]
class Traders extends Component
{
    public string $name = '';

    public string $tagline = '';

    public string $bio = '';

    public string $tier = 'verified';

    public string $riskLevel = 'medium';

    public string $winRate = '';

    public string $roi30d = '';

    public string $baseCopiers = '0';

    public string $minCopyAmount = '50';

    public string $maxCopyAmount = '';

    public function addTrader(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:255'],
            'tier' => ['required', 'in:verified,pro,elite'],
            'riskLevel' => ['required', 'in:low,medium,high'],
            'winRate' => ['required', 'numeric', 'min:0', 'max:100'],
            'roi30d' => ['required', 'numeric'],
            'baseCopiers' => ['required', 'integer', 'min:0'],
            'minCopyAmount' => ['required', 'numeric', 'min:0'],
            'maxCopyAmount' => ['nullable', 'numeric', 'gt:minCopyAmount'],
        ]);

        Trader::create([
            'name' => $this->name,
            'avatar_initials' => strtoupper(collect(explode(' ', $this->name))->map(fn ($w) => substr($w, 0, 1))->take(2)->implode('')),
            'tagline' => $this->tagline ?: null,
            'bio' => $this->bio ?: null,
            'tier' => $this->tier,
            'risk_level' => $this->riskLevel,
            'win_rate' => $this->winRate,
            'roi_30d' => $this->roi30d,
            'base_copiers' => $this->baseCopiers,
            'min_copy_amount' => $this->minCopyAmount,
            'max_copy_amount' => $this->maxCopyAmount ?: null,
            'sort_order' => (Trader::max('sort_order') ?? 0) + 1,
        ]);

        $this->reset(['name', 'tagline', 'bio', 'tier', 'riskLevel', 'winRate', 'roi30d', 'baseCopiers', 'minCopyAmount', 'maxCopyAmount']);
        $this->tier = 'verified';
        $this->riskLevel = 'medium';
        $this->baseCopiers = '0';
        $this->minCopyAmount = '50';
    }

    public function toggleActive(int $traderId): void
    {
        $trader = Trader::findOrFail($traderId);
        $trader->update(['is_active' => ! $trader->is_active]);
    }

    public function render(): View
    {
        return view('livewire.admin.traders', [
            'traders' => Trader::orderBy('sort_order')->get(),
        ]);
    }
}
