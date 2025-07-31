<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TournamentGameCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $game,
    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.tournament-game-card');
    }
}
