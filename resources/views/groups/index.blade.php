@extends('layout')
@section('title', __('common.groups'))

@section('subheader')

    @can('do-everything')
        <a class="px-2 py-1 bg-gray-300 hover:bg-gray-200 border border-gray-400 dark:bg-gray-900 dark:hover:bg-gray-700 text-sm rounded mr-1" href="{{ route('groups.import') }}">{{ __('common.import') }}</a>
        <a class="px-2 py-1 bg-gray-300 hover:bg-gray-200 border border-gray-400 dark:bg-gray-900 dark:hover:bg-gray-700 text-sm rounded mr-1" href="{{ route('groups.refresh') }}">{{ __('common.refresh') }}</a>
    @endcan

    <a class="px-2 py-1 bg-gray-300 hover:bg-gray-200 border border-gray-400 dark:bg-gray-900 dark:hover:bg-gray-700 text-sm rounded" href="{{ route('groups.create') }}">{{ __('common.add') }}</a>

@endsection

@section('content')

    <div class="mb-4">
        <form>
            <label class="sr-only" for="search">{{ __('common.search') }}</label>
            <input class="px-4 py-2 w-full border rounded-lg dark:bg-transparent" type="text" name="search" id="search" value="{{ request('search')}}" placeholder="{{ __('groups.searchbox') }}" autofocus>
        </form>
    </div>

    <div>
        <div class="bg-white dark:bg-transparent border rounded-lg overflow-x-auto">
            <table class="min-w-full border-b border-gray-300">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">{{ __('common.name') }}</th>
                        <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">{{ __('common.description') }}</th>
                        <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">{{ __('common.entities') }}</th>
                        <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 clickable">
                    @forelse ($groups as $group)
                        <tr class="hover:bg-blue-50 dark:hover:bg-gray-700" role="button">
                            <td class="px-6 py-3 text-sm">
                                {{ $group->name }}
                            </td>
                            <td class="px-6 py-3 text-sm">
                                {{ $group->description }}
                            </td>
                            <td class="px-6 py-3 text-sm">
                                {{ $group->entities->count() }}
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <a class="text-blue-500 hover:underline link" href="{{ route('groups.show', $group) }}">{{ __('common.show') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-blue-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-3 text-center font-bold" colspan="4">
                                {{ __('groups.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $groups->withQueryString()->links() }}
        </div>
    </div>

@endsection