<?php

namespace App\Livewire\Admin\Tournaments;

use App\Http\Controllers\RconController;
use App\Models\AvailableMaps;
use App\Models\Server;
use Livewire\Component;
use App\Models\Tournament;
use Livewire\Attributes\Layout;

use Barryvdh\Debugbar\Facades\Debugbar;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Show extends Component
{

    public Tournament $tournament;
    public $availableMaps;
    public $selectedMaps = [];

    public function mount($id)
    {
        $this->tournament = Tournament::findOrFail($id);
        $this->availableMaps = AvailableMaps::all();
        $this->selectedMaps = $this->tournament->maps ?? [];
    }

    public function startTournament($full = false)
    {
        if ($this->tournament->status !== 'scheduled') {
            LivewireAlert::title('Turnier kann nicht gestartet werden')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if ($this->tournament->teams->count() < 2) {
            LivewireAlert::title('Nicht genug Teams für das Turnier')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if (!$this->tournament->maps)
        {
            LivewireAlert::title('Keine Karten ausgewählt')
                ->text('Bitte wähle mindestens eine Karte für das Turnier aus.')
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

        // Warn if torunament is not full
        if (!$full) {
            LivewireAlert::title('Turnier ist nicht voll')
                ->text('Das Turnier hat nicht die maximale Anzahl an Teams. Möchtest du trotzdem starten?')
                ->asConfirm()
                ->withConfirmButton('Ja')
                ->confirmButtonColor('green')
                ->withDenyButton('Nein')
                ->denyButtonColor('gray')
                ->onConfirm('startTournament', ['full' => true])
                ->show();
            return;
        }

        $this->tournament->start();
        LivewireAlert::title('Turnier gestartet')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function cancelTournament($confirmed = false)
    {
        if (!$confirmed) {
            LivewireAlert::title('Turnier abbrechen?')
                ->text('Bist du sicher, dass du das Turnier abbrechen möchtest? Dadurch werden alle Spiele sofort abgebrochen und das Turnier wird als abgebrochen markiert.')
                ->asConfirm()
                ->withConfirmButton('Ja')
                ->confirmButtonColor('red')
                ->withDenyButton('Nein')
                ->denyButtonColor('gray')
                ->onConfirm('cancelTournamentConfirmed', ['confirmed' => true])
                ->show();
            return;
        }

        $this->tournament->cancel();
        LivewireAlert::title('Turnier abgebrochen')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function generateMatchPlan($type)
    {
        $this->tournament->generateMatchPlan($type);
        LivewireAlert::title('Erste Runde erstellt')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function addTeamsToMatchPlan($alreadyassigned = false)
    {

        if ($this->tournament->games()->count() == 0) {
            LivewireAlert::title('Es gibt noch keine Spiele im Matchplan')
                ->text('Bitte erstelle zuerst den Matchplan, bevor du Teams zuweist.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if($this->tournament->games->first()->team1_id != null && !$alreadyassigned) {
            LivewireAlert::title('Teams bereits zugewiesen')
                ->text('Die Teams sind bereits zugewiesen. Möchtest du die Teams erneut zuweisen?')
                ->asConfirm()
                ->withConfirmButton('Ja')
                ->confirmButtonColor('green')
                ->withDenyButton('Nein')
                ->denyButtonColor('gray')
                ->onConfirm('addTeamsToMatchPlan', ['alreadyassigned' => true])
                ->show();
            return;
        }

        $this->tournament->addTeamsToMatchPlan();
        LivewireAlert::title('Teams zugewiesen')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function removeAllTeamsFromMatchPlan($confirmed = false)
    {
        if (!$confirmed) {
            LivewireAlert::title('Alle Teams aus dem Matchplan entfernen?')
                ->text('Bist du sicher, dass du alle Teams aus dem Matchplan entfernen möchtest?')
                ->asConfirm()
                ->withConfirmButton('Ja')
                ->confirmButtonColor('red')
                ->withDenyButton('Nein')
                ->denyButtonColor('gray')
                ->onConfirm('removeAllTeamsFromMatchPlan', ['confirmed' => true])
                ->show();
            return;
        }
        $this->tournament->removeAllTeamsFromMatchPlan();
        LivewireAlert::title('Alle Teams aus dem Matchplan entfernt')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function resetMatchPlan($confirmed = false)
    {
        if (!$confirmed) {
            LivewireAlert::title('Matchplan zurücksetzen?')
                ->text('Bist du sicher, dass du den Matchplan zurücksetzen möchtest? Dadurch werden alle Spiele und Teamzuweisungen gelöscht.')
                ->asConfirm()
                ->withConfirmButton('Ja')
                ->confirmButtonColor('red')
                ->withDenyButton('Nein')
                ->denyButtonColor('gray')
                ->onConfirm('resetMatchPlan', ['confirmed' => true])
                ->show();
            return;
        }
        $this->tournament->games()->delete();
        LivewireAlert::title('Matchplan zurückgesetzt')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function startMatch($matchId)
    {

        $token = config('manager.api_bearer_token');

        if ($token == null || $token == '') {
            LivewireAlert::title('Es ist kein API Token gesetzt')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }


        $match = $this->tournament->games()->findOrFail($matchId);

        $freeServer = Server::whereDoesntHave('game')->first();

        // dd($freeServer);

        if (!isset($freeServer)) {
            LivewireAlert::title('Kein freier Server gefunden')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        $uri = route('api.matches.config', ['id' => $match->id]);

        new RconController()->sendCommand($freeServer->id, 'ps_loadconfig "'.$uri.'"');
    }

    public function pauseMatch($matchId)
    {
        $match = $this->tournament->games()->findOrFail($matchId);
        $map = $match->maps->where('status', 'ongoing')->first();

        if (!$map) {
            LivewireAlert::title('Kein laufendes Spiel gefunden')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }



        $map->status = 'paused';
        $map->save();

        LivewireAlert::title('Match pausiert')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function changeMapState($mapId)
    {
        $map = AvailableMaps::findOrFail($mapId);

        if (!$map) {
            LivewireAlert::title('Karte nicht gefunden')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if (array_search($map->map_code, $this->selectedMaps) !== false) {
            // Karte entfernen
            $this->selectedMaps = array_diff($this->selectedMaps, [$map->map_code]);
        } else {
            // Karte hinzufügen
            $this->selectedMaps[] = $map->map_code;
        }

        $this->tournament->maps = $this->selectedMaps;
        $this->tournament->save(); 
        LivewireAlert::title('Karte aktualisiert')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();   
        // $map = AvailableMaps::findOrFail($mapId);

        // if (!$map)
        // {
        //     LivewireAlert::title('Karte nicht gefunden')
        //         ->error()
        //         ->toast()
        //         ->position('top-end')
        //         ->show();
        //     return;
        // }

        // $maps = $this->tournament->maps;

        // // dd($maps);

        // if (!$maps)
        // {
        //     $maps = [];
        // }

        // if (in_array($map->map_code, $maps)) {
        //     $maps = array_diff($maps, [$map->map_code]);
        // } else {
        //     $maps[] = $map->map_code;
        // }

        // $this->tournament->maps = $maps;
        // $this->tournament->save();
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.tournaments.show');
    }
}
