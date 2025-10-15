<div class="grid grid-cols-1 auto-rows-auto max-w-10/12 md:max-w-6/12 mx-auto" wire:poll>
    <div>
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">{{ __('manager.select_tab') }}</label>
            <select id="tabs" wire:model='activeTab'
                class="bg-gray-500 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                <option value="scheduled">{{ __('manager.upcoming_tournaments') }}</option>
                <option value="ongoing">{{ __('manager.running_tournaments') }}</option>
                <option value="completed">{{ __('manager.finished_tournaments') }}</option>
            </select>
        </div>
        <ul class="hidden text-sm font-medium text-center text-gray-500 rounded-lg shadow-sm sm:flex">
            <li class="w-full focus-within:z-10">
                <a wire:click='changeTab("scheduled")'
                    class="inline-block w-full p-4 text-white bg-gray-800 border-r border-gray-500 rounded-s-lg cursor-pointer @if($activeTab == 'scheduled') active bg-gray-700! @else hover:bg-gray-700 @endif"
                    aria-current="page">{{ __('manager.upcoming_tournaments') }} <i class="fas fa-spinner fa-spin" wire:loading wire:target='changeTab("scheduled")'></i></a>
            </li>
            <li class="w-full focus-within:z-10">
                <a wire:click='changeTab("ongoing")'
                    class="inline-block w-full p-4 text-white bg-gray-800 border-r border-gray-500 cursor-pointer @if($activeTab == 'ongoing') active bg-gray-700! @else hover:bg-gray-700 @endif">{{
                    __('manager.running_tournaments') }} <i class="fas fa-spinner fa-spin" wire:loading wire:target='changeTab("ongoing")'></i></a>
            </li>
            <li class="w-full focus-within:z-10">
                <a wire:click='changeTab("completed")'
                    class="inline-block w-full p-4 text-white bg-gray-800 border-gray-500 rounded-e-lg cursor-pointer @if($activeTab == 'completed') active bg-gray-700! @else hover:bg-gray-700 @endif">{{
                    __('manager.finished_tournaments') }} <i class="fas fa-spinner fa-spin" wire:loading wire:target='changeTab("completed")'></i></a>
            </li>
        </ul>
    </div>
    <div>
        @forelse($tournaments as $tournament)
        <x-tournament-card :tournament="$tournament" />
        @empty
            {{ __('manager.no_tournaments') }}
        @endforelse
    </div>
    {{-- <i class="fas fa-spinner fa-spin" wire:loading wire:target='changeTab'></i> --}}
</div>