@if (get_class($model) !== 'App\Models\User')

    @unless($model->trashed())

        @if ($model->approved)
            @if ($model->active)
                <span
                    class="dark:bg-green-800 dark:text-green-100 px-2 text-xs font-semibold text-green-800 bg-green-100 rounded-full">{{ __('common.active') }}</span>
            @else
                <span
                    class="dark:bg-red-800 dark:text-red-100 px-2 text-xs font-semibold text-red-800 bg-red-100 rounded-full">{{ __('common.inactive') }}</span>
            @endif
        @endif

    @endunless
@else
    @if ($model->active)
        <span
            class="dark:bg-green-800 dark:text-green-100 px-2 text-xs font-semibold text-green-800 bg-green-100 rounded-full">{{ __('common.active') }}</span>
    @else
        <span
            class="dark:bg-red-800 dark:text-red-100 px-2 text-xs font-semibold text-red-800 bg-red-100 rounded-full">{{ __('common.inactive') }}</span>
    @endif

@endif
