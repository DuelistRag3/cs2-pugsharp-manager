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

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function messages()
    {
        return [
            'steam_ids.*.required' => __('tournament_messages.steam_id_required'),
            'steam_ids.*.distinct' => __('tournament_messages.steam_id_distinct'),
            'steam_ids.*.regex' => __('tournament_messages.steam_id_regex'),
        ];
    }

    public function registerTeam()
    {
        // $this->validate();
        dd($this->steam_ids);

        $response = Http::get("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/", [
            'key' => config('manager.steam_api_key'),
            'steamids' => $this->steam_ids,
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
