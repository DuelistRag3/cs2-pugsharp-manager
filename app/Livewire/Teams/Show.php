<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Builder;

class Show extends Component
{
    public Team $team;
    public $search = '';
    public $invitablePlayers = [];
    public $invitePlayers = [];

    public function mount(Team $team)
    {
        $this->team = $team;
        $this->invitablePlayers = User::whereAny([
                'name',
                'steam_name',
                'steam_id',
                'email'
            ], 'like', "%{$this->search}%")
            ->whereNotIn('id', $this->team->players->pluck('id'))
            ->limit(5)->get();
    }

    #[On('updatedSearch')]
    public function updatedSearch()
    {
        $this->invitablePlayers = User::whereAny([
                'name',
                'steam_name',
                'steam_id',
                'email'
            ], 'like', "%{$this->search}%")
            ->whereNotIn('id', $this->team->players->pluck('id'))
            ->limit(5)->get();
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.teams.show', ['team' => $this->team]);
    }
}
