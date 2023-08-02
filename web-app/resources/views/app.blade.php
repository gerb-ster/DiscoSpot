<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        {{-- Inertia --}}
        <script src="https://polyfill.io/v3/polyfill.min.js?features=smoothscroll,NodeList.prototype.forEach,Promise,Object.values,Object.assign" defer></script>
        {{-- Ping CRM --}}
        <script src="https://polyfill.io/v3/polyfill.min.js?features=String.prototype.startsWith" defer></script>
        @inertiaHead
        @vite('resources/js/app.js')
        @routes
    </head>
    <body>
        @inertia
    </body>
</html>
