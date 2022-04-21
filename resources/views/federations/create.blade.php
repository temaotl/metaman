@extends('layout')
@section('title', __('federations.add'))

@section('content')

<form action="{{ route('federations.store') }}" method="post">
    @csrf
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
                        <input class="text-sm dark:bg-transparent @error('name') border-red-500 border @else @if(old('name') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="name" id="name" maxlength="32" placeholder="{{ __('federations.name_placeholder') }}" value="{{ old('name') }}" required>
                    </dd>
                </div>
                <div class="dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5 bg-white">
                    <dt class="text-sm text-gray-500">
                        <label class="text-sm" for="name">{{ __('common.description') }}</label>
                    </dt>
                    <dd class="sm:col-span-2">
                        {!! $errors->first('description', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                        <input class="text-sm dark:bg-transparent @error('description') border-red-500 border @else @if(old('description') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="description" id="description" maxlength="255" placeholder="{{ __('federations.description_placeholder') }}" value="{{ old('description') }}" required>
                    </dd>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5">
                    <dt class="text-sm text-gray-500">
                        <label class="text-sm" for="explanation">{{ __('common.explanation') }}</label>
                    </dt>
                    <dd class="sm:col-span-2">
                        {!! $errors->first('explanation', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                        <textarea class="text-sm dark:bg-transparent @error('explanation') border-red-500 border @else @if(old('explanation') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" name="explanation" id="explanation" rows="3" maxlength="255" placeholder="{{ __('federations.explanation_placeholder') }}" required>{{ old('explanation') }}</textarea>
                    </dd>
                </div>
            </dl>
        </div>
        <div class="dark:bg-transparent px-4 py-4 bg-gray-100">
            <x-buttons.back href="{{ route('federations.index') }}"/>
            <x-button>{{ __('federations.add') }}</x-button>
        </div>
    </div>
</form>

@endsection