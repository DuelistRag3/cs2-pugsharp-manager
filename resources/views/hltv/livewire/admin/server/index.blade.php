<div>
    <button type="button" data-modal-target="create-modal" data-modal-toggle="create-modal"
        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 cursor-pointer"><i
            class="fa-solid fa-plus"></i> {{ __('manager.add') }}</button>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-2">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('manager.ip_address') }} / {{ __('manager.hostname') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('manager.port') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('manager.state') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('manager.server_name') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('manager.player') }} / {{ __('manager.max_player') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('manager.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($servers->isEmpty())
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        {{ __('manager.no_servers_found') }}
                    </td>
                </tr>
                @else
                @foreach ($servers as $server)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $server->id }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $server->ip_address }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $server->port }}
                    </td>
                    <div wire:poll>
                        @php
                            $serverInfo = $this->getServerStatus($server->id);
                        @endphp
                        <td class="px-6 py-4">
                            @switch($serverInfo['status'])
                            @case('online')
                            <span class="text-green-500">{{ __('manager.online') }}</span>
                            @break
                            @case('offline')
                            <span class="text-red-500">{{ __('manager.offline') }}</span>
                            @break
                            @default
                            <span class="text-gray-500">{{ __('manager.unknown') }}</span>
                            @endswitch
                        </td>
                    <td class="px-6 py-4">
                        {{ $serverInfo['name'] ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        @if (isset($serverInfo['players']) && isset($serverInfo['max_players']))
                        {{ $serverInfo['players'] }} / {{ $serverInfo['max_players'] }}
                        @else
                        N/A
                        @endif
                    </td>
                    </div>
                    <td class="px-6 py-4">
                        <button wire:click="askClear({{ $server->id }})"
                            class="cursor-pointer text-xs text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fa-solid fa-rotate-right"></i>
                            <span class="sr-only">{{ __('manager.reset') }}</span>
                        <button wire:click="askDelete({{ $server->id }})"
                            class="cursor-pointer text-xs text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg p-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            <i class="fa-solid fa-trash"></i>
                            <span class="sr-only">{{ __('manager.delete') }}</span>
                        </button>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>

        </table>
        @if ($servers)
        {{ $servers->links() }}
        @endif
    </div>

    <div id="create-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ __('manager.add_server') }}
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="create-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <form wire:submit="createServer" class="">
                        <div class="mb-5">
                            <label for="ip"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('manager.ip_address') }} / {{ __('manager.hostname') }}</label>
                            <input wire:model='ip' id="ip" type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('manager.ip_address') }} / {{ __('manager.hostname') }}" required>
                        </div>
                        <div class="mb-5">
                            <label for="port"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('manager.port') }}</label>
                            <input wire:model='port' type="text" id="port"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('manager.port') }}" required />
                        </div>
                        <div class="mb-5">
                            <label for="rcon_password"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rcon {{ __('auth.password') }}</label>
                            <input wire:model='rcon_password' id="rcon_password" type="password"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Rcon {{ __('auth.password') }}">
                        </div>
                </div>
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit"
                        class="cursor-pointer text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('manager.add') }}</button>
                    </form>
                    <button data-modal-hide="create-modal" type="button"
                        class="cursor-pointer py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">{{ __('manager.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('serverCreated', () => {
            const modal = new Modal(document.getElementById('create-modal'));
            if (modal) {
                modal.hide();
                document.querySelector("body > div[modal-backdrop]")?.remove();
            }
        });
</script>
@endscript