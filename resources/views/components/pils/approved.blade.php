@unless ($model->trashed())

    @unless ($model->approved)

        <span class="px-2 bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 text-xs font-semibold rounded-full">{{ __('common.disapproved') }}</span>

    @endunless

@endunless