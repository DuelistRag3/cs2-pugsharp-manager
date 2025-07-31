<?php

namespace App\Livewire\Matches;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Show extends Component
{
    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.matches.show');
    }
}
