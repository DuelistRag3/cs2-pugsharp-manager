<?php

namespace App\Livewire\Admin\Maps;

use Livewire\Component;
use App\Models\Tournament;
use App\Models\AvailableMaps;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Barryvdh\Debugbar\Facades\Debugbar;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Index extends Component
{
    use WithFileUploads;

    public $maps = [];

    #[Validate('required|string|max:255|unique:available_maps,name')]
    public $name;
    #[Validate('required|string|max:255|unique:available_maps,map_code')]
    public $map_code;
    #[Validate('nullable|image|max:16432')] // Max 16MB image
    public $mapThumbnail;

    public function mount()
    {
        // Load available maps from the database
        $this->maps = AvailableMaps::all();
    }

    public function add()
    {
        if ($this->mapThumbnail != null) {
            $originalName = $this->mapThumbnail->getClientOriginalName();

            AvailableMaps::create([
                'name' => $this->name,
                'map_code' => $this->map_code,
                'image_name' => $originalName,
            ]);

            $this->mapThumbnail->storePubliclyAs(path: 'maps_thumbnails', name: $originalName);
        } else {
            AvailableMaps::create([
                'name' => $this->name,
                'map_code' => $this->map_code,
            ]);
        }


        // Reload maps
        $this->maps = AvailableMaps::all();

        LivewireAlert::title(__('manager.map_added'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();

        $this->reset(['name', 'map_code', 'mapThumbnail']);
        $this->dispatch('mapAdded');
    }

    public function addDefaultMaps()
    {
        $defaultMaps = [
            ['name' => 'Dust II', 'map_code' => 'de_dust2'],
            ['name' => 'Mirage', 'map_code' => 'de_mirage'],
            ['name' => 'Inferno', 'map_code' => 'de_inferno'],
            ['name' => 'Nuke', 'map_code' => 'de_nuke'],
            ['name' => 'Overpass', 'map_code' => 'de_overpass'],
            ['name' => 'Ancient', 'map_code' => 'de_ancient'],
            ['name' => 'Anubis', 'map_code' => 'de_anubis'],
        ];

        foreach ($defaultMaps as $defaultMap) {
            AvailableMaps::firstOrCreate($defaultMap);
        }

        // Reload maps
        $this->maps = AvailableMaps::all();

        LivewireAlert::title(__('manager.active_mappool_added'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function confirmDelete(AvailableMaps $map)
    {
        LivewireAlert::title(__('manager.delete_map'))
            ->text(__('manager.delete_map_text'))
            ->asConfirm()
            ->withConfirmButton(__('manager.yes'))
            ->confirmButtonColor('red')
            ->withDenyButton(__('manager.no'))
            ->denyButtonColor('gray')
            ->onConfirm('delete', $map)
            ->show();
        return;
    }

    public function delete(AvailableMaps $map)
    {
        $map->delete();

        // Reload maps
        $this->maps = AvailableMaps::all();

        LivewireAlert::title(__('manager.map_deleted'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function confirmDeleteAll()
    {
        LivewireAlert::title(__('manager.delete_all_maps'))
            ->text(__('manager.delete_all_maps_text'))
            ->asConfirm()
            ->withConfirmButton(__('manager.yes'))
            ->confirmButtonColor('red')
            ->withDenyButton(__('manager.no'))
            ->denyButtonColor('gray')
            ->onConfirm('deleteAll')
            ->show();
        return;
    }

    public function deleteAll()
    {
        AvailableMaps::query()->delete();

        // Reload maps
        $this->maps = AvailableMaps::all();

        LivewireAlert::title(__('manager.maps_deleted'))
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.maps.index');
    }
}
