<?php

namespace App\Livewire\Tournaments;

use Livewire\Component;
use App\Models\Tournament;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Show extends Component
{

    public string $teamname;
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
            // $avatar = $data['response']['players'][0]['avatarfull'] ?? null;
            LivewireAlert::error()->toast()->title('Registrierung erfolgreich')->show();
            return;
        } else {
            LivewireAlert::success()->toast()->title('Registrierung erfolgreich')->show();
        }

        return;

        LivewireAlert::success()->title('Registrierung erfolgreich')->show();

        $this->dispatch('teamRegistered');
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.tournaments.show');
    }
}
