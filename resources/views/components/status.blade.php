@props(['model' => null])

<span
    {{ $attributes->class([
        'px-2 text-xs font-semibold rounded-full',
        'dark:bg-yellow-800 dark:text-yellow-100 text-yellow-800 bg-yellow-100' => !$model->approved,
        'dark:bg-red-800 dark:text-red-100 text-red-800 bg-red-100' => $model->trashed(),
        'dark:bg-green-800 dark:text-green-100 text-green-800 bg-green-100' => !$model->trashed(),
    ]) }}>
    @if (!$model->approved)
        {{ __('common.approval_pending') }}
    @elseif($model->trashed())
        {{ __('common.deleted') }}
    @elseif(!$model->trashed())
        {{ __('common.active') }}
    @endif
</span>
