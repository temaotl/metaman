<form x-data="{ open: false }" class="inline-block" action="{{ route('users.update', $user) }}" method="POST">
    @csrf
    @method('patch')

    <input type="hidden" name="action" value="role">

    @if ($user->admin)
        <x-button @click.prevent="open = !open" color="blue">{{ __('common.deadmin') }}</x-button>
    @else
        <x-button @click.prevent="open = !open" color="red">{{ __('common.make_admin') }}</x-button>
    @endif

    <x-modal>
        <x-slot:title>
            @if ($user->admin)
                {{ __('common.revoke_admin_rights') }}
            @else
                {{ __('common.grant_admin_rights') }}
            @endif
        </x-slot:title>
        @if ($user->admin)
            {{ __('common.revoke_admin_rights_body', ['name' => $user->name]) }}
        @else
            {{ __('common.grant_admin_rights_body', ['name' => $user->name]) }}
        @endif
    </x-modal>
</form>
