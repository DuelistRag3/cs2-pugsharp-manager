<?php

namespace App\Livewire\Admin\Tournaments;

use App\Http\Controllers\RconController;
use App\Models\AvailableMaps;
use App\Models\Server;
use Livewire\Component;
use App\Models\Tournament;
use Livewire\Attributes\Layout;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Show extends Component
{

    public Tournament $tournament;
    public $availableMaps;
    public $selectedMaps = [];
    public $maps_override = 0;

    public function mount($id)
    {
        $this->tournament = Tournament::findOrFail($id);
        $this->availableMaps = AvailableMaps::all();
        $this->selectedMaps = $this->tournament->maps ?? [];
    }

    public function startTournament($full = false)
    {
        if ($this->tournament->status !== 'scheduled') {
            LivewireAlert::title(__('manager.tournament_messages.already_started'))
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if ($this->tournament->teams->count() < 2) {
            LivewireAlert::title(__('manager.tournament_messages.not_enough_teams'))
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if (!$this->tournament->maps)
        {
            LivewireAlert::title(__('manager.tournament_messages.no_maps_selected'))
                ->text(__('manager.tournament_messages.no_maps_selected_text'))
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if (!$full) {
            if ($this->tournament->teams->count() == $this->tournament->max_teams) {
                $full = true;
            }
        }

        if (!$full) {
            LivewireAlert::title(__('manager.tournament_messages.not_full'))
                ->text(__('manager.tournament_messages.not_full_text'))
                ->asConfirm()
                ->withConfirmButton(__('manager.yes'))
                ->confirmButtonColor('green')
                ->withDenyButton(__('manager.no'))
                ->denyButtonColor('gray')
                ->onConfirm('startTournament', ['full' => true])
                ->show();
            return;
        }

        $this->tournament->start();
        LivewireAlert::title(__('manager.tournament_messages.tournament_started'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function cancelTournament()
    {
        LivewireAlert::title(__('manager.tournament_messages.cancel_tournament'))
            ->text(__('manager.tournament_messages.cancel_tournament_text'))
            ->asConfirm()
            ->withConfirmButton(__('manager.yes'))
            ->confirmButtonColor('red')
            ->withDenyButton(__('manager.no'))
            ->denyButtonColor('gray')
            ->onConfirm('cancelTournamentConfirmed')
            ->show();
    }

    public function cancelTournamentConfirmed()
    {
        $this->tournament->cancel();
        LivewireAlert::title(__('manager.tournament_messages.tournament_cancelled'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function generateMatchPlan($type)
    {
        $this->tournament->generateMatchPlan($type);
        LivewireAlert::title(__('manager.tournament_messages.matchplan_generated'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function addTeamsToMatchPlan($alreadyassigned = false)
    {

        if ($this->tournament->games()->count() == 0) {
            LivewireAlert::title(__('manager.tournament_messages.matchplan_not_generated'))
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if($this->tournament->games->first()->team1_id != null && !$alreadyassigned) {
            LivewireAlert::title(__('manager.tournament_messages.teams_already_assigned'))
                ->text(__('manager.tournament_messages.teams_already_assigned_text'))
                ->asConfirm()
                ->withConfirmButton(__('manager.yes'))
                ->confirmButtonColor('green')
                ->withDenyButton(__('manager.no'))
                ->denyButtonColor('gray')
                ->onConfirm('addTeamsToMatchPlan', ['alreadyassigned' => true])
                ->show();
            return;
        }

        $this->tournament->addTeamsToMatchPlan();
        LivewireAlert::title(__('manager.tournament_messages.teams_assigned'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function removeAllTeamsFromMatchPlan($confirmed = false)
    {
        if (!$confirmed) {
            LivewireAlert::title(__('manager.tournament_messages.remove_all_teams'))
                ->text(__('manager.tournament_messages.remove_all_teams_text'))
                ->asConfirm()
                ->withConfirmButton(__('manager.yes'))
                ->confirmButtonColor('red')
                ->withDenyButton(__('manager.no'))
                ->denyButtonColor('gray')
                ->onConfirm('removeAllTeamsFromMatchPlan', ['confirmed' => true])
                ->show();
            return;
        }
        $this->tournament->removeAllTeamsFromMatchPlan();
        LivewireAlert::title(__('manager.tournament_messages.teams_removed'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function resetMatchPlan($confirmed = false)
    {
        if ($confirmed == false) {
            LivewireAlert::title(__('manager.tournament_messages.reset_matchplan'))
                ->text(__('manager.tournament_messages.reset_matchplan_text'))
                ->asConfirm()
                ->withConfirmButton(__('manager.yes'))
                ->confirmButtonColor('red')
                ->withDenyButton(__('manager.no'))
                ->denyButtonColor('gray')
                ->onConfirm('resetMatchPlan', ['confirmed' => true])
                ->show();
            return;
        }
        
        $this->tournament->games()->delete();
        LivewireAlert::title(__('manager.tournament_messages.matchplan_reset'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function startMatch($matchId)
    {

        $token = config('manager.api_bearer_token');

        if ($token == null || $token == '') {
            LivewireAlert::title(__('manager.tournament_messages.no_api_key'))
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if ($this->tournament->maps == null || count($this->tournament->maps) == 0) {
            LivewireAlert::title(__('manager.tournament_messages.no_maps_selected'))
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }


        $match = $this->tournament->games()->findOrFail($matchId);

        $freeServer = Server::whereDoesntHave('game')->first();

        if (!isset($freeServer)) {
            LivewireAlert::title(__('manager.tournament_messages.no_free_server'))
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        $uri = route('api.matches.config', ['id' => $match->id]);

        new RconController()->sendCommand($freeServer->id, 'ps_loadconfig "'.$uri.'"');

        LivewireAlert::title(__('manager.tournament_messages.match_started_on', ['server' => $freeServer->name]))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function pauseMatch($matchId)
    {
        $match = $this->tournament->games()->findOrFail($matchId);
        $map = $match->maps->where('status', 'ongoing')->first();

        if (!$map) {
            LivewireAlert::title(__('manager.tournament_messages.match_not_running'))
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        $map->status = 'paused';
        $map->save();

        LivewireAlert::title(__('manager.tournament_messages.match_paused'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function changeMapState($mapId)
    {
        $map = AvailableMaps::findOrFail($mapId);

        if (!$map) {
            LivewireAlert::title(__('manager.tournament_messages.map_not_found'))
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if (array_search($map->map_code, $this->selectedMaps) !== false) {
            $this->selectedMaps = array_diff($this->selectedMaps, [$map->map_code]);
            LivewireAlert::title(__('manager.tournament_messages.removed_map'))
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } else {
            $this->selectedMaps[] = $map->map_code;
            LivewireAlert::title(__('manager.tournament_messages.added_map'))
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        }

        $this->tournament->maps = $this->selectedMaps;
        $this->tournament->save(); 
          
    }

    public function updateMapsOverride($gameId)
    {
        // dd($this->maps_override);
        $game = $this->tournament->games()->findOrFail($gameId);
        $game->maps_override = $this->maps_override;
        $game->save();

        LivewireAlert::title(__('manager.num_maps_overridden'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.tournaments.show');
    }
}
