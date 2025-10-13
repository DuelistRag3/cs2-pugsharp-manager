@php
$classes = ($active ?? false)
    ? 'text-blue-700 dark:text-blue-500'
    : 'text-gray-900 dark:text-white';
@endphp

<li>
    <a  href="{{ $link }}"
        class="block py-2 px-3 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent {{ $classes }}">{{ $slot }}</a>
</li>
