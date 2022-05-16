<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} &dash; Maintenance in Progress...</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="antialiased">
    <div
        class="items-top dark:bg-gray-900 sm:items-center sm:pt-0 relative flex justify-center min-h-screen bg-gray-100">
        <div class="sm:px-6 lg:px-8 max-w-xl mx-auto">
            <div class="sm:justify-start sm:pt-0 flex items-center pt-8">
                <div class="px-4 text-lg tracking-wider text-gray-500 border-r border-gray-400">
                    503 </div>

                <div class="ml-4 text-lg tracking-wider text-gray-500 uppercase">
                    Maintenance in Progress...</div>
            </div>
        </div>
    </div>
</body>

</html>
