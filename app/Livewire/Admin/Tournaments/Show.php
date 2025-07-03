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

    public function createFirstRound()
    {
        $this->tournament->createFirstRound();
        LivewireAlert::title('Erste Runde erstellt')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function startMatch($matchId)
    {
        $match = $this->tournament->games()->findOrFail($matchId);
        // $match->start();

        $freeServer = Server::where('game_id', '>', 0)
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
            $query->SetRconPassword('7j)(3ihClc6f');

            $uri = config('app.url') . '/configs/get/' . $match->id;
            Debugbar::info('Server config URI: ' . $uri);

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
