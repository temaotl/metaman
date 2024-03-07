@extends('layouts.defaultImport')
@section('title', __('federations.import'))
@section('form_action',route('federations.import'))



@section('specific_head_fields')

    <th
        class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
        {{ __('common.description') }}
    </th>
    <th
        class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
        {{ __('federations.xml_id') }}
    </th>
    <th
        class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
        {{ __('federations.xml_name') }}
    </th>
    <th
        class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
        {{ __('federations.filters') }}
    </th>
@endsection

@section('specific_fields')
    @foreach ($federations as $federation)
        <tr x-data class="hover:bg-blue-50 dark:hover:bg-gray-700" role="button"
            @click="checkbox = $el.querySelector('input[type=checkbox]'); checkbox.checked = !checkbox.checked">
            <td class="px-6 py-3 text-sm">
                <input @click.stop class="rounded" type="checkbox" name="federations[]"
                       value="{{ $federation['cfgfile'] }}" x-bind:checked="selectAll">
            </td>
            <td class="px-6 py-3 text-sm">
                <input class="rounded" type="text" name="names[{{ $federation['cfgfile'] }}]">
            </td>
            <td class="px-6 py-3 text-sm">
                <input class="rounded" type="text" name="descriptions[{{ $federation['cfgfile'] }}]">
            </td>
            <td class="px-6 py-3 text-sm">
                {{ $federation['xml_id'] }}
            </td>
            <td class="px-6 py-3 text-sm">
                {{ $federation['name'] }}
            </td>
            <td class="px-6 py-3 text-sm">
                {{ $federation['filters'] }}
            </td>
        </tr>
    @endforeach
@endsection

@section('submit_button')
    <x-button>{{ __('federations.import') }}</x-button>
@endsection

