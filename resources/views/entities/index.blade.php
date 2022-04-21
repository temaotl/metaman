@extends('layout')
@section('title', __('common.entities'))

@section('subheader')

    @can('do-everything')
        <a class="hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 px-2 py-1 mr-1 text-sm bg-gray-300 border border-gray-400 rounded"
            href="{{ route('entities.import') }}">{{ __('common.import') }}</a>
        <a class="hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 px-2 py-1 mr-1 text-sm bg-gray-300 border border-gray-400 rounded"
            href="{{ route('entities.refresh') }}">{{ __('common.refresh') }}</a>
    @endcan

    <a class="hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 px-2 py-1 text-sm bg-gray-300 border border-gray-400 rounded"
        href="{{ route('entities.create') }}">{{ __('common.add') }}</a>

@endsection

@section('content')

    <div class="mb-4">
        <form>
            <label class="sr-only" for="search">{{ __('common.search') }}</label>
            <input class="dark:bg-transparent w-full px-4 py-2 border rounded-lg" type="text" name="search" id="search"
                value="{{ request('search') }}" placeholder="{{ __('entities.searchbox') }}" autofocus>
        </form>
    </div>

    <div>
        <div class="dark:bg-transparent overflow-x-auto bg-white border rounded-lg">
            <table class="min-w-full border-b border-gray-300">
                <thead>
                    <tr>
                        <th
                            class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                            {{ __('common.name') }}
                        </th>
                        <th
                            class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                            {{ __('common.description') }}
                        </th>
                        <th
                            class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                            {{ __('common.status') }}
                        </th>
                        <th
                            class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody class="clickable divide-y divide-gray-200">
                    @forelse ($entities as $entity)
                        <tr class="hover:bg-blue-50 dark:hover-bg-gray-700" role="button">
                            <td class="whitespace-nowrap px-6 py-3 text-sm">
                                {{ $entity->name_en }}
                                <div class="text-gray-500">
                                    {{ $entity->entityid }}
                                </div>
                            </td>
                            <td class="px-6 py-3 text-sm">
                                {{ $entity->description_en ?: __('entities.no_description') }}
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <x-pils.approved :model="$entity" />
                                <x-pils.status :model="$entity" />
                                <x-pils.state :model="$entity" />
                            </td>
                            <td class="px-6 py-3 text-sm text-right">
                                <a class="hover:underline link text-blue-500"
                                    href="{{ route('entities.show', $entity->id) }}">{{ __('common.show') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-blue-50 dark:hover-bg-gray-700">
                            <td class="px-6 py-3 font-bold text-center" colspan="4">{{ __('entities.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $entities->withQueryString()->links() }}
        </div>
    </div>

@endsection
