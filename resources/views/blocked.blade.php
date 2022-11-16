<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>

<body class="sm:p-16 bg-gray-50 dark:bg-gray-900 dark:text-gray-400 p-4 antialiased text-gray-800">

    <div class="max-w-screen-md mx-auto">

        <div
            class="sm:p-8 dark:bg-gray-800 dark:border-gray-500 bg-gradient-to-br from-gray-100 dark:from-gray-800 to-gray-100 dark:to-gray-800 via-gray-200 dark:via-gray-900 p-4 bg-gray-100 border rounded shadow-md">

            <header>
                <h1 class="sm:text-5xl pb-4 text-4xl font-bold tracking-wider">{{ config('app.name') }}</h1>
                <p class="sm:text-lg leading-relaxed">{!! __('welcome.blocked_account') !!}</p>
                <div
                    class="bg-gradient-to-r from-gray-100 dark:from-gray-800 to-gray-100 dark:to-gray-800 via-gray-800 dark:via-gray-100 w-full h-px my-8">
                </div>
                <hr class="hidden">
            </header>

            <main class="sm:pb-8 pb-4">
                <p>{{ __('welcome.blocked_info') }} <a class="hover:underline text-blue-500"
                        href="mailto:info@eduid.cz">info@eduid.cz</a>.</p>
            </main>

            <footer>
                <div
                    class="bg-gradient-to-r from-gray-100 dark:from-gray-800 to-gray-100 dark:to-gray-800 via-gray-300 dark:via-gray-600 w-full h-px mt-4 mb-3">
                </div>
                <hr class="hidden">
                <p class="text-center opacity-75">
                    <small class="text-sm">
                        &copy; 2019&dash;{{ date('Y') }} <a class="hover:underline text-blue-500"
                            href="https://www.cesnet.cz">CESNET</a>,
                        <a class="hover:underline text-blue-500" href="mailto:info@eduid.cz">info@eduid.cz</a>.
                    </small>
                </p>
            </footer>

        </div>

    </div>

</body>

</html>
