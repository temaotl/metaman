@can('update', $model)

    @unless($model->trashed())
        @if ($model->approved)
            <form id="status" class="inline-block" action="{{ route("$route.update", $model) }}" method="post">
                @csrf
                @method('patch')
                <input type="hidden" name="action" value="status">
                <x-buttons.change-status :model="$model" target="status" />
            </form>

            <x-modals.confirm :model="$model" form="status" />
        @endif
    @endunless

@endcan
