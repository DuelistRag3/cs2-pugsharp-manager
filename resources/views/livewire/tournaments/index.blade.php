<div class="grid auto-rows-auto max-w-10/12 md:max-w-6/12 mx-auto" wire:poll>
    <button data-modal-target="finished_tournaments-modal" data-modal-toggle="finished_tournaments-modal"
        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer w-1/5"
        type="button">
        {{ __('manager.finished_tournaments') }}
    </button>
    <div>
        <h1 class="text-2xl text-bold">{{ __('manager.running_tournaments') }}</h1>
        @foreach ($runningTournaments as $running)
        <x-tournament-card :tournament="$running" />
        @endforeach
    </div>
    <div>
        <h1 class="text-2xl text-bold">{{ __('manager.upcoming_tournaments') }}</h1>
        @foreach ($upcomingTournaments as $upcoming)
        <x-tournament-card :tournament="$upcoming" />
        @endforeach
    </div>

    <div id="finished_tournaments-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ __('manager.finished_tournaments') }}
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <div class="relative overflow-x-auto">
                        {{-- <div class="relative mt-1 mb-1">
                            <div
                                class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                <i class="fas fa-search"></i>
                            </div>
                            <input type="text" id="table-search" wire:model.live='search'
                                class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('manager.search') }}">
                        </div> --}}
                        <table class="w-full text-sm text-left rtl:text-right text-gray-600">
                            <thead class="text-xs text-gray-50 uppercase bg-gray-900">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('manager.name') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('manager.winner') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('manager.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($finishedTournaments as $finished)
                                <tr class="bg-gray-800 border-b  border-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                        {{ $finished->id }}
                                    </th>
                                    <td class="px-6 py-4 text-white">
                                        {{ $finished->name }}
                                    </td>
                                    <td class="px-6 py-4 text-white">
                                        {{ $finished->games()->where('next_game_id', null)->first()->winnerTeam->name }}
                                    </td>
                                    <td class="px-6 py-4 text-white">
                                        <a type="button" href="{{ route('tournaments.show', $finished->id) }}"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full p-2.5 text-center inline-flex items-center me-2 cursor-pointer text-xs">
                                            <i class="fas fa-eye"></i>
                                            <span class="sr-only">View</span>
                                            </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="finished_tournaments-modal" type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer">{{
                        __('manager.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>