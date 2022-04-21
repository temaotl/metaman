<button
    class="open-modal px-4 py-2 rounded shadow

    @if ($model->active) bg-red-500 hover:bg-red-600 text-red-50
    @else bg-green-500 hover:bg-green-600 text-green-50 @endif"
    data-target="{{ $target }}" type="submit">
    @if ($model->active)
        {{ __('common.deactivate') }}
    @else
        {{ __('common.activate') }}
    @endif
</button>
