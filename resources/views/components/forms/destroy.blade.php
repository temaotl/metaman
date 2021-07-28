@can('do-everything')

    @if ($model->trashed())

        <form id="destroy" class="inline-block" action="{{ route("$route.destroy", $model) }}" method="post">
            @csrf
            @method('delete')
            <x-buttons.destroy target="destroy"/>
        </form>

        <x-modals.confirm :model="$model" form="destroy"/>

    @endif

@endcan