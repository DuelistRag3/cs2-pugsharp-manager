<?php

namespace App\Livewire\Matches;

use App\Models\Game;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Show extends Component
{
    public Game $game;
    public $numMaps;

    public function mount(Game $game)
    {
        $this->game = $game;
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.matches.show');
    }
}
