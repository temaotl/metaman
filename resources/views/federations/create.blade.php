@extends('layouts.defaultCreate')
@section('title', __('federations.add'))
@section('form_action', route('federations.store'))
@section('submit_button',__('federations.add'))
@section('profile',__('federations.profile'))


@section('specific_fields')


    <x-forms.section.form-body-section
        name="common.name"
        label="name"
    >
        <x-forms.element.input err="name">
            type="text" name="name" id="name" maxlength="32"
            placeholder="{{ __('federations.name_placeholder') }}" value="{{ old('name') }}"
            required
        </x-forms.element.input>

    </x-forms.section.form-body-section>

    <x-forms.section.form-body-section
        name="common.description"
        label="name"
    >
        <x-forms.element.input err="description">
            type="text" name="description" id="description" maxlength="255"
            placeholder="{{ __('federations.description_placeholder') }}" value="{{ old('description') }}" required
        </x-forms.element.input>

    </x-forms.section.form-body-section>

    <x-forms.section.form-body-section
        name="common.explanation"
        label="explanation"
    >

        <textarea @class([
    'text-sm dark:bg-transparent focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md',
    'border-red-500 border' => $errors->has('explanation'),
    'border-green-500' => !$errors->has('explanation') && old('explanation') !== null,
    ]) name="explanation" id="explanation" rows="3" maxlength="255"
                  placeholder="{{ __('federations.explanation_placeholder') }}"
                  required>{{ old('explanation') }}</textarea>

    </x-forms.section.form-body-section>

@endsection

