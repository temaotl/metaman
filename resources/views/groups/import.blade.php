@extends('layout')
@section('title', __('groups.import'))

@section('content')

    @if (count($groups))

        <form action="{{ route('groups.import') }}" method="post">
            @csrf
            <div class="bg-white dark:bg-transparent border rounded-lg overflow-x-auto">
                <table class="min-w-full border-b border-gray-300">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                &nbsp;
                            </th>
                            <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                {{ __('common.file') }}
                            </th>
                            <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                {{ __('common.name') }}
                            </th>
                            <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                {{ __('common.description') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($groups as $group)
                            <tr class="hover:bg-blue-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-3 text-sm">
                                    <input class="rounded" type="checkbox" name="groups[]" value="{{ $group }}">
                                </td>
                                <td class="px-6 py-3 text-sm">
                                    <code class="text-sm text-pink-500">
                                        {{ $group }}
                                    </code>
                                </td>
                                <td class="px-6 py-3 text-sm">
                                    <input class="rounded-lg" type="text" name="names[{{ $group }}]">
                                </td>
                                <td class="px-6 py-3 text-sm">
                                    <input class="rounded-lg" type="text" name="descriptions[{{ $group }}]">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-4 bg-gray-100 dark:bg-transparent">
                <x-buttons.submit text="{{ __('groups.import') }}"/>
            </div>
        </form>

    @else

        <strong>{{ __('groups.nothing_to_import') }}</strong>

    @endif

@endsection