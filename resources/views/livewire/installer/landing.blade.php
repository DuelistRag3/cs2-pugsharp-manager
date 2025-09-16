<div>
    <h2 class="text-2xl font-semibold">{{ __('installer.title') }}</h2>
    <p class="mb-4">{{ __('installer.welcome', ['app' => config('app.name'), 'version' => config('app.version')]) }}</p>
    <br>
    <a wire:navigate href="{{ route('install.general') }}"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('installer.start') }}</a>

</div>
