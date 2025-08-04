<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Show extends Component
{
    public Team $team;

    public function mount(Team $team)
    {
        $this->team = $team;
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.teams.show', ['team' => $this->team]);
    }
}
