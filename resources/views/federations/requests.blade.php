@extends('layout')
@section('title', __('common.requests'))

@section('content')

    @include('federations.navigation')

    @forelse ($joins as $join)
        <div class="mb-4 bg-white sm:rounded-lg shadow overflow-hidden">
            <div>
                <dl>
                    <div class="px-4 py-5 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">{{ __('common.entity') }}</dt>
                        <dd><a class="text-blue-500 hover:underline" href="{{ route('entities.show', $join->entity) }}">{{ $join->entity->name_en }}</a></dd>
                    </div>
                    <div class="px-4 py-5 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">{{ __('common.requester') }}</dt>
                        <dd><a class="text-blue-500 hover:underline" href="{{ route('users.show', $join->requested_by) }}">{{ $join->requester->name }}</a></dd>
                    </div>
                    <div class="px-4 py-5 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-gray-500">{{ __('common.explanation') }}</dt>
                        <dd>{{ $join->explanation }}</dd>
                    </div>
                </dl>
            </div>
            <div class="px-6 py-3 bg-gray-100">
                <x-forms.membership-reject :membership="$join"/>
                <x-forms.membership-accept :membership="$join"/>
            </div>
        </div>
    @empty
        {{ __('common.no_requests') }}        
    @endforelse

@endsection