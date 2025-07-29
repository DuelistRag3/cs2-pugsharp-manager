<?php

namespace App\Livewire\Admin\Server;

use App\Models\Server;
use Livewire\Component;
use Livewire\Attributes\Layout;
use xPaw\SourceQuery\SourceQuery;
use Barryvdh\Debugbar\Facades\Debugbar;

use App\Http\Controllers\RconController;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Index extends Component
{

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;

    public $ip;
    public $port;
    public $rcon_password;

    public function createServer()
    {
        $this->validate([
            'ip' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'rcon_password' => 'nullable|string|max:255',
        ]);

        Server::create([
            'ip_address' => $this->ip,
            'port' => $this->port,
            'rcon_password' => $this->rcon_password ?? null,
        ]);

        $this->reset(['ip', 'port', 'rcon_password']);
        LivewireAlert::title(__('manager.server_added'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
        $this->dispatch('serverCreated');
    }

    public function clear($id)
    {
        $server = Server::find($id);

        if (!$server) {
            LivewireAlert::title(__('manager.server_not_found'))
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        new RconController()->sendCommand($server->id, 'ps_stopmatch');

        $server->free();

        LivewireAlert::title(__('manager.server_reset'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function delete($id)
    {
        $server = Server::find($id);
        if ($server) {
            $server->delete();
            LivewireAlert::title(__('manager.server_deleted'))
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } else {
            LivewireAlert::title(__('manager.server_not_found'))
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    public function getServerStatus($id)
    {
        return new RconController()->getServerInfo($id);
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view(
            'livewire.admin.server.index',
            [
                'servers' => Server::paginate($this->perPage),
            ]
        );
    }
}
