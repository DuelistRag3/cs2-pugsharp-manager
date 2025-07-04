<?php

namespace App\Livewire\Admin\Tournaments;

use App\Models\Server;
use Livewire\Component;
use App\Models\Tournament;
use Livewire\Attributes\Layout;

use xPaw\SourceQuery\SourceQuery;
use Barryvdh\Debugbar\Facades\Debugbar;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Show extends Component
{

    public Tournament $tournament;

    public function mount($id)
    {
        $this->tournament = Tournament::findOrFail($id);
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

        if ($this->tournament->teams->count() % 2 !== 0) {
            LivewireAlert::title('Die Anzahl der Teams muss gerade sein')
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

    public function cancelTournament()
    {
        LivewireAlert::title('Turnier abbrechen?')
            ->text('Bist du sicher, dass du das Turnier abbrechen möchtest? Dadurch werden alle Spiele sofort abgebrochen und das Turnier wird als abgebrochen markiert.')
            ->asConfirm()
            ->withConfirmButton('Ja')
            ->confirmButtonColor('red')
            ->withDenyButton('Nein')
            ->denyButtonColor('gray')
            ->onConfirm('cancelConfirmed')
            ->show();
    }

    public function cancelConfirmed()
    {
        $this->tournament->cancel();
        LivewireAlert::title('Turnier abgebrochen')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function generateMatchPlan()
    {
        $this->tournament->generateMatchPlan();
        LivewireAlert::title('Erste Runde erstellt')
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
        // $match->start();

        $freeServer = Server::where('game_id', null)
            ->first();

        if (!isset($freeServer)) {
            LivewireAlert::title('Kein freier Server gefunden')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        $query = new SourceQuery();
        try {
            $query->Connect($freeServer->ip_address, $freeServer->port, 1, SourceQuery::SOURCE);
            // $info = $query->GetInfo();
            $query->SetRconPassword($freeServer->rcon_password);

            $uri = route('api.matches.config', ['id' => $match->id]);

            $command = 'ps_loadconfig "'.$uri.'"';

            $query->Rcon($command);
            $query->Disconnect();

            Debugbar::info('Server config successfully sendet');

            $freeServer->game_id = $match->id;
            $freeServer->save();

            LivewireAlert::title('Match gestartet')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();

            return;
        } catch (\Exception $e) {
            Debugbar::error('Server status error: ' . $e->getMessage());
            return;
        }
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.tournaments.show');
    }
}
