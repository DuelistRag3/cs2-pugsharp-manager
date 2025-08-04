<?php

namespace App\Livewire\Teams;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.teams.index');
    }
}
