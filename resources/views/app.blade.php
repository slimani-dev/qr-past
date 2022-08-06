<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}" sizes="180x180">
    <meta name="theme-color" content="#32138D">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

    <script src="{{ asset('registerSW.js') }}"></script>


    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    @routes
    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body class="font-sans antialiased">
@inertia
</body>
</html>
