<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public $teams;
    public $teamName;
    public $teamTag;
    public $teamLogo;

    public function mount()
    {
        // Fetch teams with their related tournaments and players
        $this->teams = Team::all();
    }

    public function createTeam()
    {
        $this->validate([
            'teamName' => 'required|string|max:255',
            'teamTag' => 'required|string|max:255',
            'teamLogo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $team = Team::create([
            'name' => $this->teamName,
            'tag' => $this->teamTag,
            'captain_id' => auth()->id(),
            'logo_extension' => $this->teamLogo ? $this->teamLogo->extension() : null,
        ]);

        $this->teamLogo->storePubliclyAs(path: 'team_logos', name: $team->id . '.' . $this->teamLogo->extension());

        return redirect()->route('teams.show', $team->id);
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.teams.index', [
            'teams' => $this->teams,
        ]);
    }
}
