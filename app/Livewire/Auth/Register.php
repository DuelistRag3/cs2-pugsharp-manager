<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component
{
    #[Validate('required|string|min:3|max:255|unique:users,name')]
    public string $username;
    #[Validate('required|email|max:255|unique:users,email')]
    public string $email;
    #[Validate('required|string|min:8|max:255')]
    public string $password;
    #[Validate('required_with:password|string|min:8|max:255|same:password')]
    public string $password_confirmation;

    public function register()
    {
        $this->validate();

        // Create the user
        $user = \App\Models\User::create([
            'name' => $this->username,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        // Assign the default role
        $user->assignRole('user');

        // Log the user in
        auth()->login($user);

        // Redirect to the landing page
        return redirect()->route('landing');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
