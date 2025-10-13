<?php

namespace App\Livewire\Matches;

use App\Models\Game;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $activeTab = 'scheduled';
    public $matches;

    public function mount()
    {
        $this->matches = Game::where('status', $this->activeTab)->get();
    }

    public function changeTab($newTab)
    {
        $this->activeTab = $newTab;
        $this->matches = Game::where('status', $this->activeTab)->get();
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
        return view('livewire.matches.index');
    }
}
