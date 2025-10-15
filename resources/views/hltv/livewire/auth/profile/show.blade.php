<div class="grid grid-cols-1 xl:grid-cols-8 gap-2 mb-4 max-w-10/12 md:max-w-6/12 mx-auto">
    <div class="xl:col-span-3">
        <div
            class="w-full max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="flex justify-end px-4 pt-4">
            </div>
            <div class="flex flex-col items-center pb-5">
                <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $user->profilePicture() }}"
                    alt="Bonnie image" />
                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $user->name }}</h5>
                @if($user->isThisUser())
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</span>
                @if(!$user->email)
                <form class="max-w-sm mx-auto" wire:submit.prevent="addEmail">
                    <div class="flex">
                        <button type="submit"
                            class="text-white cursor-pointer bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-none rounded-e-0 text-sm px-5 py-1 rounded-s-lg dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Add</button>
                        <input type="text" id="website-admin"
                            class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-1  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="E-Mail" wire:model="email" required>
                    </div>
                </form>
                @endif
                @endif
                <div
                    class="py-2 w-[95%] mx-auto flex items-center text-sm text-gray-800 before:flex-1 before:border-t before:border-gray-200 before:me-0 after:flex-1 after:border-t after:border-gray-200 after:ms-0 dark:text-white dark:before:border-neutral-600 dark:after:border-neutral-600">
                </div>
                @if($user->steam_id)
                <div class="text-left w-full px-4">
                    Steam Account: <span class="text-sm text-gray-500 dark:text-gray-400"><a
                            href="{{ $user->steam_url }}" target="_blank">{{ $user->steam_name }}</a></span>

                </div>
                @if($user->isThisUser())
                <button wire:click="unlinkSteam"
                    class="focus:outline-none mt-2 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 cursor-pointer">
                    Unlink Steam account
                </button>
                @endif
                @else
                @if($user->isThisUser())
                <div class="pb-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-1">No Steam account linked.</p>
                    <a href="{{ route('steam.link') }}" class="block mx-auto"><img
                            src="https://community.fastly.steamstatic.com/public/images/signinthroughsteam/sits_01.png"
                            alt="Register Image"></a>
                </div>
                @endif
                @endif

            </div>
        </div>
        @if($user->isThisUser() && $user->getNotificationsCount() > 0)
        <div
            class="block mt-2 py-1 px-3 max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{
                __('manager.notifications') }}
            </h5>
            @if($user->teamInvitations())
            <span>{{ __('manager.invites') }}</span>
            @foreach($user->teamInvitations()->get() as $invite)
            <div
                class="flex items-center w-full mt-2 p-2 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-900 dark:border-gray-700">

                <img src="{{ $invite->team->logoUrl() }}" alt="logo" class="w-10 h-10 rounded-full">

                <div class="ml-4">
                    <h2 class="text-lg font-bold dark:text-gray-300">{{ $invite->team->name }}</h2>
                    <div class="inline-flex rounded-md shadow-xs" role="group">
                        <button type="button" wire:click="acceptInvite({{ $invite->id }})"
                            class="px-3 py-2 cursor-pointer text-xs font-medium rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-green-800 dark:border-green-700 dark:text-white dark:hover:text-white dark:hover:bg-green-700 dark:focus:ring-blue-500 dark:focus:text-white">
                            {{ __('manager.accept_invite') }}
                        </button>
                        <button type="button" wire:click="declineInvite({{ $invite->id }})"
                            class="px-3 py-2 cursor-pointer text-xs font-medium rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-red-800 dark:border-red-700 dark:text-white dark:hover:text-white dark:hover:bg-red-700 dark:focus:ring-blue-500 dark:focus:text-white">
                            {{ __('manager.decline_invite') }}
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
        @endif
        <div
            class="block mt-2 py-1 px-3 max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('manager.stats') }}
            </h5>
            <div
                class="py-2 w-full flex text-sm text-gray-800 before:flex-1 before:border-t before:border-gray-200 before:me-0 after:flex-1 after:border-t after:border-gray-200 after:ms-0 dark:text-white dark:before:border-neutral-600 dark:after:border-neutral-600">
            </div>
            @php
            $stats = $user->stats();
            @endphp
            <p class="flex justify-between w-full">Kills: <span
                    class="text-sm text-right text-gray-500 dark:text-gray-400">{{ $stats['kills'] }}</span></p>
            <p class="flex justify-between w-full">Headshots: <span
                    class="text-sm text-right text-gray-500 dark:text-gray-400">{{ $stats['headshots'] }}</span></p>
            <p class="flex justify-between w-full">Deaths: <span
                    class="text-sm text-right text-gray-500 dark:text-gray-400">{{ $stats['deaths'] }}</span></p>
            <p class="flex justify-between w-full">Assists: <span
                    class="text-sm text-right text-gray-500 dark:text-gray-400">{{ $stats['assists'] }}</span></p>
        </div>
        <div
            class="block mt-2 py-1 px-3 max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('manager.teams') }}
            </h5>
            @foreach($user->teams as $team)
            <a href="{{ route('teams.show', $team->id) }}" target="_blank"
                class="flex items-center py-1 {{ $loop->last ? '' : 'border-b' }} border-gray-200 dark:border-gray-700">
                <img class="w-8 h-8 rounded-full mr-2" src="{{ $team->logoUrl() }}" alt="{{ $team->name }}" />
                <span class="text-blue-500">{{ $team->name }}</span>
            </a>
            @endforeach
        </div>
    </div>

    <div
        class="block xl:col-span-5 p-6 max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">

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
                {{-- {{ print_r($user->matchHistory()) }} --}}
                <tbody>
                    @foreach($user->matchHistory() as $match)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4"><a href="{{ route('matches.show', $match->id) }}">{{ $match->id }}</a>
                        </td>
                        <td class="px-6 py-4">{{ $match->tournament->name }}</td>
                        <td class="px-6 py-4">{{ $match->created_at->format(__('manager.timeformat')) }}</td>
                        <td class="px-6 py-4">
                            @php
                            $isTeam1 = $match->team1 && $match->team1->players->contains($user->id);
                            $isTeam2 = $match->team2 && $match->team2->players->contains($user->id);
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
</div>