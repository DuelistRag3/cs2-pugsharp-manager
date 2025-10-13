<?php

namespace App\Livewire\Tournaments;

use App\Models\Tournament;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $upcomingTournaments;
    public $runningTournaments;
    public $finishedTournaments;

    public $search = '';

    public function mount()
    {
        $this->upcomingTournaments = Tournament::where('status', 'scheduled')->get();
        $this->runningTournaments = Tournament::where('status', 'ongoing')->get();
        $this->finishedTournaments = Tournament::where('status', 'completed')->whereAny([
            'name',
            'description',
            'start_date',
            'end_date',
            'max_teams',
            'guest_mode'
        ], 'like', "%{$this->search}%")->get();
    }

    public function updating($property, $value)
    {
        if ($property === 'search') {
            $this->finishedTournaments = Tournament::where('status', 'completed')->whereAny([
                'name',
                'description',
                'start_date',
                'end_date',
                'max_teams',
                'guest_mode'
            ], 'like', "%{$value}%")->get();
        }
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.tournaments.index');
    }
}
