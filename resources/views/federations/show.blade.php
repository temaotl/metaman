@extends('layout')
@section('title', __('federations.show', ['name' => $federation->name]))

@section('content')

    @include('federations.navigation')

    <h3 class="text-lg font-semibold">{{ __('federations.profile') }}</h3>
    <div class="mb-6 bg-white dark:bg-gray-800 sm:rounded-lg shadow overflow-hidden">
        <div>
            <dl>
                <div class="px-4 py-5 bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm text-gray-500">{{ __('common.name') }}</dt>
                    <dd class="sm:col-span-2">
                        <span class="pr-4">{{ $federation->name }}</span>
                        <x-pils.approved :model="$federation"/>
                        <x-pils.status :model="$federation"/>
                        <x-pils.state :model="$federation"/>
                    </dd>
                </div>
                <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm text-gray-500">
                        {{ __('common.description') }}
                    </dt>
                    <dd class="sm:col-span-2">
                        {{ $federation->description }}
                    </dd>
                </div>
                <div class="px-4 py-5 bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm text-gray-500">
                        {{ __('federations.xml_id') }}
                    </dt>
                    <dd class="sm:col-span-2">
                        {{ $federation->xml_id }}
                    </dd>
                </div>
                <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm text-gray-500">
                        {{ __('federations.xml_name') }}
                    </dt>
                    <dd class="sm:col-span-2">
                        <code class="text-sm text-pink-500">
                            {{ $federation->xml_name }}
                        </code>
                    </dd>
                </div>
                <div class="px-4 py-5 bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm text-gray-500">
                        {{ __('federations.metadata_url') }}
                    </dt>
                    <dd class="sm:col-span-2">
                        <ul>
                            @foreach (explode(', ', $federation->filters) as $filter)
                                <li>
                                    <a class="font-mono text-sm text-blue-500" href="{{ config('git.metadata_base_url') }}/{{ $filter }}" target="_blank">
                                        {{ config('git.metadata_base_url') }}/{{ $filter }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </dd>
                </div>
                @can('do-everything')
                    <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">
                            {{ __('federations.tagfile') }}
                        </dt>
                        <dd class="sm:col-span-2">
                            <code class="text-sm text-pink-500">
                                {{ $federation->tagfile }}
                            </code>
                        </dd>
                    </div>
                    <div class="px-4 py-5 bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">
                            {{ __('federations.cfgfile') }}
                        </dt>
                        <dd class="sm:col-span-2">
                            <code class="text-sm text-pink-500">
                                {{ $federation->cfgfile }}
                            </code>
                        </dd>
                    </div>
                    <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">
                            {{ __('federations.filters') }}
                        </dt>
                        <dd class="sm:col-span-2">
                            {{ $federation->filters }}
                        </dd>
                    </div>
                @endcan
                @unless ($federation->approved)
                    <div class="px-4 py-5 bg-green-50 dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 font-bold">
                        <dt class="text-sm text-gray-500">
                            {{ __('common.explanation') }}
                        </dt>
                        <dd class="sm:col-span-2">
                            {{ $federation->explanation }}
                        </dd>
                    </div>
                @endunless
            </dl>
        </div>
        <div class="px-6 py-3 bg-gray-100">
            <x-buttons.back href="{{ route('federations.index') }}"/>
    
            @can('update', $federation)
                @unless ($federation->trashed())
                    <a class="px-4 py-2 bg-yellow-300 text-yellow-600 hover:bg-yellow-200 rounded shadow" href="{{ route('federations.edit', $federation) }}">{{ __('common.edit') }}</a>
                @endunless
            @endcan

            <x-forms.cancel route="federations" :model="$federation"/>
            <x-forms.approve route="federations" :model="$federation"/>
            <x-forms.change-status route="federations" :model="$federation"/>
            <x-forms.change-state route="federations" :model="$federation"/>
            <x-forms.destroy route="federations" :model="$federation"/>

        </div>
    </div>

@endsection