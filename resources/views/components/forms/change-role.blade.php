@can('do-everything')
    @unless(Auth::id() === $user->id)
        <form id="role" class="inline-block" action="{{ route('users.show', $user) }}" method="post">
            @csrf
            @method('patch')
            <input type="hidden" name="action" value="role">
            <x-buttons.admin :user="$user" target="role" />
        </form>

        <x-modals.confirm :model="$user" form="role" />
    @endunless
@endcan
