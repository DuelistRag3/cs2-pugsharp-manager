<?php

namespace App\Livewire\Admin\Tournaments;

use App\Models\Player;
use Livewire\Component;

class PlayerCard extends Component
{
    public $steamId;
    public $name;
    public $avatar;
    public $url;

    public function mount($id = null)
    {
        $player = Player::findOrFail($id);

        $this->steamId = $player->steam_id;
        $this->name = $player->steam_name;
        $this->avatar = $player->steam_avatar;
        $this->url = $player->steam_url;
    }

    public function render()
    {
        return view('livewire.admin.tournaments.player-card');
    }
}
