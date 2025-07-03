<?php

namespace App\Livewire\Admin\Profile;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Show extends Component
{

    public function createToken()
    {
        $token = auth()->user()->createToken('Main');

        session()->flash('message', 'Token created successfully: ' . $token);

        LivewireAlert::title('Token erstellt:' . $token)
            ->success()
            ->success()
            ->toast()
            ->position('top-end')
            ->show();

        return redirect()->route('admin.profile');
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.profile.show');
    }
}
