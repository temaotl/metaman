@if (get_class($model) !== 'App\Models\User')

    @unless ($model->trashed())

        @if ($model->approved)

            @if ($model->active)

                <span class="px-2 bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 text-xs font-semibold rounded-full">{{ __('common.active') }}</span>

            @else

                <span class="px-2 bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 text-xs font-semibold rounded-full">{{ __('common.inactive') }}</span>

            @endif

        @endif

    @endunless

@else

    @if ($model->active)

    <span class="px-2 bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 text-xs font-semibold rounded-full">{{ __('common.active') }}</span>

    @else

    <span class="px-2 bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 text-xs font-semibold rounded-full">{{ __('common.inactive') }}</span>

    @endif

@endif
