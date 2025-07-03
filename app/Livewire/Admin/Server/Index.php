<?php

namespace App\Livewire\Admin\Server;

use App\Models\Server;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Index extends Component
{

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;

    public $name;
    public $ip;
    public $port;

    public function createServer()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'ip' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
        ]);

        Server::create([
            'name' => $this->name,
            'ip_address' => $this->ip,
            'port' => $this->port,
        ]);

        $this->reset(['name', 'ip', 'port']);
        LivewireAlert::title('Server hinzugefügt')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
        $this->dispatch('serverCreated');
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
