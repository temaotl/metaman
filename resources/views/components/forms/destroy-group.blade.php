@if (count($group->entities) === 0)
    <form id="destroy" class="inline-block" action="{{ route('groups.destroy', $group) }}" method="post">
        @csrf
        @method('delete')
        <x-buttons.destroy target="destroy"/>
    </form>

    <x-modals.confirm :model="$group" form="destroy" />
@endif
