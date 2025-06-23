<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\TournamentRoundService;
use App\Models\Tournament;
use Livewire\Attributes\Layout;

class TournamentRounds extends Component
{
    public Tournament $tournament;

    public array $rounds = [];
    public array $bracket = [];

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
        $teamCount = $tournament->teams()->count();
        $this->rounds = TournamentRoundService::generateRounds($teamCount);
        $this->bracket = TournamentRoundService::generateBracketForTournament($tournament);
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.tournament-rounds');
    }
}
