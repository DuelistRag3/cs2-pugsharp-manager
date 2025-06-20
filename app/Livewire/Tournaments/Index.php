<?php

namespace App\Livewire\Tournaments;

use App\Models\Tournament;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    #[Layout('components.layouts.guest')]
    public function render()
    {
        $data = [
            'tournaments' => Tournament::all()
        ];
        return view('livewire.tournaments.index', $data);
    }
}
