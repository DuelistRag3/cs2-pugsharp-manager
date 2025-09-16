<?php

namespace App\Livewire\Installer;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Landing extends Component
{
    #[Layout('components.layouts.installer')]
    public function render()
    {
        return view('livewire.installer.landing');
    }
}
