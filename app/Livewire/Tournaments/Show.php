<?php

namespace App\Livewire\Tournaments;

use App\Models\Team;
use App\Models\Player;
use Livewire\Component;
use App\Models\Tournament;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Show extends Component
{

    public string $teamname;
    public string $teamtag;
    public string $player1Id;
    public string $player2Id;
    public string $player3Id;
    public string $player4Id;
    public string $player5Id;

    public Tournament $tournament;

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function registerTeam()
    {

        // Get Players Data

        $response = Http::get("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/", [
            'key' => config('manager.steam_api_key'),
            'steamids' => "$this->player1Id,$this->player2Id,$this->player3Id,$this->player4Id,$this->player5Id",
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            $team = new Team([
                'name' => $this->teamname,
                'tag' => $this->teamtag,
                'flag' => "DE"
            ]);

            $this->tournament->teams()->save($team);

            foreach ($data['response']['players'] as $player) {
                $player = new Player([
                    'steam_id' => $player['steamid'],
                    'steam_name' => $player['personaname'],
                    'steam_avatar' => $player['avatarfull'],
                    'steam_url' => $player['profileurl']
                ]);

                $team->players()->save($player);
            }
            LivewireAlert::success()->toast()->position('top-end')->title('Registrierung erfolgreich')->show();
            return;
        } else {
            $error =  $response->json();
            LivewireAlert::error()->toast()->position('top-end')->title("Fehler")->show();
        }

        $this->dispatch('teamRegistered');

        return;
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.tournaments.show');
    }
}
