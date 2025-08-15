<?php

namespace App\Livewire\Tournaments;

use App\Models\Team;
use App\Models\User;
use Livewire\Component;
use App\Models\Tournament;
use App\Models\TeamTournament;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Renderless;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Livewire;

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
        $this->avlTeams = Team::where('captain_id', auth()->id())->has('tournaments', '!=', $this->tournament->id)->get();
    }

    public function messages()
    {
        return [
            'steam_ids.*.required' => __('tournament_messages.steam_id_required'),
            'steam_ids.*.distinct' => __('tournament_messages.steam_id_distinct'),
            'steam_ids.*.regex' => __('tournament_messages.steam_id_regex'),
        ];
    }

    #[Renderless]
    public function selectTeam($teamId)
    {
        $team = Team::findOrFail($teamId);

        $players = $team->players->map(function ($player) {
            if (!$player->isAvailableForTournament($this->tournament)) {
                return; // Skip players not available for the tournament
            }
            return [
                'id' => $player->id,
                'name' => $player->name,
                'steam_id' => $player->steam_id,
                'avatar' => $player->profilePicture(),
            ];
        });

        $this->dispatch('teamSelected', ['team' => $team, 'players' => $players]);
    }

    public function registerTeam($team, $players)
    {
        $team = Team::findOrFail($team);

        if (count($players) > $this->tournament->team_size) {
            $this->playerSelectionLimitReached($this->tournament->team_size);
            return;
        }

        foreach($players as $player) {
            $user = User::findOrFail($player);

            if(!$user->isAvailableForTournament($this->tournament)) {
                return;
            }
        }

        $team->tournaments()->attach($this->tournament);

        $teamTournament = TeamTournament::where('team_id', $team->id)
            ->where('tournament_id', $this->tournament->id)
            ->first();

        foreach($players as $player) {
            $teamTournament->players()->create([
                'user_id' => $player,
            ]);
        }

        LivewireAlert::title(__('manager.team_registered'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();

        $this->dispatch('teamRegistered');
    }

    #[Renderless]
    public function playerSelectionLimitReached($maxPlayers)
    {
        LivewireAlert::title(__('manager.num_players_exceeded', ['max' => $maxPlayers]))
            ->error()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function cancelRegistration($teamID)
    {
        LivewireAlert::title(__('manager.cancel_registration'))
                ->text(__('manager.cancel_registration_text'))
                ->asConfirm()
                ->withConfirmButton(__('manager.yes'))
                ->confirmButtonColor('red')
                ->withDenyButton(__('manager.no'))
                ->denyButtonColor('gray')
                ->onConfirm('cancelRegistrationConfirmed', ['teamID' => $teamID])
                ->show();
    }

    public function cancelRegistrationConfirmed($data)
    {
        $teamID = $data['teamID'];
        // dd($teamID);
        $team = Team::findOrFail($teamID);
        // dd($team);
        $teamTournament = TeamTournament::where('team_id', $team->id)
            ->where('tournament_id', $this->tournament->id)
            ->first();
        $team->tournaments()->detach($this->tournament);
        $players = $teamTournament->players;
        foreach($players as $player) {
            $player->delete();
        }

        LivewireAlert::title(__('manager.registration_cancelled'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.tournaments.show');
    }
}
