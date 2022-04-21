@if ($model->trashed())

    <span class="dark:bg-red-800 dark:text-red-100 px-2 text-xs font-semibold text-red-800 bg-red-100 rounded-full">{{ __('common.deleted') }}</span>

@endif