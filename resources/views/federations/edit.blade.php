@extends('layout')
@section('title', __('federations.edit', ['name' => $federation->name]))

@section('content')

    <form action="{{ route('federations.update', $federation) }}" method="post">
        @csrf
        @method('patch')
        <input type="hidden" name="action" value="update">
        <div class="mb-6 bg-white dark:bg-gray-800 sm:rounded-lg shadow overflow-hidden">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg font-semibold">{{ __('federations.profile') }}</h3>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-500">
                <dl>
                    <div class="px-4 py-5 bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="name">{{ __('common.name') }}</label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('name', '<div class="text-red-600 text-sm font-semibold float-right">:message</div>') !!}
                            <input class="text-sm dark:bg-transparent @error('name') border-red-500 border @else @if(old('name') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="name" id="name" maxlength="32" placeholder="{{ __('federations.name_placeholder') }}" value="{{ $federation->name }}" required>
                        </dd>
                    </div>
                    <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="name">{{ __('common.description') }}</label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('description', '<div class="text-red-600 text-sm font-semibold float-right">:message</div>') !!}
                            <input class="text-sm dark:bg-transparent @error('description') border-red-500 border @else @if(old('description') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="description" id="description" maxlength="255" placeholder="{{ __('federations.description_placeholder') }}" value="{{ $federation->description }}" required>
                        </dd>
                    </div>
                    <div class="px-4 py-5 bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="name">{{ __('federations.xml_id') }}</label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('xml_id', '<div class="text-red-600 text-sm font-semibold float-right">:message</div>') !!}
                            <input class="text-sm dark:bg-transparent @error('xml_id') border-red-500 border @else @if(old('xml_id') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="xml_id" id="xml_id" maxlength="32" placeholder="{{ __('federations.name_placeholder') }}" value="{{ $federation->xml_id }}" required>
                        </dd>
                    </div>
                    <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="name">{{ __('federations.xml_name') }}</label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('xml_name', '<div class="text-red-600 text-sm font-semibold float-right">:message</div>') !!}
                            <input class="text-sm dark:bg-transparent @error('xml_name') border-red-500 border @else @if(old('xml_name') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="xml_name" id="xml_name" maxlength="255" placeholder="{{ __('federations.description_placeholder') }}" value="{{ $federation->xml_name }}" required>
                        </dd>
                    </div>
                    <div class="px-4 py-5 bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="name">{{ __('federations.filters') }}</label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('filters', '<div class="text-red-600 text-sm font-semibold float-right">:message</div>') !!}
                            <input class="text-sm dark:bg-transparent @error('filters') border-red-500 border @else @if(old('filters') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="filters" id="filters" maxlength="32" placeholder="{{ __('federations.name_placeholder') }}" value="{{ $federation->filters }}" required>
                        </dd>
                    </div>
                </dl>
            </div>
            <div class="px-4 py-4 bg-gray-100 dark:bg-transparent">
                <x-buttons.back href="{{ route('federations.index') }}"/>
                <x-buttons.submit text="{{ __('federations.update') }}"/>
            </div>
        </div>
    </form>

@endsection