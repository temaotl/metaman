<button class="open-modal px-4 py-2

    @if ($model->trashed())
        bg-green-500 hover:bg-green-600 text-green-50
    @else
        bg-red-500 hover:bg-red-600 text-red-50
    @endif

    rounded shadow" data-target="{{ $target }}" type="submit">
    @if ($model->trashed())
        {{ __('common.restore') }}
    @else
        {{ __('common.delete') }}
    @endif
</button>
