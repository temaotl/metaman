@extends('layout')
@section('title', __('groups.edit', ['name' => $group->name]))

@section('content')

    <form action="{{ route('groups.update', $group) }}" method="post">
        @csrf
        @method('patch')
        <div class="sm:rounded-lg mb-6 overflow-hidden bg-white shadow">
            <div class="sm:px-6 px-4 py-5">
                <h3 class="text-lg font-semibold">
                    {{ __('groups.profile') }}
                </h3>
            </div>
            <div class="border-t border-gray-300">
                <dl>
                    <div class="bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="name">
                                {{ __('common.name') }}
                            </label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('name', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                            <input
                                class="text-sm dark:bg-transparent @error('name') border-red-500 border @else @if (old('name') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md"
                                type="text" name="name" id="name" maxlength="32"
                                placeholder="{{ __('groups.name_placeholder') }}" value="{{ $group->name }}" required>
                        </dd>
                    </div>
                    <div class="dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5 bg-white">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="name">{{ __('common.description') }}</label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('description', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                            <input
                                class="text-sm dark:bg-transparent @error('description') border-red-500 border @else @if (old('description') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md"
                                type="text" name="description" id="description" maxlength="255"
                                placeholder="{{ __('groups.description_placeholder') }}"
                                value="{{ $group->description }}" required>
                        </dd>
                    </div>
                    <div class="bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="name">
                                {{ __('common.file') }}
                            </label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('tagfile', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                            <input
                                class="text-sm dark:bg-transparent @error('tagfile') border-red-500 border @else @if (old('tagfile') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md"
                                type="text" name="tagfile" id="tagfile" maxlength="36"
                                placeholder="{{ __('groups.file_placeholder') }}" value="{{ $group->tagfile }}"
                                required>
                        </dd>
                    </div>
                </dl>
            </div>
            <div class="px-4 py-4 bg-gray-100">
                <x-buttons.back href="{{ route('groups.index') }}" />
                <x-button>{{ __('groups.update') }}</x-button>
            </div>
    </form>

@endsection
