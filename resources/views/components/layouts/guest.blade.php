<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>

    @vite(['resources/js/app.js'])
    @vite(['resources/css/app.css'])

    <link rel="icon" type="image/x-icon" href="{{ Vite::asset('resources/images/cs2logo.ico') }}">
    @livewireStyles
</head>
<body class="dark:bg-gray-900 text-black dark:text-white">
    <x-navigation.navbar />

    @auth
        @if(Auth::user()->email == null)
            <div class="bg-yellow-100 text-yellow-800 p-4 text-center">
                {{ __('auth.no_email') }}
                <a href="{{ route('profile.show') }}" class="text-blue-600 hover:underline">{{ __('auth.set_email') }}</a>
            </div>
        @endif   
    @endauth

    <div class="container mx-auto px-4 py-8 min-h-screen">
        {{ $slot }}
    </div>

    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto text-center">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </footer>

    @livewireScripts
</body>
</html>