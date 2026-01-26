<nav class="bg-white border-gray-200 dark:bg-gray-800" wire:ignore>
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="{{ route('landing') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="{{ Vite::asset('resources/images/cs2logo.ico') }}" width="50">
            <span
                class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">{{ config('app.name') }}</span>
        </a>
        @auth
            <div class="relative flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse" wire:ignore>
                <button type="button"
                    class="relative flex justify-end text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 cursor-pointer"
                    id="user-menu-button" x-data="{ open: false }" @click="open = !open" @click.outside="open = false"
                    @keydown.escape.window="open = false">
                    <span class="sr-only">Open user menu</span>

                    <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profilePicture() }}"
                        alt="{{ Auth::user()->name }}">

                    @if (Auth::user()->getNotificationsCount() > 0)
                        <div style="font-size: 0.5rem;"
                            class="absolute -right-1 -top-1 inline-flex items-center justify-center w-4 h-4 font-bold text-white bg-red-500 border-2 border-white rounded-full dark:border-gray-900">
                            {{ Auth::user()->getNotificationsCount() }}
                        </div>
                    @endif

                    <!-- Dropdown menu -->
                    <div x-show="open" x-transition.origin.top.right style="display:none;"
                        class="absolute right-0 top-full mt-2 z-50 w-56 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600">
                        <div class="px-4 py-3">
                            <span class="block text-sm text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                            <span
                                class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ Auth::user()->email }}</span>
                        </div>

                        <ul class="py-2">
                            @role('admin')
                                <li>
                                    <a href="{{ route('admin.tournaments.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                        Admin Dashboard
                                    </a>
                                </li>
                            @endrole

                            <li>
                                <a href="{{ route('profile.show', Auth::user()->id) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    {{ __('auth.profile') }}
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('logout') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    {{ __('auth.logout') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </button>
                <button data-collapse-toggle="navbar-user" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-controls="navbar-user" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    ...
                </button>
            </div>
        @else
            <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg  md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 dark:border-gray-700">
                    <li>
                        <button id="dropdownLoginButton" data-dropdown-toggle="dropdownLogin"
                            class="block cursor-pointer py-2 px-3 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">{{ __('auth.login') }}</button>
                    </li>
                    <livewire:auth.login />
                    <li>
                        <button id="dropdownRegisterButton" data-dropdown-toggle="dropdownRegister"
                            class="block cursor-pointer py-2 px-3 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">{{ __('auth.register') }}</button>
                    </li>
                    <livewire:auth.register />
                </ul>
            </div>
        @endauth
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
            <ul
                class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg  md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 dark:border-gray-700">
                <x-navigation.navbar-item :link="route('landing')"
                    :active="request()->routeIs('landing')">{{ __('manager.home') }}</x-navigation.navbar-item>
                <x-navigation.navbar-item :link="route('tournaments.index')"
                    :active="request()->routeIs('tournaments.*')">{{ __('manager.tournaments') }}</x-navigation.navbar-item>
                <x-navigation.navbar-item :link="route('teams.index')"
                    :active="request()->routeIs('teams.*')">{{ __('manager.teams') }}</x-navigation.navbar-item>
                <x-navigation.navbar-item :link="route('matches.index')"
                    :active="request()->routeIs('matches.*')">{{ __('manager.matches') }}</x-navigation.navbar-item>
            </ul>
        </div>
    </div>
</nav>
