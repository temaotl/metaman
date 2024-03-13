@extends('layouts.defaultCreate')
@section('title', __('groups.add'))
@section('form_action',route('groups.store'))
@section('back_button',route('groups.index'))
@section('submit_button',__('groups.add'))
@section('profile', __('groups.profile'))


@section('specific_fields')

    <x-forms.create.create-form-field
        name="common.name"
        label="name"
    >

        <x-forms.create.create-form-input err="name">
            type="text" name="name" id="name" maxlength="32"
            placeholder="{{ __('groups.name_placeholder') }}" value="{{ old('name') }}" required
        </x-forms.create.create-form-input>

    </x-forms.create.create-form-field>

    <x-forms.create.create-form-field
        name="common.description"
        label="description"
    >
        <x-forms.create.create-form-input err="description">
            type="text" name="description" id="description" maxlength="255"
            placeholder="{{ __('categories.description_placeholder') }}"
            value="{{ old('description') }}" required
        </x-forms.create.create-form-input>

    </x-forms.create.create-form-field>

@endsection


