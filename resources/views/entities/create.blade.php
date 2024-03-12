@extends('layouts.defaultCreate')
@section('title', __('entities.add'))
@section('form_action',route('entities.store'))
@section('back_button',route('entities.index'))
@section('submit_button',__('entities.add'))
@section('form_enctype',"multipart/form-data")
@section('profile',__('entities.profile'))

@section('specific_fields')
    <x-forms.create.create-form-field>

        <x-slot name="field">
            file
        </x-slot>

        <x-slot name="name">
            {{ __('entities.metadata_file') }}
        </x-slot>

        <x-slot name="content">
            <input
                class="focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 rounded-md shadow-sm"
                type="file" name="file" id="file">
        </x-slot>

    </x-forms.create.create-form-field>

    <x-forms.create.create-form-field>

        <x-slot name="field">
            metadata
        </x-slot>

        <x-slot name="name">
            {{ __('common.metadata') }}
        </x-slot>

        <x-slot name="content">

{{--            <textarea class="text-sm @error('metadata') border @else @if (old('metadata') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm rounded-md"
                      name="metadata" id="metadata" rows="10"
                      placeholder="{{ __('entities.metadata_placeholder') }}">{{ old('metadata') }}</textarea>--}}
            <textarea @class([
                         'text-sm focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm rounded-md',
                         'border' => $errors->has('metadata'),
                         'border-green-500' => old('metadata') !== null && !$errors->has('metadata'),
                         ])
                      name="metadata" id="metadata" rows="10"
                      placeholder="{{ __('entities.metadata_placeholder') }}">{{ old('metadata') }}
            </textarea>

        </x-slot>

    </x-forms.create.create-form-field>

    <x-forms.create.create-form-field>

        <x-slot name="field">
            federation
        </x-slot>

        <x-slot name="name">
            {{ __('entities.federation') }}
        </x-slot>

        <x-slot name="content">

{{--            <select
                class="text-sm @error('federation') border-red-500 border @else @if (old('federation') !== null) @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm rounded-md"
                name="federation" id="federation" required>
                <option value="">{{ __('entities.choose_federation') }}</option>
                @foreach ($federations as $federation)
                    <option value="{{ $federation->id }}"
                        {{ old('federation') == $federation->id ? 'selected' : '' }}>
                        {{ $federation->name }}</option>
                @endforeach
            </select>--}}

            <select @class([
                            'text-sm focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm rounded-md',
                            'border-red-500 border' => $errors->has('federation'),
                            ])
                    name="federation" id="federation" required>
                <option value="">{{ __('entities.choose_federation') }}</option>
                @foreach ($federations as $federation)
                    <option value="{{ $federation->id }}" {{ old('federation') == $federation->id ? 'selected' : '' }}>
                        {{ $federation->name }}
                    </option>
                @endforeach
            </select>


        </x-slot>

    </x-forms.create.create-form-field>


    <x-forms.create.create-form-field>

        <x-slot name="field">
            explanation
        </x-slot>

        <x-slot name="name">
            {{ __('common.explanation') }}
        </x-slot>

        <x-slot name="content">

{{--            <textarea class="text-sm @error('explanation') border-red-500 border @else @if (old('explanation') !== null) @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm rounded-md"
                      name="explanation" id="explanation" rows="3" maxlength="255"
                      placeholder="{{ __('entities.explanation_placeholder') }}"
                      required>{{ old('explanation') }}</textarea>--}}

                        <textarea @class([
                                     'text-sm focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm rounded-md',
                                     'border-red-500 border' => $errors->has('explanation'),
                                     'border-green-500' => !$errors->has('explanation') && old('explanation') !== null,
                                     ])
                                  name="explanation" id="explanation" rows="3" maxlength="255"
                                  placeholder="{{ __('entities.explanation_placeholder') }}"
                                  required>{{ old('explanation') }}
                        </textarea>



        </x-slot>

    </x-forms.create.create-form-field>
@endsection


