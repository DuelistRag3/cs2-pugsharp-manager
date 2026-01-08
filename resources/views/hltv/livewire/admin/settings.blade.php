<div>
    <div class="bg-neutral-primary-soft block max-w-sm p-6 border border-default rounded-base shadow-xs">
        <h5 class="mb-3 text-2xl font-semibold tracking-tight text-heading leading-8">{{ __('manager.settings.headings.general') }}</h5>
        <p class="text-body mb-6">
            <label for="themes"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('manager.settings.theme') }}</label>
        <select id="themes" wire:model.live='theme'
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

            @foreach ($themes as $aTheme)
                <option value="{{ $aTheme->name }}">{{ $aTheme->friendly_name }}</option>
            @endforeach
        </select>
        </p>
    </div>

    

</div>
