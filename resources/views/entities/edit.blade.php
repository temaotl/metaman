@extends('layout')
@section('title', __('entities.edit', ['name' => $entity->name_en]))

@section('content')
    
    <form action="{{ route('entities.update', $entity) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('patch')
        <input type="hidden" name="action" value="update">
        <div class="sm:rounded-lg mb-6 overflow-hidden bg-white shadow">
            <div class="sm:px-6 px-4 py-5">
                <h3 class="text-lg font-semibold">
                    {{ __('entities.profile') }}
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="odd:bg-gray-50 even:bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="file">
                                {{ __('entities.metadata_file') }}
                            </label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('file', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                            <input class="focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 rounded-md shadow-sm" type="file" name="file" id="file">
                        </dd>
                    </div>
                    <div class="odd:bg-gray-50 even:bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5">
                        <dt class="text-sm text-gray-500">
                            <label class="text-sm" for="metadata">
                                {{ __('common.metadata') }}
                            </label>
                        </dt>
                        <dd class="sm:col-span-2">
                            {!! $errors->first('metadata', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                            <textarea class="text-sm @error('metadata') border-red-500 border @else @if(old('metadata') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 rounded-md" name="metadata" id="metadata" rows="10" placeholder="{{ __('entities.metadata_placeholder') }}">{{ old('metadata') }}</textarea>
                        </dd>
                    </div>
                </dl>
            </div>
            <div class="px-4 py-4 bg-gray-100">
                <x-buttons.back href="{{ URL::previous() }}"/>
                <x-button>{{ __('entities.update') }}</x-button>
            </div>
        </div>
    </form>

@endsection