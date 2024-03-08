@extends('layouts.defaultImport')
@section('title', __('groups.import'))
@section('form_action',route('groups.import'))


@section('specific_head_fields')
    <th
        class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
        {{ __('common.description') }}
    </th>

    <th
        class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
        {{ __('common.file') }}
    </th>

@endsection

@section('specific_fields')

    @foreach ($groups as $group)
        <tr x-data class="hover:bg-blue-50 dark:hover:bg-gray-700"
            @click="checkbox = $el.querySelector('input[type=checkbox]'); checkbox.checked = !checkbox.checked">
            <td class="px-6 py-3 text-sm">
                <input @click.stop class="rounded" type="checkbox" name="groups[]"
                       value="{{ $group }}" x-bind:checked="selectAll" >
            </td>

            <td class="px-6 py-3 text-sm">
                <input class="rounded-lg" type="text" name="names[{{ $group }}]">
            </td>

            <td class="px-6 py-3 text-sm">
                <input class="rounded-lg" type="text" name="descriptions[{{ $group }}]">
            </td>

            <td class="px-6 py-3 text-sm">
                <code class="text-sm text-pink-500">
                    {{ $group }}
                </code>
            </td>

        </tr>
    @endforeach

@endsection

@section('submit_button')
    <x-button>{{ __('groups.import') }}</x-button>
@endsection






