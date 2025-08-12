<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function mount()
    {}

    public function login()
    {
        // Handle the login logic here
        // For example, you might want to validate the input and authenticate the user
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log in the user
        if (auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            // Redirect to the intended page or dashboard
            return redirect()->route('landing');
        }

        // If login fails, add an error message
        LivewireAlert::title(__('auth.failed'))->error()->toast()->position('top-end')->show();
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
