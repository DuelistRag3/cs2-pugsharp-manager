<?php

namespace App\Livewire\Auth\Profile;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Show extends Component
{
    public User $user;
    public string $email;

    public function mount($id)
    {
        if (session()->has('steam_already_linked')) {
            LivewireAlert::title('This steam account is already linked to another user.')
                ->warning()
                ->position('top-end')
                ->toast()
                ->show();
        }

        if( session()->has('steam_linked')) {
            LivewireAlert::title('Steam account linked successfully!')
                ->success()
                ->position('top-end')
                ->toast()
                ->show();
        }

        $this->user = User::findOrFail($id);
    }

    public function unlinkSteam($confirmed = false)
    {
        if(!$this->user->isThisUser()) {
            LivewireAlert::title(__('auth.not_allowed'))
                ->text(__('auth.not_allowed_text'))
                ->error()
                ->position('top-end')
                ->toast()
                ->show();
            return;
        }

        if (!$confirmed) {
            LivewireAlert::title(__('manager.steam.unlink_confirmation_title'))
                ->text(__('manager.steam.unlink_confirmation_text'))
                ->asConfirm()
                ->withConfirmButton(__('manager.steam.unlink'))
                ->confirmButtonColor('red')
                ->withDenyButton(__('manager.no'))
                ->denyButtonColor('gray')
                ->onConfirm('unlinkSteam', ['confirmed' => true])
                ->show();
            return;
        }

        // Unlink the Steam account
        $this->user->steam_id = null;
        $this->user->steam_name = null;
        $this->user->steam_avatar = null;
        $this->user->steam_url = null;

        $this->user->save();

        LivewireAlert::title('Steam account unlinked successfully!')
            ->success()
            ->position('top-end')
            ->toast()
            ->show();
    }

    public function addEmail()
    {
        if(!$this->user->isThisUser()) {
            LivewireAlert::title(__('auth.not_allowed'))
                ->text(__('auth.not_allowed_text'))
                ->error()
                ->position('top-end')
                ->toast()
                ->show();
            return;
        }

        if (User::where('email', $this->email)->exists()) {
            LivewireAlert::title(__('auth.email_already_exists'))
                ->error()
                ->position('top-end')
                ->toast()
                ->show();
            return;
        }

        if (empty($this->user->email)) {
            $this->user->email = $this->email;
            $this->user->save();

            LivewireAlert::title(__('auth.email_set'))
                ->success()
                ->position('top-end')
                ->toast()
                ->show();
        } else {
            LivewireAlert::title(__('auth.no_email'))
                ->warning()
                ->position('top-end')
                ->toast()
                ->show();
        }
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.auth.profile.show', ['user' => $this->user]);
    }
}
