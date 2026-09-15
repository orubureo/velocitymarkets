<?php

namespace App\Livewire;

use App\Services\NewsService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('News')]
class News extends Component
{
    public function render(NewsService $news): View
    {
        return view('livewire.news', [
            'headlines' => $news->latestHeadlines(),
        ]);
    }
}
