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

    $field =[
        ''
    ];


@endphp


@section('specific_head_fields')

    @foreach ($headers as $header)
        <x-table-head-import>
            {{ __($header) }}
        </x-table-head-import>
    @endforeach

@endsection

@section('specific_fields')
    @foreach ($federations as $federation)
        <tr x-data class="hover:bg-blue-50 dark:hover:bg-gray-700" role="button"
            @click="checkbox = $el.querySelector('input[type=checkbox]'); checkbox.checked = !checkbox.checked">
            <td class="px-6 py-3 text-sm">
                <input @click.stop class="rounded" type="checkbox" name="federations[]"
                       value="{{ $federation['cfgfile'] }}" x-bind:checked="selectAll">
            </td>

            <x-table-body-import>
                <input class="rounded" type="text" name="names[{{ $federation['cfgfile'] }}]">
            </x-table-body-import>

            <x-table-body-import>
                <input class="rounded" type="text" name="descriptions[{{ $federation['cfgfile'] }}]">
            </x-table-body-import>

            <x-table-body-import>
                {{ $federation['xml_id'] }}
            </x-table-body-import>

            <x-table-body-import>
                {{ $federation['name'] }}
            </x-table-body-import>

            <x-table-body-import>
                {{ $federation['filters'] }}
            </x-table-body-import>

        </tr>
    @endforeach
@endsection

@section('submit_button')
    <x-button>{{ __('federations.import') }}</x-button>
@endsection

