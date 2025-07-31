<?php

namespace App\Livewire\Matches;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $upcomingMatches;
    public $runningMatches;
    public $finishedMatches;

    public function mount()
    {
        $this->upcomingMatches = \App\Models\Game::where('status', 'scheduled')->get();
        $this->runningMatches = \App\Models\Game::where('status', 'ongoing')->get();
        $this->finishedMatches = \App\Models\Game::where('status', 'completed')->get();
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.matches.index');
    }
}
