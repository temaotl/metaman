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
        <input
            @class([
    'text-sm','dark:bg-transparent','focus:ring-blue-500','focus:border-blue-500', 'block','w-full',
    'shadow-sm','border-gray-300','dark:border-gray-700','rounded-md',
    'border-red-500 border' => $errors->has('name'),
    'border-green-500' => !$errors->has('name') && old('name') !== null,
              ])
            type="text" name="name" id="name" maxlength="32"
            placeholder="{{ __('groups.name_placeholder') }}" value="{{ old('name') }}" required
        />

    </x-forms.create.create-form-field>

    <x-forms.create.create-form-field
        name="common.description"
        label="description"
    >
        <input
            @class([
                'text-sm dark:bg-transparent focus:ring-blue-500',
                'focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md',
                'border-red-500 border' =>  $errors->has('description'),
                'border-green-500' =>  !$errors->has('description') && old('description') !== null
                ])
            type="text" name="description" id="description" maxlength="255"
            placeholder="{{ __('categories.description_placeholder') }}"
            value="{{ old('description') }}" required

        />

    </x-forms.create.create-form-field>

@endsection


