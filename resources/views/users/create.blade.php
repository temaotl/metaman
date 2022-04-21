@extends('layout')
@section('title', __('users.add'))

@section('content')

<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="dark:bg-gray-800 sm:rounded-lg mb-6 overflow-hidden bg-white shadow">
        <div class="sm:px-6 px-4 py-5">
            <h3 class="text-lg font-semibold">{{ __('users.profile') }}</h3>
        </div>
        <div class="dark:border-gray-500 border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5">
                    <dt class="text-sm text-gray-500">
                        <label class="text-sm" for="name">{{ __('common.full_name') }}</label>
                    </dt>
                    <dd class="sm:col-span-2">
                        {!! $errors->first('name', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                        <input class="text-sm dark:bg-transparent @error('name') border-red-500 border @else @if(old('name') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="text" name="name" id="name" maxlength="255" placeholder="{{ __('users.name_placeholder') }}" value="{{ old('name') }}" required>
                    </dd>
                </div>
            </dl>
            <dl>
                <div class="dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5 bg-white">
                    <dt class="text-sm text-gray-500">
                        <label class="text-sm" for="name">{{ __('common.uniqueid_attribute') }}</label>
                    </dt>
                    <dd class="sm:col-span-2">
                        {!! $errors->first('uniqueid', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                        <input class="text-sm dark:bg-transparent @error('uniqueid') border-red-500 border @else @if(old('uniqueid') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="email" name="uniqueid" id="uniqueid" maxlength="255" placeholder="{{ __('users.uniqueid_placeholder') }}" value="{{ old('uniqueid') }}" required>
                    </dd>
                </div>
            </dl>
            <dl>
                <div class="bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5">
                    <dt class="text-sm text-gray-500">
                        <label class="text-sm" for="name">{{ __('common.email_address') }}</label>
                    </dt>
                    <dd class="sm:col-span-2">
                        {!! $errors->first('email', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                        <input class="text-sm dark:bg-transparent @error('email') border-red-500 border @else @if(old('email') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md" type="email" name="email" id="email" maxlength="255" placeholder="{{ __('users.email_placeholder') }}" value="{{ old('email') }}" required>
                    </dd>
                </div>
            </dl>
        </div>
        <div class="dark:bg-transparent px-4 py-4 bg-gray-100">
            <x-buttons.back href="{{ route('users.index') }}"/>
            <x-button>{{ __('users.add') }}</x-button>
        </div>
    </div>
</form>

@endsection