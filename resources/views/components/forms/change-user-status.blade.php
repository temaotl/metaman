@can('update', $user)
    @unless(Auth::id() === $user->id)
        <form id="status" class="inline-block" action="{{ route("$route.update", $user) }}" method="post">
            @csrf
            @method('patch')
            <input type="hidden" name="action" value="status">
            <x-buttons.change-status :model="$user" target="status" />
        </form>

        <x-modals.confirm :model="$user" form="status" />
    @endunless
@endcan
