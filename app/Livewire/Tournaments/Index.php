<?php

namespace App\Livewire\Tournaments;

use App\Models\Tournament;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $tournaments;
    public $upcomingTournaments;
    public $runningTournaments;

    public function mount()
    {
        $this->tournaments = Tournament::all();
        $this->upcomingTournaments = Tournament::where('status', 'scheduled')->get();
        $this->runningTournaments = Tournament::where('status', 'ongoing')->get();
    }
    #[Layout('components.layouts.guest')]
    public function render()
    {
        $data = [
            'tournaments' => $this->tournaments,
        ];
        return view('livewire.tournaments.index', $data);
    }
}
