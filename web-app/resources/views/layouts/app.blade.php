<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ env('APP_NAME') }}</title>

        <link rel="icon" href="{{ url('images/android-chrome-192x192.png') }}">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        @livewireStyles
        @vite(['resources/css/app.css','resources/js/app.js'])
    </head>
    <body>
        <x-navigation class="menu-background flex-shrink-0 w-56 p-8 hidden md:block overflow-y-auto"/>

        @yield('content')

        @livewireScripts
    </body>
</html>
