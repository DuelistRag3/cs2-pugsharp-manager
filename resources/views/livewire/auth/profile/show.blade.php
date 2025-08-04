<div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-4">
    <div class="col-span-1">
        <div
            class="w-full max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="flex justify-end px-4 pt-4">
            </div>
            <div class="flex flex-col items-center pb-5">
                <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $user->profilePicture() }}"
                    alt="Bonnie image" />
                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $user->name }}</h5>
                @if($isThisUser)
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
                @if($isThisUser)
                <button wire:click="unlinkSteam"
                    class="focus:outline-none mt-2 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 cursor-pointer">
                    Unlink Steam account
                </button>
                @endif
                @else
                @if($isThisUser)
                <div class="pb-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-1">No Steam account linked.</p>
                    <a href="{{ route('profile.steam.link') }}" class="block mx-auto"><img
                            src="https://community.fastly.steamstatic.com/public/images/signinthroughsteam/sits_01.png"
                            alt="Register Image"></a>
                </div>
                @endif
                @endif

            </div>
        </div>
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
        </div>
    </div>


    <div
        class="block col-span-1 md:col-span-3 p-6 max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">

        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{
            __('manager.matchhistory_title') }}</h5>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Match ID</th>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">Result</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->ongoingMatches() as $match)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $match->id }}</td>
                        <td class="px-6 py-4">{{ $match->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4"></td>
                    </tr>
                    @endforeach
                    @foreach($user->matchHistory() as $match)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $match->id }}</td>
                        <td class="px-6 py-4">{{ $match->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>