@extends('layout')
@section('title', __('users.add'))

@section('content')

<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="mb-6 bg-white dark:bg-gray-800 sm:rounded-lg shadow overflow-hidden">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg font-semibold">{{ __('users.profile') }}</h3>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-500">
            <dl>
                <div class="px-4 py-5 bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm text-gray-500">
                        <label class="text-sm" for="name">{{ __('common.full_name') }}</label>
                    </dt>
                    <dd class="sm:col-span-2">
                        {!! $errors->first('name', '<div class="text-red-600 text-sm font-semibold float-right">:message</div>') !!}
                        <input class="text-sm dark:bg-transparent @error('name') border-red-500 border @else @if(old('name') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="name" id="name" maxlength="255" placeholder="{{ __('users.name_placeholder') }}" value="{{ old('name') }}" required>
                    </dd>
                </div>
            </dl>
            <dl>
                <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm text-gray-500">
                        <label class="text-sm" for="name">{{ __('common.uniqueid_attribute') }}</label>
                    </dt>
                    <dd class="sm:col-span-2">
                        {!! $errors->first('uniqueid', '<div class="text-red-600 text-sm font-semibold float-right">:message</div>') !!}
                        <input class="text-sm dark:bg-transparent @error('uniqueid') border-red-500 border @else @if(old('uniqueid') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="email" name="uniqueid" id="uniqueid" maxlength="255" placeholder="{{ __('users.uniqueid_placeholder') }}" value="{{ old('uniqueid') }}" required>
                    </dd>
                </div>
            </dl>
            <dl>
                <div class="px-4 py-5 bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm text-gray-500">
                        <label class="text-sm" for="name">{{ __('common.email_address') }}</label>
                    </dt>
                    <dd class="sm:col-span-2">
                        {!! $errors->first('email', '<div class="text-red-600 text-sm font-semibold float-right">:message</div>') !!}
                        <input class="text-sm dark:bg-transparent @error('email') border-red-500 border @else @if(old('email') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="email" name="email" id="email" maxlength="255" placeholder="{{ __('users.email_placeholder') }}" value="{{ old('email') }}" required>
                    </dd>
                </div>
            </dl>
        </div>
        <div class="px-4 py-4 bg-gray-100 dark:bg-transparent">
            <x-buttons.back href="{{ route('users.index') }}"/>
            <x-submit>{{ __('users.add') }}</x-submit>
        </div>
    </div>
</form>

@endsection