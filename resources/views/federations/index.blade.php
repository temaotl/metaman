@extends('layout')
@section('title', __('common.federations'))

@section('subheader')

    @can('do-everything')
        <a class="px-2 py-1 bg-gray-300 hover:bg-gray-200 border border-gray-400 dark:bg-gray-900 dark:hover:bg-gray-700 text-sm rounded mr-1" href="{{ route('federations.import') }}">{{ __('common.import') }}</a>
        <a class="px-2 py-1 bg-gray-300 hover:bg-gray-200 border border-gray-400 dark:bg-gray-900 dark:hover:bg-gray-700 text-sm rounded mr-1" href="{{ route('federations.refresh') }}">{{ __('common.refresh') }}</a>
    @endcan

    <a class="px-2 py-1 bg-gray-300 hover:bg-gray-200 border border-gray-400 dark:bg-gray-900 dark:hover:bg-gray-700 text-sm rounded" href="{{ route('federations.create') }}">{{ __('common.add') }}</a>

@endsection

@section('content')

    <div class="mb-4">
        <form>
            <label class="sr-only" for="search">{{ __('common.search') }}</label>
            <input class="px-4 py-2 w-full border rounded-lg dark:bg-transparent" type="text" name="search" id="search" value="{{ request('search') }}" placeholder="{{ __('federations.searchbox') }}" autofocus>
        </form>
    </div>

    <div>
        <div class="bg-white dark:bg-transparent border rounded-lg overflow-x-auto">
            <table class="min-w-full border-b border-gray-300">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">{{ __('common.name') }}</th>
                        <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">{{ __('common.description') }}</th>
                        <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">{{ __('common.status') }}</th>
                        <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 clickable">
                    @forelse ($federations as $federation)
                        <tr class="hover:bg-blue-50 dark:hover-bg-gray-700" role="button">
                            <td class="px-6 py-3 text-sm whitespace-nowrap">
                                {{ $federation->name }}
                            </td>
                            <td class="px-6 py-3 text-sm">
                                {{ $federation->description }}
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <x-pils.approved :model="$federation"/>
                                <x-pils.status :model="$federation"/>
                                <x-pils.state :model="$federation"/>
                            </td>
                            <td class="px-6 py-3 text-sm text-right">
                                <a class="text-blue-500 hover:underline link" href="{{ route('federations.show', $federation->id) }}">{{ __('common.show') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-blue-50 dark:hover-bg-gray-700">
                            <td class="px-6 py-3 text-center font-bold" colspan="4">{{ __('federations.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $federations->withQueryString()->links() }}
        </div>
    </div>

@endsection