@can('update', $model)

    @unless ($model->trashed())

        @unless ($model->approved)

            @if (in_array(Auth::id(), $model->operators->pluck('id')->toArray()))

                <form class="inline-block" action="{{ route("$route.update", $model) }}" method="post">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="action" value="cancel">
                    <x-buttons.cancel/>
                </form>

            @else

                <form class="inline-block" action="{{ route("$route.update", $model) }}" method="post">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="action" value="reject">
                    <x-buttons.reject/>
                </form>

            @endif

        @endunless

    @endunless

@endcan