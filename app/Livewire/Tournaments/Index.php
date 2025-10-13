<?php

namespace App\Livewire\Tournaments;

use App\Models\Tournament;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $activeTab = 'scheduled';
    public $tournaments;

    public function mount()
    {
        $this->tournaments = Tournament::where('status', $this->activeTab)->get();
    }

    public function changeTab($newTab)
    {
        $this->activeTab = $newTab;
        $this->tournaments = Tournament::where('status', $this->activeTab)->get();
    }

    public function updating($prop, $val)
    {
        if($prop === 'activeTab')
        {
            $this->changeTab($val);
        }
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.tournaments.index');
    }
}
