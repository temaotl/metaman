<header>
    <noscript>
        <div class="py-4 bg-red-700 dark:bg-red-900 text-red-50 font-bold">
            <div class="px-4 max-w-screen-xl mx-auto flex items-center space-x-4">
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

    <div class="md:h-16 bg-gray-200 dark:bg-gray-800">
        <div class="md:pl-4 h-full max-w-screen-xl mx-auto">
            <nav class="h-full flex flex-col md:flex-row md:items-center justify-between">

                <div class="flex flex-col md:flex-row md:items-center">
                    <p class="h-16 flex items-center">
                        <a class="inline-block px-4 py-2 text-lg font-bold" href="/">{{ config('app.name') }}</a>
                    </p>
                    <ul class="hidden md:flex flex-col md:flex-row" id="navigation">
                        <li><a class="block md:inline-block px-4 py-2 md:rounded hover:bg-gray-400 hover:text-gray-900" href="{{ route('federations.index') }}">{{ __('common.federations') }}</a></li>
                        <li><a class="block md:inline-block px-4 py-2 md:rounded hover:bg-gray-400 hover:text-gray-900" href="{{ route('entities.index') }}">{{ __('common.entities') }}</a></li>
                        @can('do-everything')
                            <li><a class="block md:inline-block px-4 py-2 md:rounded hover:bg-gray-400 hover:text-gray-900" href="{{ route('categories.index') }}">{{ __('common.categories') }}</a></li>
                            <li><a class="block md:inline-block px-4 py-2 md:rounded hover:bg-gray-400 hover:text-gray-900" href="{{ route('groups.index') }}">{{ __('common.groups') }}</a></li>
                        @endcan
                        <li><a class="block md:inline-block px-4 py-2 md:rounded hover:bg-gray-400 hover:text-gray-900" href="{{ route('users.show', Auth::id()) }}">{{ __('common.my_profile') }}</a></li>
                        @can('viewAny', App\Models\User::class)
                            <li><a class="block md:inline-block px-4 py-2 md:rounded hover:bg-gray-400 hover:text-gray-900" href="{{ route('users.index') }}">{{ __('common.users') }}</a></li>
                        @endcan
                    </ul>
                </div>

                <div class="flex flex-col md:flex-row">
                    <ul class="md:pr-4 hidden md:flex flex-col md:flex-row md:text-sm md:items-center" id="profile">
                        {{--
                        <li>
                            <a class="dark:hover:text-white hover:text-gray-700 whitespace-nowrap" href="{{ route('notifications') }}">
                                <div class="md:hidden px-4 py-2 hover:bg-gray-300 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:text-gray-400 md:hover:bg-transparent rounded-md">Notifications ({{ $notifications}})</div>
                                <div class="hidden md:flex justify-center items-center">
                                    <span class="h-5 w-5 rounded-full flex items-center justify-center">{{ $notifications }}</span>
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                            </a>
                        </li>
                        --}}
                        <li>
                            @if (App::currentLocale() == 'cs')
                                <a class="block md:inline-block px-4 py-2 md:rounded hover:bg-gray-400 hover:text-gray-900" href="/language/en" title="Switch to English">EN</a>
                            @else
                                <a class="block md:inline-block px-4 py-2 md:rounded hover:bg-gray-400 hover:text-gray-900" href="/language/cs" title="Přepnout do češtiny">CS</a>
                            @endif

                            <a class="block md:inline-block px-4 py-2 md:rounded hover:bg-gray-400 hover:text-gray-900 whitespace-nowrap"
                            @env(['local', 'testing'])
                                href="/fakelogout"
                            @else
                                href="{{ route('logout') }}"
                            @endenv>{{ __('common.logout') }}</a>
                        </li>
                    </ul>
                </div>

                <div class="block md:hidden absolute top-3 right-4">
                    <button class="p-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700" id="menu">
                        <svg class="block w-6 h-6" id="open-menu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="hidden h-6 w-6" id="close-menu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </nav>
        </div>
    </div>

    <div class="h-10 bg-gray-100 dark:bg-gray-700">
        <div class="px-4 md:px-8 h-full max-w-screen-xl mx-auto">
            <div class="h-full flex items-center justify-between text-lg font-semibold">
                <div>
                    @yield('title')
                </div>
                <div class="flex items-center">
                    @yield('subheader')
                </div>
            </div>
        </div>
    </div>

    <hr class="hidden">
</header>