<a wire:navigate href="{{ $href }}" class="{{ $active ? 'dock-active' : '' }}">
    @include('components.icons.' . $icon)
    <span class="dock-label">{{ $label }}</span>
</a>