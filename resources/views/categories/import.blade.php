@extends('layouts.defaultImport')
@section('title', __('categories.import'))
@section('form_action',route('categories.import'))


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
    @foreach ($categories as $category)
        <tr x-data class="hover:bg-blue-50 dark:hover:bg-gray-700"
            @click="checkbox = $el.querySelector('input[type=checkbox]'); checkbox.checked = !checkbox.checked">
            <td class="px-6 py-3 text-sm">
                <input @click.stop class="rounded" type="checkbox" name="categories[]"
                       value="{{ $category }}"  x-bind:checked="selectAll" >
            </td>

            <td class="px-6 py-3 text-sm">
                <input class="rounded" type="text" name="names[{{ $category }}]">
            </td>

            <td class="px-6 py-3 text-sm">
                <input class="rounded" type="text" name="descriptions[{{ $category }}]">
            </td>

            <td class="px-6 py-3 text-sm">
                <code class="text-sm text-pink-500">
                    {{ $category }}
                </code>
            </td>


        </tr>
    @endforeach
@endsection

@section('submit_button')
    <x-button>{{ __('categories.import') }}</x-button>
@endsection




