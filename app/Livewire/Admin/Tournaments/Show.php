<?php

namespace App\Livewire\Admin\Tournaments;

use Livewire\Component;
use App\Models\Tournament;
use Livewire\Attributes\Layout;

class Show extends Component
{

    public Tournament $tournament;

    public function mount($id)
    {
        $this->tournament = Tournament::findOrFail($id);
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.tournaments.show');
    }
}
