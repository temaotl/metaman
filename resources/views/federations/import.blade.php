@extends('layout')
@section('title', __('federations.import'))

@section('content')

    @if (count($federations))

        <form action="{{ route('federations.import') }}" method="post">
        @csrf
            <div class="bg-white dark:bg-transparent border rounded-lg overflow-x-auto">
                <table class="min-w-full border-b border-gray-300">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                &nbsp;
                            </th>
                            <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                {{ __('common.name') }}
                            </th>
                            <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                {{ __('common.description') }}
                            </th>
                            <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                {{ __('federations.xml_id') }}
                            </th>
                            <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                {{ __('federations.xml_name') }}
                            </th>
                            <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                {{ __('federations.filters') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($federations as $federation)
                            <tr class="hover:bg-blue-50 dark:hover:bg-gray-700" role="button">
                                <td class="px-6 py-3 text-sm">
                                    <input class="rounded" type="checkbox" name="federations[]" value="{{ $federation['cfgfile'] }}">
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
                        @empty
                            
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-4 bg-gray-100 dark:bg-transparent">
                <x-buttons.submit text="{{ __('federations.import') }}"/>
            </div>
        </form>
    @else

        <strong>{{ __('federations.nothing_to_import') }}</strong>

    @endif

@endsection