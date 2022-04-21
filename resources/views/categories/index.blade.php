@extends('layout')
@section('title', __('common.categories'))

@section('subheader')

    @can('do-everything')
        <a class="hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 px-2 py-1 mr-1 text-sm bg-gray-300 border border-gray-400 rounded"
            href="{{ route('categories.import') }}">{{ __('common.import') }}</a>
        <a class="hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 px-2 py-1 mr-1 text-sm bg-gray-300 border border-gray-400 rounded"
            href="{{ route('categories.refresh') }}">{{ __('common.refresh') }}</a>
    @endcan

    <a class="hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 px-2 py-1 text-sm bg-gray-300 border border-gray-400 rounded"
        href="{{ route('categories.create') }}">{{ __('common.add') }}</a>

@endsection

@section('content')

    <div class="mb-4">
        <form>
            <label class="sr-only" for="search">{{ __('common.search') }}</label>
            <input class="dark:bg-transparent w-full px-4 py-2 border rounded-lg" type="text" name="search" id="search"
                value="{{ request('search') }}" placeholder="{{ __('categories.searchbox') }}" autofocus>
        </form>
    </div>

    <div>
        <div class="dark:bg-transparent overflow-x-auto bg-white border rounded-lg">
            <table class="min-w-full border-b border-gray-300">
                <thead>
                    <tr>
                        <th
                            class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                            {{ __('common.name') }}</th>
                        <th
                            class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                            {{ __('common.description') }}</th>
                        <th
                            class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                            {{ __('common.entities') }}</th>
                        <th
                            class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                            &nbsp;</th>
                    </tr>
                </thead>
                <tbody class="clickable divide-y divide-indigo-200">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-blue-50 dark:hover:bg-gray-700" role="button">
                            <td class="px-6 py-3 text-sm">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-3 text-sm">
                                {{ $category->description }}
                            </td>
                            <td class="px-6 py-3 text-sm">
                                {{ $category->entities->count() }}
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <a class="hover:underline link text-blue-500"
                                    href="{{ route('categories.show', $category) }}">{{ __('common.show') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-blue-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-3 font-bold text-center" colspan="4">
                                {{ __('categories.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $categories->withQueryString()->links() }}
        </div>
    </div>
@endsection
