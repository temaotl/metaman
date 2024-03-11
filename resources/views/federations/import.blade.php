@extends('layouts.defaultImport')
@section('title', __('federations.import'))
@section('form_action',route('federations.import'))


@php
    $headers = [
        'common.description',
        'federations.xml_id',
        'federations.xml_name',
        'federations.filters',
    ];
@endphp


@section('specific_head_fields')

    @foreach ($headers as $header)

        <x-form-table.head-cell>
            {{ __($header) }}
        </x-form-table.head-cell>

    @endforeach

@endsection

@section('specific_fields')
    @foreach ($federations as $federation)
        <x-form-table.row>

            <x-form-table.body-cell>
                <input @click.stop class="rounded" type="checkbox" name="federations[]"
                       value="{{ $federation['cfgfile'] }}" x-bind:checked="selectAll">
            </x-form-table.body-cell>

            <x-form-table.body-cell>
                <input class="rounded" type="text" name="names[{{ $federation['cfgfile'] }}]">
            </x-form-table.body-cell>

            <x-form-table.body-cell>
                <input class="rounded" type="text" name="descriptions[{{ $federation['cfgfile'] }}]">
            </x-form-table.body-cell>

            <x-form-table.body-cell>
                {{ $federation['xml_id'] }}
            </x-form-table.body-cell>

            <x-form-table.body-cell>
                {{ $federation['name'] }}
            </x-form-table.body-cell>

            <x-form-table.body-cell>
                {{ $federation['filters'] }}
            </x-form-table.body-cell>


        </x-form-table.row>





    @endforeach
@endsection

@section('submit_button')
    <x-button>{{ __('federations.import') }}</x-button>
@endsection




