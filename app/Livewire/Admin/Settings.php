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
    public $pageTitle;

    public function mount()
    {
        $this->themes = Theme::all();
        $this->theme = env('THEME', 'hltv');
        $this->pageTitle = env('APP_NAME', 'PugSharp Manager');
    }

    public function updating($prop, $val)
    {
        if ($prop === 'pageTitle')
        {
            // Update .env file
            $this->updateEnvFile('APP_NAME', $val);

            LivewireAlert::text(__('manager.settings.updated', ['setting' => 'Page Title']))
            ->toast()
            ->position('top-end')
            ->success()
            ->show();
        }
        if ($prop === 'theme')
        {
            // Update .env file
            $this->updateEnvFile('THEME', $val);

            LivewireAlert::text(__('manager.settings.updated', ['setting' => 'Theme']))
            ->toast()
            ->position('top-end')
            ->success()
            ->show();
        }
    }

    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');
        
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // Check if key exists
            if (preg_match("/^{$key}=.*/m", $content)) {
                // Update existing key
                $content = preg_replace("/^{$key}=.*/m", "{$key}='{$value}'", $content);
            } else {
                // Add new key
                $content .= "\n{$key}={$value}\n";
            }
            
            file_put_contents($path, $content);
        }
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.settings');
    }
}
