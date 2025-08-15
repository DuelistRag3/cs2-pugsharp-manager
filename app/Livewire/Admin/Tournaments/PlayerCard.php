<?php

namespace App\Livewire\Admin\Tournaments;

use App\Models\User;
use Livewire\Component;

class PlayerCard extends Component
{
    public $steamId;
    public $name;
    public $avatar;
    public $url;

    public function mount($id = null)
    {
        $user = User::findOrFail($id);

        $this->steamId = $user->steam_id;
        $this->name = $user->steam_name;
        $this->avatar = $user->steam_avatar;
        $this->url = $user->steam_url;
    }

    public function render()
    {
        return view('livewire.admin.tournaments.player-card');
    }
}
