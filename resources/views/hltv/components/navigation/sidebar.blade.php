
<button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
   <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
      <a wire:navigate href="{{ route('landing') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="{{ Vite::asset('resources/images/cs2logo.ico') }}" width="50">
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Dashboard</span>
    </a>
      <ul class="space-y-2 font-medium">
         <x-navigation.sidebar-item href="{{ route('admin.tournaments.index') }}" :active="request()->routeIs('admin.tournaments.*')" icon="fa-solid fa-trophy">{{ __('manager.tournaments') }}</x-navigation.sidebar-item>
         <x-navigation.sidebar-item href="{{ route('admin.maps.index') }}" :active="request()->routeIs('admin.maps.index')" icon="fa-solid fa-map">{{ __('manager.maps') }}</x-navigation.sidebar-item>
         <x-navigation.sidebar-item href="{{ route('admin.server.index') }}" :active="request()->routeIs('admin.server.index')" icon="fa-solid fa-server">{{ __('manager.server') }}</x-navigation.sidebar-item>
         <x-navigation.sidebar-item href="{{ route('admin.api.logging') }}" :active="request()->routeIs('admin.api.logging')" icon="fa-solid fa-server">{{ __('manager.api.logging') }}</x-navigation.sidebar-item>
         <x-navigation.sidebar-item href="{{ route('admin.settings') }}" :active="request()->routeIs('admin.settings')" icon="fa-solid fa-cog">{{ __('manager.settings.title') }}</x-navigation.sidebar-item>
         <x-navigation.sidebar-item href="{{ route('landing') }}" :active="request()->routeIs('landing')" icon="fa-solid fa-home">{{ __('manager.home') }}</x-navigation.sidebar-item>
         <x-navigation.sidebar-item href="{{ route('logout') }}" :active="request()->routeIs('logout')" icon="fa-solid fa-right-from-bracket">{{ __('auth.logout') }}</x-navigation.sidebar-item>
      </ul>
   </div>
</aside>

<div class="p-4 sm:ml-64">
 {{ $slot }}
</div>
