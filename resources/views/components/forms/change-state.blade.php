@can('update', $model)

    @if ($model->approved)
        @unless($model->active)
            <form id="state" class="inline-block" action="{{ route("$route.update", $model) }}" method="post">
                @csrf
                @method('patch')
                <input type="hidden" name="action" value="state">
                <x-buttons.change-state :model="$model" target="state" />
            </form>

            <x-modals.confirm :model="$model" form="state" />
        @endunless
    @endif

@endcan
