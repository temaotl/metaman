@extends('layout')
@section('title', __('common.users'))

@section('subheader')

    <a class="hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 px-2 py-1 text-sm bg-gray-300 border border-gray-400 rounded" href="{{ route('users.create') }}">{{ __('common.add') }}</a>

@endsection

@section('content')

    <div class="mb-4">
        <form class="">
            <label class="sr-only" for="search">{{ __('common.search') }}</label>
            <input class="dark:bg-transparent w-full px-4 py-2 border rounded-lg" type="text" name="search" id="search" value="{{ request('search') }}" placeholder="{{ __('users.searchbox') }}" autofocus>
        </form>
    </div>
    
    <div>
        <div class="dark:bg-transparent overflow-x-auto bg-white border rounded-lg">
            <table class="min-w-full border-b border-gray-300">
                <thead>
                    <tr>
                        <th class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">{{ __('common.name') }}</th>
                        <th class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">{{ __('common.email') }}</th>
                        <th class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">{{ __('common.status') }}</th>
                        <th class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="clickable divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr class="hover:bg-blue-50 dark:hover:bg-gray-700" role="button">
                            <td class="whitespace-nowrap px-6 py-3 text-sm">
                                <div class="font-bold">
                                    {{ $user->name }}
                                </div>
                                <div class="text-gray-400">
                                    {{ $user->uniqueid }}
                                </div>
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <a class="hover:underline text-blue-600" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <x-pils.status :model="$user" />
                            </td>
                            <td class="px-6 py-3 text-sm text-right">
                                <a class="hover:underline link text-blue-600" href="{{ route('users.show', $user->id) }}">{{ __('common.show') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->withQueryString()->links() }}
        </div>
    </div>

@endsection