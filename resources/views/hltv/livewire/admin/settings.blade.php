<div class="grid grid-cols-1 md:grid-cols-6">
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 col-span-2">
        <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ __('manager.settings.headings.general') }}
                </h5>
                <div class="mb-4 mt-4">
                    <label for="pagetitle"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('manager.settings.options.page_title') }}</label>
                    <input type="text" id="pagetitle" wire:model.blur='pageTitle'
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                </div>
                <div>
                    <label for="themes"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('manager.settings.options.theme') }}</label>
                    <select id="themes" wire:model.live='theme'
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                        @foreach ($themes as $aTheme)
                            <option value="{{ $aTheme->name }}">{{ $aTheme->friendly_name }}</option>
                        @endforeach
                    </select>
                </div>
        </div>
    </div>
</div>
