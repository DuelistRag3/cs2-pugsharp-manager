<div class="grid grid-cols-1 xl:grid-cols-8 gap-2 mb-4 max-w-10/12 md:max-w-6/12 mx-auto">
    @auth
    @if(Auth::user()->isTeamCaptain($team))
    <div class="col-span-1 xl:col-span-8">
        <div class="inline-flex rounded-md shadow-xs" role="group">
            <button type="button" data-modal-target="invite-modal" data-modal-toggle="invite-modal"
                class="px-4 py-2 cursor-pointer text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-green-800 dark:border-green-700 dark:text-white dark:hover:text-white dark:hover:bg-green-700 dark:focus:ring-blue-500 dark:focus:text-white">
                {{ __('manager.invite_player') }}
            </button>
            <button type="button"
                class="px-4 py-2 cursor-pointer text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-blue-800 dark:border-blue-700 dark:text-white dark:hover:text-white dark:hover:bg-blue-700 dark:focus:ring-blue-500 dark:focus:text-white">
                {{ __('manager.edit_team') }}
            </button>
            <button type="button"
                class="px-4 py-2 cursor-pointer text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-red-800 dark:border-red-700 dark:text-white dark:hover:text-white dark:hover:bg-red-700 dark:focus:ring-blue-500 dark:focus:text-white">
                {{ __('manager.delete_team') }}
            </button>
        </div>

    </div>
    @endif
    @endauth
    <div class="xl:col-span-3">
        <div
            class="w-full max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="flex justify-end px-4 pt-4">
            </div>
            <div class="flex flex-col items-center pb-5">
                <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $team->logoUrl() }}" alt="Team image" />
                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $team->name }}</h5>

            </div>
        </div>
        <div
            class="block mt-2 py-1 px-3 max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('manager.players') }}
            </h5>
            @foreach($team->players as $player)
            <a href="{{ route('profile.show', $player->id) }}" target="_blank"
                class="flex items-center py-1 {{ $loop->last ? '' : 'border-b' }} border-gray-200 dark:border-gray-700">
                <img class="w-8 h-8 rounded-full mr-2" src="{{ $player->profilePicture() }}"
                    alt="{{ $player->name }}" />
                <span class="text-gray-900 dark:text-white">{{ $player->name }} @if($player->isTeamCaptain($team)) <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">Captain</span> @endif</span>
            </a>
            @endforeach
        </div>
        @auth
        @if(Auth::user()->isTeamCaptain($team))
        <div
            class="block mt-2 py-1 px-3 max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('manager.pending_invites') }}
            </h5>
            @foreach($team->pendingInvites as $player)
            <span class="flex items-center py-1 {{ $loop->last ? '' : 'border-b' }} border-gray-200 dark:border-gray-700">
                <img class="w-8 h-8 rounded-full mr-2" src="{{ $player->profilePicture() }}"
                    alt="{{ $player->name }}" />
                <span class="text-gray-900 dark:text-white">{{ $player->name }}</span><i wire:click='confirmCancelInvite({{ $player->id }})' class="fa-solid fa-x mr-0 ml-auto cursor-pointer text-red-500"></i>
            </span>
            @endforeach
        </div>
        @endif
        @endauth
    </div>

    <div class="block xl:col-span-5 p-6 max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{
            __('manager.matchhistory_title') }}</h5>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">{{ __('manager.tournament') }}</th>
                        <th scope="col" class="px-6 py-3">{{ __('manager.date') }}</th>
                        <th scope="col" class="px-6 py-3">{{ __('manager.result') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($team->games()->where('status', 'completed')->get() as $match)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4"><a href="{{ route('matches.show', $match->id) }}">{{ $match->id }}</a>
                        </td>
                        <td class="px-6 py-4">{{ $match->tournament->name }}</td>
                        <td class="px-6 py-4">{{ $match->created_at->format(__('manager.timeformat')) }}</td>
                        <td class="px-6 py-4">
                            @php
                            $isTeam1 = $match->team1->id == $team->id;
                            $isTeam2 = $match->team2->id == $team->id;;
                            @endphp
                            @if($isTeam1)
                            {{ $match->team1_maps_won }} : {{ $match->team2_maps_won }}
                            @elseif($isTeam2)
                            {{ $match->team2_maps_won }} : {{ $match->team1_maps_won }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @auth
    @if(Auth::user()->isTeamCaptain($team))
    <!-- Invite modal -->
    <div id="invite-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ __('manager.invite_player') }}
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="invite-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <div class="mb-5">
                        <label for="search" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('manager.search') }}</label>
                        <input id="searchInput" wire:model.live='search' type="text" id="search"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="{{ __('manager.search_placeholder') }}" required />
                    </div>
                    @foreach($invitablePlayers as $player)
                    <div wire:click="confirmInvitePlayer({{ $player->id }})"
                        class="flex items-center w-full cursor-pointer mt-2 p-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-900">

                        <img src="{{ $player->profilePicture() }}" alt="logo" class="w-10 h-10 rounded-full">

                        <div class="ml-4">
                            <h2 class="text-lg font-bold dark:text-gray-300">{{ $player->name }}</h2>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    @endauth
</div>