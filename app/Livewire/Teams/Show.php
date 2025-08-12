<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Show extends Component
{
    public Team $team;
    public $search = '';
    public $invitablePlayers = [];
    public $invitePlayers = [];

    public function mount(Team $team)
    {
        $this->team = $team;
        $this->invitablePlayers = User::whereAny([
                'name',
                'steam_name',
                'steam_id',
                'email'
            ], 'like', "%{$this->search}%")
            ->whereNotIn('id', $this->team->players->pluck('id'))
            ->whereNotIn('id', $this->team->pendingInvites->pluck('id'))
            ->where('steam_id', '!=', null)
            ->limit(5)->get();
    }

    #[On('updatedSearch')]
    public function updatedSearch()
    {
        $this->invitablePlayers = User::whereAny([
                'name',
                'steam_name',
                'steam_id',
                'email'
            ], 'like', "%{$this->search}%")
            ->whereNotIn('id', $this->team->players->pluck('id'))
            ->whereNotIn('id', $this->team->pendingInvites->pluck('id'))
            ->where('steam_id', '!=', null)
            ->limit(5)->get();
    }

    public function confirmInvitePlayer(User $player)
    {
        LivewireAlert::title(__('manager.invite_player'))
            ->text(__('manager.invite_player_confirm_text', ['player' => $player->name]))
            ->asConfirm()
            ->withConfirmButton(__('manager.yes'))
            ->confirmButtonColor('green')
            ->withDenyButton(__('manager.no'))
            ->denyButtonColor('gray')
            ->onConfirm('invitePlayer', $player)
            ->show();
    }

    public function invitePlayer(User $player)
    {
        $this->team->pendingInvites()->attach($player);
        LivewireAlert::title(__('manager.invite_player_success', ['player' => $player->name]))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function confirmCancelInvite(User $player)
    {
        LivewireAlert::title(__('manager.cancel_invite'))
            ->text(__('manager.cancel_invite_confirm_text', ['player' => $player->name]))
            ->asConfirm()
            ->withConfirmButton(__('manager.yes'))
            ->confirmButtonColor('red')
            ->withDenyButton(__('manager.no'))
            ->denyButtonColor('gray')
            ->onConfirm('cancelInvite', $player)
            ->show();
    }

    public function cancelInvite(User $player)
    {
        $this->team->pendingInvites()->detach($player);
        LivewireAlert::title(__('manager.cancel_invite_success', ['player' => $player->name]))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.teams.show', ['team' => $this->team]);
    }
}
