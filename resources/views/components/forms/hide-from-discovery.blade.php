@can('update', $entity)

    @unless ($entity->trashed())

        @if ($entity->active && $entity->approved && $entity->type === 'idp')

            <form id="hfd" class="inline-block" action="{{ route('entities.update', $entity) }}" method="post">
                @csrf
                @method('patch')
                <input type="hidden" name="action" value="hfd">
                <x-buttons.hide-from-discovery :entity="$entity" target="hfd"/>
            </form>

            <x-modals.confirm :model="$entity" form="hfd"/>
        
        @endif

    @endunless

@endcan