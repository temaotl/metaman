@can('do-everything')

    @unless ($model->trashed())

        @unless ($model->approved)

            <form class="inline-block" action="{{ route("$route.update", $model) }}" method="post">
                @csrf
                @method('patch')
                <input type="hidden" name="action" value="approve">
                <x-buttons.approve/>
            </form>

        @endunless

    @endunless

@endcan