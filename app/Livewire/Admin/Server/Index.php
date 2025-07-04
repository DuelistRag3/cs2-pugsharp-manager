<?php

namespace App\Livewire\Admin\Server;

use App\Models\Server;
use Livewire\Component;
use Livewire\Attributes\Layout;
use xPaw\SourceQuery\SourceQuery;
use Barryvdh\Debugbar\Facades\Debugbar;

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
        LivewireAlert::title('Server hinzugefügt')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
        $this->dispatch('serverCreated');
    }

    public function clear($id)
    {
        $server = Server::find($id);

        $server->game_id = null;
        $server->save();
        LivewireAlert::title('Server freigegeben')
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
            LivewireAlert::title('Server gelöscht')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } else {
            LivewireAlert::title('Server nicht gefunden')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    public function getServerStatus($id)
    {
        $server = Server::find($id);
        if ($server) {
            $query = new SourceQuery();
            try {
                $query->Connect($server->ip_address, $server->port, 1, SourceQuery::SOURCE);
                $info = $query->GetInfo();
                $query->Disconnect();

                Debugbar::info('Server status fetched successfully');

                return [
                    'status' => 'online',
                    'name' => $info['HostName'],
                    'players' => $info['Players'],
                    'max_players' => $info['MaxPlayers'],
                ];
            } catch (\Exception $e) {
                Debugbar::error('Server status error: ' . $e->getMessage());
                return ['status' => 'offline'];
            }
        } else {
            Debugbar::warning('Server not found with ID: ' . $id);
            return ['status' => 'unknown'];
        }
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view(
            'livewire.admin.server.index',
            [
                'servers' => Server::where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orderBy($this->sortField, $this->sortDirection)
                    ->paginate($this->perPage),
            ]
        );
    }
}
