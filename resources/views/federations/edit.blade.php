@extends('layout')
@section('title', __('federations.edit', ['name' => $federation->name]))

@section('content')

    <form action="{{ route('federations.update', $federation) }}" method="post">
        @csrf
        @method('patch')
        <input type="hidden" name="action" value="update">
        <div class="dark:bg-gray-800 sm:rounded-lg mb-6 overflow-hidden bg-white shadow">
            <div class="sm:px-6 px-4 py-5">
                <h3 class="text-lg font-semibold">{{ __('federations.profile') }}</h3>
            </div>
            <div class="dark:border-gray-500 border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="name">{{ __('common.name') }}</label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('name', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                            <input class="text-sm dark:bg-transparent @error('name') border-red-500 border @else @if(old('name') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="name" id="name" maxlength="32" placeholder="{{ __('federations.name_placeholder') }}" value="{{ $federation->name }}" required>
                        </dd>
                    </div>
                    <div class="dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5 bg-white">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="name">{{ __('common.description') }}</label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('description', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                            <input class="text-sm dark:bg-transparent @error('description') border-red-500 border @else @if(old('description') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="description" id="description" maxlength="255" placeholder="{{ __('federations.description_placeholder') }}" value="{{ $federation->description }}" required>
                        </dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="name">{{ __('federations.xml_id') }}</label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('xml_id', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                            <input class="text-sm dark:bg-transparent @error('xml_id') border-red-500 border @else @if(old('xml_id') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="xml_id" id="xml_id" maxlength="32" placeholder="{{ __('federations.name_placeholder') }}" value="{{ $federation->xml_id }}" required>
                        </dd>
                    </div>
                    <div class="dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5 bg-white">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="name">{{ __('federations.xml_name') }}</label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('xml_name', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                            <input class="text-sm dark:bg-transparent @error('xml_name') border-red-500 border @else @if(old('xml_name') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="xml_name" id="xml_name" maxlength="255" placeholder="{{ __('federations.description_placeholder') }}" value="{{ $federation->xml_name }}" required>
                        </dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="name">{{ __('federations.filters') }}</label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('filters', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                            <input class="text-sm dark:bg-transparent @error('filters') border-red-500 border @else @if(old('filters') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="filters" id="filters" maxlength="32" placeholder="{{ __('federations.name_placeholder') }}" value="{{ $federation->filters }}" required>
                        </dd>
                    </div>
                </dl>
            </div>
            <div class="dark:bg-transparent px-4 py-4 bg-gray-100">
                <x-buttons.back href="{{ route('federations.index') }}"/>
                <x-button>{{ __('federations.update') }}</x-button>
            </div>
        </div>
    </form>

@endsection