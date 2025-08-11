<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;

class Show extends Component
{
    public Team $team;
    public $search = '';
    public $invitablePlayers = [];
    public $invitePlayers = [];

    public function mount(Team $team)
    {
        $this->team = $team;
        $this->invitablePlayers = User::where('name', 'like', "%{$this->search}%")->orWhere('steam_name', 'like', "%{$this->search}%")
            ->orWhere('steam_id', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%")
            ->limit(5)->get();
    }

    #[On('updatedSearch')]
    public function updatedSearch()
    {
        $this->invitablePlayers = User::where('name', 'like', "%{$this->search}%")
            ->orWhere('steam_name', 'like', "%{$this->search}%")
            ->orWhere('steam_id', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%")
            ->limit(5)->get();
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.teams.show', ['team' => $this->team]);
    }
}
