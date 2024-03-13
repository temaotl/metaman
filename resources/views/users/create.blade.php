@extends('layouts.defaultCreate')
@section('title', __('users.add'))
@section('form_action',route('users.store'))
@section('back_button',route('users.index'))
@section('submit_button',__('users.add'))
@section('profile', __('users.profile'))


@section('specific_fields')

    <x-forms.create.create-form-field
        name="common.full_name"
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
            placeholder="{{ __('users.name_placeholder') }}"
            value="{{ old('name') }}"
            required
        />

    </x-forms.create.create-form-field>

    <x-forms.create.create-form-field
        name="common.uniqueid_attribute"
        label="uniqueid"
    >
        <input
            @class([
                'text-sm dark:bg-transparent focus:ring-blue-500',
                'focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md',
                'border-red-500 border' =>  $errors->has('uniqueid'),
                'border-green-500' =>  !$errors->has('uniqueid') && old('uniqueid') !== null
                ])
            type="email" name="uniqueid" id="uniqueid" maxlength="255"
            placeholder="{{ __('users.uniqueid_placeholder') }}" value="{{ old('uniqueid') }}"
            required
        />

    </x-forms.create.create-form-field>


    <x-forms.create.create-form-field
        name="common.email_address"
        label="email"
    >
        <input
            @class([
                'text-sm dark:bg-transparent focus:ring-blue-500',
                'focus:border-blue-500 block w-full shadow-sm border-gray-300 dark:border-gray-700 rounded-md',
                'border-red-500 border' =>  $errors->has('email'),
                'border-green-500' =>  !$errors->has('email') && old('email') !== null
                ])
            type="email" name="email" id="email" maxlength="255"
            placeholder="{{ __('users.email_placeholder') }}" value="{{ old('email') }}" required
        />

    </x-forms.create.create-form-field>

@endsection


