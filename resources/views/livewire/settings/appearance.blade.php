<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('dashboard.settings.appearance.title')" :subheading=" __('dashboard.settings.appearance.description')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('dashboard.settings.appearance.light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('dashboard.settings.appearance.dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('dashboard.settings.appearance.system') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>
