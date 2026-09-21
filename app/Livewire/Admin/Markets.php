<?php

namespace App\Livewire\Admin;

use App\Models\Market;
use App\Models\Trade;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Markets')]
#[Layout('layouts.admin')]
class Markets extends Component
{
    public bool $showModal = false;

    public ?int $editingId = null;

    public string $symbol = '';

    public string $displayName = '';

    public string $coingeckoId = '';

    public string $tradingviewSymbol = '';

    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

    public string $deletingSymbol = '';

    public int $deletingOpenTradesCount = 0;

    public string $deleteConfirmation = '';

    public function openCreateModal(): void
    {
        $this->reset(['editingId', 'symbol', 'displayName', 'coingeckoId', 'tradingviewSymbol']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEditModal(int $marketId): void
    {
        $market = Market::findOrFail($marketId);

        $this->editingId = $market->id;
        $this->symbol = $market->symbol;
        $this->displayName = $market->display_name;
        $this->coingeckoId = $market->coingecko_id;
        $this->tradingviewSymbol = $market->tradingview_symbol;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId', 'symbol', 'displayName', 'coingeckoId', 'tradingviewSymbol']);
    }

    public function save(): void
    {
        $this->validate([
            'symbol' => ['required', 'string', 'max:20', Rule::unique('markets', 'symbol')->ignore($this->editingId)],
            'displayName' => ['required', 'string', 'max:20'],
            'coingeckoId' => ['required', 'string', 'max:50'],
            'tradingviewSymbol' => ['required', 'string', 'max:50'],
        ]);

        $data = [
            'symbol' => strtoupper($this->symbol),
            'display_name' => $this->displayName,
            'coingecko_id' => strtolower($this->coingeckoId),
            'tradingview_symbol' => strtoupper($this->tradingviewSymbol),
        ];

        if ($this->editingId) {
            Market::findOrFail($this->editingId)->update($data);
        } else {
            Market::create([...$data, 'sort_order' => Market::max('sort_order') + 1]);
        }

        $this->closeModal();
    }

    public function toggleActive(int $marketId): void
    {
        $market = Market::findOrFail($marketId);
        $market->update(['is_active' => ! $market->is_active]);
    }

    public function confirmDelete(int $marketId): void
    {
        $market = Market::findOrFail($marketId);

        $this->deletingId = $market->id;
        $this->deletingSymbol = $market->symbol;
        $this->deletingOpenTradesCount = Trade::where('asset', $market->symbol)->where('status', 'open')->count();
        $this->deleteConfirmation = '';
        $this->resetValidation();
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->reset(['deletingId', 'deletingSymbol', 'deletingOpenTradesCount', 'deleteConfirmation']);
    }

    public function deleteMarket(): void
    {
        $this->validate([
            'deleteConfirmation' => ['required', 'in:DELETE'],
        ], [
            'deleteConfirmation.in' => 'Type DELETE (all caps) to confirm.',
        ]);

        Market::findOrFail($this->deletingId)->delete();

        $this->closeDeleteModal();
    }

    public function render(): View
    {
        return view('livewire.admin.markets', [
            'markets' => Market::orderBy('sort_order')->get(),
        ]);
    }
}
