@extends('layouts.defaultImport')
@section('title', __('entities.import'))
@section('form_action',route('entities.import'))



@section('specific_head_fields')

    <th
        class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
        {{ __('common.entityid') }}
    </th>
    <th
        class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
        {{ __('common.federations') }}
    </th>

@endsection

@section('specific_fields')

    @forelse ($entities as $entity)
        <tr class="hover:bg-blue-50 dark:hover:bg-gray-700" role="button">
            <td class="px-6 py-3 text-sm">
                <input class="rounded" type="checkbox" name="entities[]" value="{{ $entity['file'] }}"
                       x-bind:checked="selectAll">
            </td>
            <td class="px-6 py-3 text-sm">
                {{ $entity["name_$locale"] }}
            </td>
            <td class="px-6 py-3 text-sm">
                {{ $entity['entityid'] }}
            </td>
            <td class="px-6 py-3 text-sm">
                @if (isset($entity['federations']))
                    @foreach ($entity['federations'] as $federation)
                        {{ $federation }}@if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                @else
                @endif
            </td>
        </tr>
    @empty

    @endforelse

@endsection

@section('submit_button')
    <x-button>{{ __('entities.import') }}</x-button>
@endsection




