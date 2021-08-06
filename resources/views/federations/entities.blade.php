@extends('layout')
@section('title', __('federations.show', ['name' => $federation->name]))

@section('content')

    @include('federations.navigation')

    @can('update', $federation)

        <div class="mb-4">
            <h3 class="text-lg font-semibold">
                {{ __('common.delete_members') }}
            </h3>
            <form id="delete_members" action="{{ route('federations.update', $federation) }}" method="post">
                @csrf
                @method('patch')
                <input type="hidden" name="action" value="delete_entities">
                <div class="bg-white border rounded-lg overflow-x-auto">
                    <table class="min-w-full border-b border-gray-300">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b bg-gray-100 text-left text-xs tracking-widest uppercase">&nbsp;</th>
                                <th class="px-6 py-3 border-b bg-gray-100 text-left text-xs tracking-widest uppercase">{{ __('common.name') }}</th>
                                <th class="px-6 py-3 border-b bg-gray-100 text-left text-xs tracking-widest uppercase">{{ __('common.description') }}</th>
                                <th class="px-6 py-3 border-b bg-gray-100 text-left text-xs tracking-widest uppercase">{{ __('common.status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 checkable">
                            @forelse ($members->sortBy('name_en') as $entity)
                                <tr class="hover:bg-blue-50" role="button">
                                    <td class="px-6 py-3 text-sm">
                                        <input class="rounded" type="checkbox" name="entities[]" value="{{ $entity->id }}">
                                    </td>
                                    <td class="px-6 py-3 text-sm whitespace-nowrap">
                                        {{ $entity->name_en }}
                                        <div class="text-gray-500">
                                            {{ $entity->entityid }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-sm">
                                        {{ $entity->description_en ?: __('entities.no_description') }}
                                    </td>
                                    <td class="px-6 py-3 text-sm">
                                        <x-pils.approved :model="$entity"/>
                                        <x-pils.status :model="$entity"/>
                                        <x-pils.state :model="$entity"/>        
                                    </td>
                                </tr>
                            @empty
                                <tr class="hover:bg-blue-50">
                                    <td class="px-6 py-3 text-center font-bold" colspan="4">
                                        {{ __('federations.no_members') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $members->links() }}
                    @if (count($members))
                        <div class="px-4 py-2 bg-gray-100">
                            <x-submit color="red" target="true" data-target="delete_members">{{ __('common.delete_members') }}</x-submit>
                            <x-modals.confirm :model="$federation" form="delete_members"/>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <div>
            <h3 class="text-lg font-semibold">
                {{ __('common.add_members') }}
            </h3>
            <div class="mb-4">
                <form>
                    <label class="sr-only" for="search">{{ __('common.search') }}</label>
                    <input class="px-4 py-2 w-full border rounded-lg dark:bg-transparent" type="text" name="search" id="search" value="{{ request('search') }}" placeholder="{{ __('entities.searchbox') }}">
                </form>
            </div>
            <form id="add_members" action="{{ route('federations.update', $federation) }}" method="post">
                @csrf
                @method('patch')
                <input type="hidden" name="action" value="add_entities">
                <div class="bg-white border rounded-lg overflow-x-auto">
                    <table class="min-w-full border-b border-gray-300">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b bg-gray-100 text-left text-xs tracking-widest uppercase">&nbsp;</th>
                                <th class="px-6 py-3 border-b bg-gray-100 text-left text-xs tracking-widest uppercase">{{ __('common.name') }}</th>
                                <th class="px-6 py-3 border-b bg-gray-100 text-left text-xs tracking-widest uppercase">{{ __('common.description') }}</th>
                                <th class="px-6 py-3 border-b bg-gray-100 text-left text-xs tracking-widest uppercase">{{ __('common.status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 checkable">
                            @forelse ($entities as $entity)
                                <tr class="hover:bg-blue-50" role="button">
                                    <td class="px-6 py-3 text-sm">
                                        <input class="rounded" type="checkbox" name="entities[]" value="{{ $entity->id }}">
                                    </td>
                                    <td class="px-6 py-3 text-sm whitespace-nowrap">
                                        {{ $entity->name_en }}
                                        <div class="text-gray-500">
                                            {{ $entity->entityid }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-sm">
                                        {{ $entity->description_en ?: __('entities.no_description') }}
                                    </td>
                                    <td class="px-6 py-3 text-sm">
                                        <x-pils.approved :model="$entity"/>
                                        <x-pils.status :model="$entity"/>
                                        <x-pils.state :model="$entity"/>        
                                    </td>
                                </tr>    
                            @empty
                                <tr class="hover:bg-blue-50">
                                    <td class="px-6 py-3 text-center font-bold">
                                        {{ __('federations.empty') }}
                                    </td>
                                </tr>    
                            @endforelse
                        </tbody>
                    </table>
                    {{ $entities->links() }}
                    @if (count($entities))
                        <div class="px-4 py-2 bg-gray-100">
                            <x-submit target="true" data-target="add_members">{{ __('common.add_members') }}</x-submit>
                            <x-modals.confirm :model="$federation" form="add_members"/>
                        </div>
                    @endif
                </div>
            </form>
        </div>

    @else

        <h3 class="text-lg font-semibold">{{ __('common.entities_list') }}</h3>
        <div class="bg-gray-100 border rounded-lg overflow-x-auto">
            <table class="min-w-full border-b border-gray-300">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-100 text-left text-xs tracking-widest uppercase">{{ __('common.name') }}</th>
                        <th class="px-6 py-3 bg-gray-100 text-left text-xs tracking-widest uppercase">{{ __('common.description') }}</th>
                        <th class="px-6 py-3 bg-gray-100 text-left text-xs tracking-widest uppercase">{{ __('common.status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($members as $entity)
                        <tr class="bg-white hover:bg-blue-50">
                            <td class="px-6 py-3 text-sm">
                                {{ $entity->name_en }}
                            </td>
                            <td class="px-6 py-3 text-sm">
                                {{ $entity->description_en }}
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <x-pils.approved :model="$entity"/>
                                <x-pils.status :model="$entity"/>
                                <x-pils.state :model="$entity"/>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-3 bg-gray-100" colspan="3">
                                {{ __('common.no_entities') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $members->links() }}
        </div>

    @endcan

@endsection