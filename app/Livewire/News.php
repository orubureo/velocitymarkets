<?php

namespace App\Livewire;

use App\Services\NewsService;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('News')]
class News extends Component
{
    public function render(NewsService $news)
    {
        return view('livewire.news', [
            'headlines' => $news->latestHeadlines(),
        ]);
    }
}
