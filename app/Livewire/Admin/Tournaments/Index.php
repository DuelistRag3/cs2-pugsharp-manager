<?php

namespace App\Livewire\Admin\Tournaments;

use Livewire\Component;
use App\Models\Tournament;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
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
    #[Validate('required|string|max:255|unique:tournaments,name')]
    public $name;
    #[Validate('required|string|max:255')]
    public $description;
    #[Validate('required|date|before:start_date')]
    public $registration_deadline;
    #[Validate('required|date|after:now')]
    public $start_date;
    #[Validate('integer|min:1|max:10')]
    public $team_size = 5; // Default team size for CS2
    #[Validate('integer|min:2')]
    public $max_teams = 2;
    #[Validate('integer|min:0|max:2')]
    public $maps_each_game = 0; // 0: BO1, 1: BO3, 2: BO5
    #[Validate('integer|min:0|max:2')]
    public $maps_final_game = 0; // 0: BO1, 1: BO3, 2: BO5
    #[Validate('integer|min:2')]
    public $map_rounds = 24; // Number of rounds per match, default is 24 for CS2
    #[Validate('integer|min:0')]
    public $map_overtime_rounds = 6; // Number of overtime rounds, default is

    public function create()
    {
        $this->validate();

        $tournament = new Tournament();
        $tournament->name = $this->name;
        $tournament->description = $this->description;
        $tournament->registration_deadline = $this->registration_deadline;
        $tournament->start_date = $this->start_date;
        $tournament->team_size = $this->team_size;
        $tournament->max_teams = $this->max_teams;
        $tournament->maps_each_game = $this->maps_each_game;
        $tournament->maps_final_game = $this->maps_final_game;
        $tournament->map_rounds = $this->map_rounds;
        $tournament->map_overtime_rounds = $this->map_overtime_rounds;
        $tournament->save();
        
        LivewireAlert::title('Turnier erstellen')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();

        return redirect(Show::class, ['id' => $tournament->id]);
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
