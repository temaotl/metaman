@extends('layouts.defaultCreate')
@section('title', __('federations.add'))
@section('form_action', route('federations.store'))
@section('back_button', route('federations.index'))
@section('submit_button',__('federations.add'))
@section('profile',__('federations.profile'))


@section('specific_fields')
    <x-forms.create.create-form-field>
        <x-slot name="field">
            name
        </x-slot>

        <x-slot name="name">
            {{ __('common.name') }}
        </x-slot>

        <x-slot name="content">
            {{--            <input
                            class="text-sm dark:bg-transparent @error('name') border-red-500 border @else @if (old('name') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md"
                            type="text" name="name" id="name" maxlength="32"
                            placeholder="{{ __('federations.name_placeholder') }}" value="{{ old('name') }}"
                            required>--}}

            <input @class([
    'text-sm dark:bg-transparent focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md',
    'border-red-500 border' => $errors->has('name'),
    'border-green-500' => !$errors->has('name') && old('name') !== null,
                           ])
                   type="text" name="name" id="name" maxlength="32"
                   placeholder="{{ __('federations.name_placeholder') }}" value="{{ old('name') }}"
                   required>
        </x-slot>

    </x-forms.create.create-form-field>

    <x-forms.create.create-form-field>

        <x-slot name="field">
            name
        </x-slot>

        <x-slot name="name">
            {{ __('common.description') }}
        </x-slot>

        <x-slot name="content">

            {{--            <input
                            class="text-sm dark:bg-transparent @error('description') border-red-500 border @else @if (old('description') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md"
                            type="text" name="description" id="description" maxlength="255"
                            placeholder="{{ __('federations.description_placeholder') }}"
                            value="{{ old('description') }}" required>--}}

            <input @class([
                'text-sm dark:bg-transparent focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md',
                'border-red-500 border' => $errors->has('description'),
                'border-green-500' => !$errors->has('description') && old('description') !== null,
                           ])
                   type="text" name="description" id="description" maxlength="255"
                   placeholder="{{ __('federations.description_placeholder') }}" value="{{ old('description') }}" required>


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

            {{--            <textarea class="text-sm dark:bg-transparent @error('explanation') border-red-500 border @else @if (old('explanation') !== null) border-green-500 @endif @enderror focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md"
                                  name="explanation" id="explanation" rows="3" maxlength="255"
                                  placeholder="{{ __('federations.explanation_placeholder') }}"
                                  required>{{ old('explanation') }}</textarea>--}}

            <textarea @class([
    'text-sm dark:bg-transparent focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md',
    'border-red-500 border' => $errors->has('explanation'),
    'border-green-500' => !$errors->has('explanation') && old('explanation') !== null,
    ]) name="explanation" id="explanation" rows="3" maxlength="255"
                      placeholder="{{ __('federations.explanation_placeholder') }}"
                      required>{{ old('explanation') }}</textarea>


        </x-slot>

    </x-forms.create.create-form-field>


@endsection

