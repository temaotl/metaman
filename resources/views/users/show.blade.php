@extends('layout')
@section('title', __('users.show', ['name' => $user->name]))

@section('content')

    <div class="mb-6 bg-white dark:bg-gray-800 sm:rounded-lg shadow overflow-hidden">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg font-semibold">
                {{ __('users.profile') }}
            </h3>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-500">
            <dl>
                <div class="px-4 py-5 bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm text-gray-500">
                        {{ __('common.full_name') }}
                    </dt>
                    <dd class="sm:col-span-2">
                        <span class="pr-4">{{ $user->name }}</span>
                        <span class="pr-4"><x-pils.status :model="$user" /></span>
                        <x-pils.user-role role="{{ $user->admin }}" />
                    </dd>
                </div>
                <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm text-gray-500">
                        {{ __('common.uniqueid_attribute') }}
                    </dt>
                    <dd class="sm:col-span-2">
                        {{ $user->uniqueid }}
                    </dd>
                </div>
                <div class="px-4 py-5 bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm text-gray-500">
                        {{ __('common.email_address') }}
                    </dt>
                    <dd class="sm:col-span-2">
                        <div>
                            <a class="text-blue-500 hover:underline" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </div>
                        @if (count($emails) > 1)
                        @can('update', $user)
                            <div class="mt-4 p-4 bg-white border rounded-lg shadow">
                                <p class="mb-4 text-gray-500">
                                    {{ __('users.select_different_email') }}
                                </p>
                                <form action="{{ route('users.update', $user) }}" method="post">
                                    @csrf
                                    @method('patch')
                                    <input type="hidden" name="action" value="email">
                                    <select class="rounded mr-4" name="email">
                                        @foreach ($emails as $email)
                                            <option value="{{ $email }}"
                                                @if ($email === $user->email)
                                                    selected
                                                @endif
                                            >{{ $email }}</option>
                                        @endforeach
                                    </select>
                                    <x-button>{{ __('users.update_email') }}</x-button>
                                </form>
                            </div>
                        @endcan
                        @endif
                    </dd>
                </div>
                <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm text-gray-500">
                        {{ __('common.federations') }}
                    </dt>
                    <dd class="sm:col-span-2">
                        @if (count($user->federations))
                            <ul class="list-decimal list-inside">
                                @foreach ($user->federations as $federation)
                                    <li>
                                        <a class="text-blue-500 hover:underline" href="{{ route('federations.show', $federation) }}">{{ $federation->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            {{ __('users.no_federations') }}
                        @endif
                    </dd>
                </div>
                <div class="px-4 py-5 bg-gray-50 dark:bg-gray-900 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm text-gray-500">
                        {{ __('common.entities') }}
                    </dt>
                    <dd class="sm:col-span-2">
                        @if (count($user->entities))
                            <ul class="list-decimal list-inside">
                                @foreach ($user->entities as $entity)
                                    <li>
                                        <a class="text-blue-500 hover:underline" href="{{ route('entities.show', $entity) }}">{{ $entity->name_en ?: $entity->entityid }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            {{ __('users.no_entities') }}
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div>
        <x-buttons.back href="{{ URL::previous() }}" />

        <x-forms.change-user-status route="users" :user="$user"/>
        <x-forms.change-role :user="$user"/>
    </div>

@endsection