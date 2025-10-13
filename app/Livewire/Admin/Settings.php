<?php

namespace App\Livewire\Admin;

use App\Models\Theme;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Setting as DBSettings;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Settings extends Component
{
    public $themes;
    public $theme;

    public function mount()
    {
        $this->themes = Theme::all();
        $this->theme = DBSettings::where('key', 'theme')->first()->value;
    }

    public function updating($prop, $val)
    {
        if ($prop === 'theme')
        {
            $t = DBSettings::where('key', 'theme')->first();
            $t->value = $val;
            $t->save();

            LivewireAlert::text(__('manager.settings.updated', ['setting' => 'Theme']))
            ->toast()
            ->position('top-end')
            ->success()
            ->show();
        }
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.settings');
    }
}
