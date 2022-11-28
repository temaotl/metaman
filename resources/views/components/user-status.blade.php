@props(['model' => null])

<span
    {{ $attributes->class([
        'px-2 text-xs font-semibold rounded-full',
        'dark:bg-green-800 dark:text-green-100 text-green-800 bg-green-100' => $model->active,
        'dark:bg-red-800 dark:text-red-100 text-red-800 bg-red-100' => !$model->active,
    ]) }}>
    @if ($model->active)
        {{ __('common.active') }}
    @elseif(!$model->active)
        {{ __('common.inactive') }}
    @endif
</span>

@if ($model->admin)
    <span
        class="dark:bg-indigo-800 dark:text-indigo-100 px-2 ml-2 text-xs font-semibold text-indigo-800 bg-indigo-100 rounded-full">
        {{ __('common.administrator') }}
    </span>
@endif
