<?php

namespace App\Livewire\Admin\Api;

use Livewire\Component;
use App\Models\ApiLogging;

class Logging extends Component
{

    public $logs;

    public function mount()
    {
        $this->logs = ApiLogging::orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.admin.api.logging');
    }
}
