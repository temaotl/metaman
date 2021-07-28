@extends('layout')
@section('title', __('common.users'))

@section('subheader')

    <a class="px-2 py-1 bg-gray-300 hover:bg-gray-200 border border-gray-400 dark:bg-gray-900 dark:hover:bg-gray-700 text-sm rounded" href="{{ route('users.create') }}">{{ __('common.add') }}</a>

@endsection

@section('content')

    <div class="mb-4">
        <form class="">
            <label class="sr-only" for="search">{{ __('common.search') }}</label>
            <input class="px-4 py-2 w-full border rounded-lg dark:bg-transparent" type="text" name="search" id="search" value="{{ request('search') }}" placeholder="{{ __('users.searchbox') }}" autofocus>
        </form>
    </div>
    
    <div>
        <div class="bg-white dark:bg-transparent border rounded-lg overflow-x-auto">
            <table class="min-w-full border-b border-gray-300">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">{{ __('common.name') }}</th>
                        <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">{{ __('common.email') }}</th>
                        <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">{{ __('common.status') }}</th>
                        <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 clickable">
                    @foreach ($users as $user)
                        <tr class="hover:bg-blue-50 dark:hover:bg-gray-700" role="button">
                            <td class="px-6 py-3 text-sm whitespace-nowrap">
                                <div class="font-bold">
                                    {{ $user->name }}
                                </div>
                                <div class="text-gray-400">
                                    {{ $user->uniqueid }}
                                </div>
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <a class="text-blue-600 hover:underline" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <x-pils.status :model="$user" />
                            </td>
                            <td class="px-6 py-3 text-sm text-right">
                                <a class="text-blue-600 hover:underline link" href="{{ route('users.show', $user->id) }}">{{ __('common.show') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->withQueryString()->links() }}
        </div>
    </div>

@endsection