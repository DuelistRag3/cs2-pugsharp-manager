<!DOCTYPE html>
<html lang="en">
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
<body class="dark:bg-gray-900">
    <x-navigation.navbar />

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