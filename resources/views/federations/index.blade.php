@extends('layout')
@section('title', __('common.federations'))

@section('subheader')

    @can('do-everything')
        <a class="hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 px-2 py-1 mr-1 text-sm bg-gray-300 border border-gray-400 rounded"
            href="{{ route('federations.import') }}">{{ __('common.import') }}</a>
        <a class="hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 px-2 py-1 mr-1 text-sm bg-gray-300 border border-gray-400 rounded"
            href="{{ route('federations.refresh') }}">{{ __('common.refresh') }}</a>
    @endcan

    <a class="hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 px-2 py-1 text-sm bg-gray-300 border border-gray-400 rounded"
        href="{{ route('federations.create') }}">{{ __('common.add') }}</a>

@endsection

@section('content')

    @livewire('search-federations')

@endsection
