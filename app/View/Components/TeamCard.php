<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TeamCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $team = null, // Default to null if no team is passed
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.team-card');
    }
}
