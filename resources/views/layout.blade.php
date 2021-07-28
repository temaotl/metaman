<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} &dash; @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="bg-gray-50 text-gray-700 dark:bg-gray-900 dark:text-gray-400 antialiased">

    @include('partials.header')

    <main class="p-4 md:p-8 max-w-screen-xl mx-auto">
        <x-flash-message/>
        @yield('content')
    </main>

    @include('partials.footer')

</body>
</html>