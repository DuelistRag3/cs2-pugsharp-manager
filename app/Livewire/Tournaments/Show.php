<?php

namespace App\Livewire\Tournaments;

use App\Models\Team;
use App\Models\Player;
use Livewire\Component;
use App\Models\Tournament;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Show extends Component
{

    #[Validate(['required', 'string', 'max:255'])]
    public string $teamname;
    #[Validate(['required', 'string', 'max:10'])]
    public string $teamtag;
    #[Validate([
        'steam_ids' => 'required|array|min:1',
        'steam_ids.*' => [
            'required',
            'string',
            'distinct',
            'regex:/^[0-9]{17}$/'
        ],
    ])]
    public $steam_ids = [];

    public Tournament $tournament;
    public $avlTeams;

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
        $this->avlTeams = Team::where('captain_id', auth()->id())->has('players', $this->tournament->team_size)->has('tournaments', '!=', $this->tournament->id)->get();
    }

    public function messages()
    {
        return [
            'steam_ids.*.required' => __('tournament_messages.steam_id_required'),
            'steam_ids.*.distinct' => __('tournament_messages.steam_id_distinct'),
            'steam_ids.*.regex' => __('tournament_messages.steam_id_regex'),
        ];
    }

    public function registerTeam($id)
    {

        $this->tournament->teams()->attach($id);

        $this->dispatch('teamRegistered');

        return;
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.tournaments.show');
    }
}
