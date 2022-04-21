@unless($model->trashed())

    @unless($model->approved)
        <span
            class="dark:bg-yellow-800 dark:text-yellow-100 px-2 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">{{ __('common.approval_pending') }}</span>
    @endunless

@endunless
