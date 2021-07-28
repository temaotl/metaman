@can('update', $entity)

    @unless ($entity->trashed())

        @if ($entity->active && $entity->approved)

            <form id="edugain" class="inline-block" action="{{ route('entities.update', $entity) }}" method="post">
                @csrf
                @method('patch')
                <input type="hidden" name="action" value="edugain">
                <x-buttons.edugain-membership :entity="$entity" target="edugain"/>
            </form>

            <x-modals.confirm :model="$entity" form="edugain"/>

        @endif

    @endunless
    
@endcan