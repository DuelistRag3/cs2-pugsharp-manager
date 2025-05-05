<?php

use Livewire\Volt\Component;
use App\Models\Setting;

new class extends Component {
    
    public string $name = '';
    public string $locale = '';

    public function mount(): void
    {
        $this->name = Setting::where('key', 'app.name')->first()->value;
        $this->locale = Setting::where('key', 'app.language')->first()->value;
    }

    public function updateAppSettings(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'locale' => ['required', 'string', 'in:en,de'],
        ]);



        Session::put('locale', $this->locale);
        app()->setLocale($this->locale);
        Setting::where('key', 'app.name')->update(['value' => $this->name]);
        Setting::where('key', 'app.language')->update(['value' => $this->locale]);
        $this->dispatch('app-settings-updated', name: $this->name, locale: $this->locale);
    }
}; 
?>

<section class="w-full">
    @include('partials.settings-heading')
    <x-settings.layout :heading="__('dashboard.settings.app.title')" :subheading="__('dashboard.settings.app.description')" :label="__('dashboard.settings.app.language')">
        <form wire:submit='updateAppSettings' class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('dashboard.settings.app.name')" type="text" required autofocus autocomplete="name" />
            <flux:radio.group wire:model='locale' label="{{ __('dashboard.settings.app.language') }}" variant="segmented"
                class="max-sm:flex-col">
                <flux:radio value="en" label="{{ __('dashboard.settings.app.languages.en') }}" />
                <flux:radio value="de" label="{{ __('dashboard.settings.app.languages.de') }}" />
            </flux:radio.group>
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('dashboard.settings.save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="app-settings-updated">
                    {{ __('dashboard.settings.app.saved') }}
                </x-action-message>
            </div>
    </x-settings.layout>
</section>
