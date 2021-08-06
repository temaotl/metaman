@extends('layout')
@section('title', __('entities.import'))

@section('content')

    @if (count($missing_federations))

        <p>
            <strong>Missing federations!</strong>
        </p>
    
    @else

        @if (count($entities))

            <form action="{{ route('entities.import') }}" method="post">
                @csrf
                <div class="bg-white dark:bg-transparent border rounded-lg overflow-x-auto">
                    <table class="min-w-full border-b border-gray-300">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                    <input class="rounded" type="checkbox" id="checkAll">
                                </th>
                                <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                    {{ __('common.name') }}
                                </th>
                                <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                    {{ __('common.entityid') }}
                                </th>
                                <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">
                                    {{ __('common.federations') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($entities as $entity)
                                <tr class="hover:bg-blue-50 dark:hover:bg-gray-700" role="button">
                                    <td class="px-6 py-3 text-sm">
                                        <input class="rounded" type="checkbox" name="entities[]" value="{{ $entity['file'] }}">
                                    </td>
                                    <td class="px-6 py-3 text-sm">
                                        {{ $entity['name_en'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm">
                                        {{ $entity['entityid'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm">
                                        @if (isset($entity['federations']))
                                            @foreach ($entity['federations'] as $federation)
                                                {{ $federation }}@if (! $loop->last), @endif
                                            @endforeach
                                        @else
                                            
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-4 bg-gray-100 dark:bg-transparent">
                    <x-button>{{ __('entities.import') }}</x-button>
                </div>
            </form>

        @else

            <p>
                <strong>Nothing to import!</strong>
            </p>
        
        @endif

    @endif

@endsection