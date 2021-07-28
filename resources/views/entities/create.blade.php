@extends('layout')
@section('title', __('entities.add'))

@section('content')

    <form action="{{ route('entities.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="mb-6 bg-white sm:rounded-lg shadow overflow-hidden">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg font-semibold">
                    {{ __('entities.profile') }}
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="px-4 py-5 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="url">
                                {{ __('entities.metadata_url') }}
                            </label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('url', '<div class="text-red-600 text-sm font-semibold float-right">:message</div>') !!}
                            <input class="text-sm @error('url') border-red-500 border @else @if(old('url') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 rounded-md" type="text" name="url" id="url" placeholder="{{ __('entities.url_placeholder') }}" value="{{ old('url') }}">
                        </dd>
                    </div>
                    <div class="px-4 py-5 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="file">
                                {{ __('entities.metadata_file') }}
                            </label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('file', '<div class="text-red-600 text-sm font-semibold float-right">:message</div>') !!}
                            <input class="text-sm focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 rounded-md" type="file" name="file" id="file">
                        </dd>
                    </div>
                    <div class="px-4 py-5 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="federation">
                                {{ __('entities.federation') }}
                            </label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('federation', '<div class="text-red-600 text-sm font-semibold float-right">:message</div>') !!}
                            <select class="text-sm @error('federation') border-red-500 border @else @if(old('federation') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 rounded-md" name="federation" id="federation">
                                <option value="">{{ __('entities.choose_federation') }}</option>
                                @foreach ($federations as $federation)
                                    <option value="{{ $federation->id }}" {{ old('federation') == $federation->id ? "selected" : "" }}>{{ $federation->name }}</option>
                                @endforeach
                            </select>
                        </dd>
                    </div>
                    <div class="px-4 py-5 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="explanation">
                                {{ __('common.explanation') }}
                            </label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('explanation', '<div class="text-red-600 text-sm font-semibold float-right">:message</div>') !!}
                            <textarea class="text-sm @error('explanation') border-red-500 border @else @if(old('explanation') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 rounded-md" name="explanation" id="explanation" rows="3" maxlength="255" placeholder="{{ __('entities.explanation_placeholder') }}">{{ old('explanation') }}</textarea>
                        </dd>
                    </div>
                </dl>
            </div>
            <div class="px-4 py-4 bg-gray-100">
                <x-buttons.back href="{{ route('entities.index') }}"/>
                <x-buttons.submit text="{{ __('entities.add') }}"/>
            </div>
        </div>
    </form>

@endsection