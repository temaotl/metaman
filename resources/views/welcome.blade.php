<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="p-4 sm:p-16 bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-400 antialiased">

    <div class="max-w-screen-md mx-auto">

        <noscript>
            <div class="mb-4 p-4 sm:px-8 bg-red-700 dark:bg-red-900 text-red-50 font-bold rounded shadow">
                <div class="flex items-center space-x-4">
                    <div class="p-2 bg-red-900 dark:bg-red-700 rounded">
                        <svg class="w-6 h-6 text-red-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <p>
                        <span class="sm:hidden">{{ __('common.javascript_short') }}</span>
                        <span class="hidden sm:inline">{{ __('common.javascript_long') }}</span>
                    </p>
                </div>
            </div>
        </noscript>

        <div class="p-4 sm:p-8 bg-gray-100 dark:bg-gray-800 border dark:border-gray-500 rounded shadow-md bg-gradient-to-br from-gray-100 dark:from-gray-800 to-gray-100 dark:to-gray-800 via-gray-200 dark:via-gray-900">

            <header>
                <h1 class="pb-4 text-4xl sm:text-5xl font-bold tracking-wider">{{ config('app.name') }}</h1>
                <p class="pb-4 text-right text-blue-500 font-bold">[
                    @if (App::currentLocale() == 'cs')
                        <a href="/language/en">
                    @else
                        <a href="/language/cs">
                    @endif

                    {{ __('welcome.switch_language') }}</a>
                ]</p>
                <p class="sm:text-lg leading-relaxed">{!! __('welcome.introduction') !!}</p>
                <div class="my-8 w-full h-px bg-gradient-to-r from-gray-100 dark:from-gray-800 to-gray-100 dark:to-gray-800 via-gray-800 dark:via-gray-100"></div>
                <hr class="hidden">
            </header>

            <main class="pb-4 sm:pb-8">
                <p class="pb-4">{{ __('welcome.requested_attributes') }}</p>

                <ul class="list-disc list-inside">
                    <li>
                        <a class="text-blue-500 hover:underline" href="https://www.eduid.cz/cs/tech/attributes/cn">cn</a> ({{ __('welcome.cn') }})
                    </li>
                    <li>
                        <a class="text-blue-500 hover:underline" href="https://www.eduid.cz/cs/tech/attributes/edupersonuniqueid">uniqueId</a> ({{ __('welcome.uniqueid') }})
                    </li>
                    <li>
                        <a class="text-blue-500 hover:underline" href="https://www.eduid.cz/cs/tech/attributes/mail">mail</a> ({{ __('welcome.mail') }})
                    </li>
                </ul>

                <p class="pt-6 sm:pt-10 text-center">
                    <a class="px-6 py-3 block md:inline-block bg-blue-500 hover:bg-blue-600 text-blue-50 font-bold rounded shadow hover:shadow-lg" href="{{ route('login') }}">{{ __('common.login') }}</a>
                </p>
            </main>

            <footer>
                <div class="mt-4 mb-3 w-full h-px bg-gradient-to-r from-gray-100 dark:from-gray-800 to-gray-100 dark:to-gray-800 via-gray-300 dark:via-gray-600"></div>
                <hr class="hidden">
                <p class="text-center opacity-75">
                    <small class="text-sm">
                        <a class="text-blue-500 hover:underline" href="{{ __('welcome.pii-link') }}">{{ __('welcome.pii-text') }}</a><br>
                        &copy; 2019&dash;{{ date('Y') }} <a class="text-blue-500 hover:underline" href="https://www.cesnet.cz">CESNET</a>, 
                        <a class="text-blue-500 hover:underline" href="mailto:info@eduid.cz">info@eduid.cz</a>.
                    </small>
                </p>
            </footer>

        </div>

        @if (App::environment(['local', 'testing']))
            <hr class="hidden">
            <div class="mt-4 bg-blue-100 text-blue-900 rounded shadow">
                <h2 class="p-4 bg-blue-400 sm:text-xl font-semibold rounded-t">Login without authentication</h2>
                <form action="/fakelogin" method="POST">
                    @csrf
                    <div class="p-4 flex flex-col sm:flex-row space-y-5 sm:justify-between">
                        <div class="flex items-center space-x-5">
                            <label class="font-semibold" for="user_id">User ID:</label>
                            <input class="w-20" type="number" name="id" id="user_id" value="1" min="1" required>
                        </div>
                        <button class="px-6 py-3 bg-blue-300 hover:bg-blue-400 rounded shadow hover:shadow-lg" type="submit">Fake Login</button>
                    </div>
                </form>
            </div>
        @endif

    </div>

</body>
</html>