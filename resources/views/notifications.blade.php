@extends('layout')
@section('title', __('common.notifications'))

@section('content')

<div>
    <ul class="pb-4 sm:flex sm:space-x-4 space-y-3 sm:space-y-0 justify-center font-semibold">
        <li><a class="px-3 py-1 @if (Request('show') === 'unread') bg-gray-500 text-gray-50 @else bg-gray-300 hover:bg-gray-200 @endif rounded-full shadow" href="?show=unread">{{ __('notifications.unread_notifications')}}</a></li>
        <li><a class="px-3 py-1 @if (Request('show') === 'read') bg-gray-500 text-gray-50 @else bg-gray-300 hover:bg-gray-200 @endif rounded-full shadow" href="?show=read">{{ __('notifications.read_notifications')}}</a></li>
        <li><a class="px-3 py-1 @if (Request('show') !== 'unread' and Request('show') !== 'read') bg-gray-500 text-gray-50 @else bg-gray-300 hover:bg-gray-200 @endif rounded-full shadow" href="?show=all">{{ __('notifications.all_notifications')}}</a></li>
    </ul>
    <div class="bg-white dark:bg-transparent border rounded-lg overflow-x-auto">
        <table class="min-w-full border-b border-gray-300">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">{{ __('common.notification') }}</th>
                    <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">{{ __('common.created') }}</th>
                    <th class="px-6 py-3 border-b bg-gray-100 dark:bg-gray-700 text-left text-xs tracking-widest uppercase">&nbsp;</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 clickable">
                @forelse ($notifications as $notification)
                    <tr class="hover:bg-blue-100 dark:hover:bg-gray-700
                        @if ($notification->unread())
                            bg-blue-50 font-bold
                        @else
                            bg-white
                        @endif">
                        <td class="px-6 py-3 text-sm">
                            @if (is_int($notification->data['user_id'] ?? null))
                                <a class="text-blue-500 hover:underline" href="{{ route('users.show', $notification->data['user_id']) }}">{{ $notification->data['body'] }}</a>
                            @elseif (is_int($notification->data['federation_id'] ?? null))
                                <a class="text-blue-500 hover:underline" href="{{ route('federations.show', $notification->data['federation_id']) }}">{{ $notification->data['body'] }}</a>
                            @elseif (is_int($notification->data['entity_id'] ?? null))
                                <a class="text-blue-500 hover:underline" href="{{ route('entities.show', $notification->data['entity_id']) }}">{{ $notification->data['body'] }}</a>
                            @elseif (is_int($notification->data['category_id'] ?? null))
                                <a class="text-blue-500 hover:underline" href="{{ route('categories.show', $notification->data['category_id']) }}">{{ $notification->data['body'] }}</a>
                            @elseif (is_int($notification->data['group_id'] ?? null))
                                <a class="text-blue-500 hover:underline" href="{{ route('groups.show', $notification->data['group_id']) }}">{{ $notification->data['body'] }}</a>
                            @else
                                {{ $notification->data['body'] }}
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm">
                            {{ $notification->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-3 text-sm text-right">
                            <form class="inline-block" action="{{ route('notifications.update', $notification->id) }}" method="post">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="page" value="{{ request('page') }}">
                                <button class="text-blue-500 hover:underline @if ($notification->unread()) font-bold @endif" type="submit">
                                    @if ($notification->unread())
                                        {{ __('notifications.mark_as_read' )}}
                                    @else
                                        {{ __('notifications.mark_as_unread' )}}
                                    @endif
                                </button>
                            </form>
                            <span class="mx-1">|</span>
                            <form class="inline-block" action="{{ route('notifications.destroy', $notification->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="page" value="{{ request('page') }}">
                                <button class="text-red-500 hover:underline @if ($notification->unread()) font-bold @endif" type="submit">
                                    {{ __('notifications.delete') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="hover:bg-blue-50 dark:hover-bg-gray-700">
                        <td class="px-6 py-3 text-center font-bold" colspan="4">{{ __('notifications.empty') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $notifications->withQueryString()->links() }}
    </div>
</div>

@endsection