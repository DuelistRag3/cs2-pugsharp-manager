<?php

namespace App\Livewire\Admin\Tournaments;

use App\Models\Tournament;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Index extends Component
{
    /**
     * The component's properties.
     */
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Form properties
    public $name;
    public $description;
    public $registration_deadline;
    public $start_date;
    public $end_date;
    public $team_size = 5; // Default team size for CS2
    public $max_teams = 1;
    public $matchup_rounds = 0; // 0: BO1, 1: BO3, 2: BO5
    public $match_rounds = 24; // Number of rounds per match, default is 24 for CS2
    public $overtime_rounds = 6; // Number of overtime rounds, default is
    public $final_rounds = 0; // 0: BO1, 1: BO3, 2: BO5
    public $status = 'scheduled'; //a scheduled, ongoing, completed, cancelled

    public function create()
    {
        $tournament = new Tournament();
        $tournament->name = $this->name;
        $tournament->description = $this->description;
        $tournament->registration_deadline = $this->registration_deadline;
        $tournament->start_date = $this->start_date;
        $tournament->end_date = $this->end_date;
        $tournament->team_size = $this->team_size; // Default team size
        $tournament->max_teams = $this->max_teams;
        $tournament->matchup_rounds = $this->matchup_rounds;
        $tournament->match_rounds = $this->match_rounds; // Number of rounds per match
        $tournament->overtime_rounds = $this->overtime_rounds; // Number of overtime rounds
        $tournament->final_rounds = $this->final_rounds;
        $tournament->status = $this->status;
        $tournament->save();
        
        LivewireAlert::title('Turnier erstellen')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();

        $this->reset(['name', 'description', 'registration_deadline', 'start_date', 'end_date', 'max_teams', 'matchup_rounds', 'final_rounds', 'status']);
        $this->dispatch('tournamentCreated');
    }

    public function delete($id)
    {
        $tournament = Tournament::find($id);
        if ($tournament) {
            $tournament->delete();
            LivewireAlert::title('Turnier gelöscht')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();

            $this->dispatch('tournamentDeleted');
        } else {
            LivewireAlert::title('Turnier nicht gefunden')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {

        return view('livewire.admin.tournaments.index',
            [
                'tournaments' => Tournament::where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orderBy($this->sortField, $this->sortDirection)
                    ->paginate($this->perPage),
            ]
        );
    }
}
