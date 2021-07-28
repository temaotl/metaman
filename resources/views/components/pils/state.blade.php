@if ($model->trashed())

    <span class="px-2 bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 text-xs font-semibold rounded-full">{{ __('common.deleted') }}</span>

@endif