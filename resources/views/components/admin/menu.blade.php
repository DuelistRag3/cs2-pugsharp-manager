<div class="dock">
    <x-admin.menu-item :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" label="Dashboard" icon="home" />
    <x-admin.menu-item :href="route('admin.server')" :active="request()->routeIs('admin.server')" label="Server" icon="server-stack" />
    <x-admin.menu-item :href="route('admin.tournaments')" :active="request()->routeIs('admin.tournaments')" label="Tournaments" icon="play" />
    <x-admin.menu-item :href="route('admin.settings')" :active="request()->routeIs('admin.settings')" label="Settings" icon="settings" />
    <x-admin.menu-item :href="route('home')" :active="request()->routeIs('home')" label="Frontpage" icon="back" />
</div>
