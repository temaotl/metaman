<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="p-4 sm:p-16 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-400 antialiased">

    <div class="max-w-screen-md mx-auto">

        <div class="p-4 sm:p-8 bg-gray-100 dark:bg-gray-800 border dark:border-gray-500 rounded shadow-md bg-gradient-to-br from-gray-100 dark:from-gray-800 to-gray-100 dark:to-gray-800 via-gray-200 dark:via-gray-900">

            <header>
                <h1 class="pb-4 text-4xl sm:text-5xl font-bold tracking-wider">{{ config('app.name') }}</h1>
                <p class="sm:text-lg leading-relaxed">{!! __('welcome.blocked_account') !!}</p>
                <div class="my-8 w-full h-px bg-gradient-to-r from-gray-100 dark:from-gray-800 to-gray-100 dark:to-gray-800 via-gray-800 dark:via-gray-100"></div>
                <hr class="hidden">
            </header>

            <main class="pb-4 sm:pb-8">
                <p>{{ __('welcome.blocked_info') }} <a class="text-blue-500 hover:underline" href="mailto:info@eduid.cz">info@eduid.cz</a>.</p>
            </main>

            <footer>
                <div class="mt-4 mb-3 w-full h-px bg-gradient-to-r from-gray-100 dark:from-gray-800 to-gray-100 dark:to-gray-800 via-gray-300 dark:via-gray-600"></div>
                <hr class="hidden">
                <p class="text-center opacity-75">
                    <small class="text-sm">
                        &copy; 2019&dash;{{ date('Y') }} <a class="text-blue-500 hover:underline" href="https://www.cesnet.cz">CESNET</a>, 
                        <a class="text-blue-500 hover:underline" href="mailto:info@eduid.cz">info@eduid.cz</a>.
                    </small>
                </p>
            </footer>

        </div>

    </div>

</body>
</html>