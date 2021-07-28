<button class="open-modal px-4 py-2

    @if ($entity->hfd)
        bg-red-500 hover:bg-red-600 text-red-50
    @else
        bg-purple-500 hover:bg-purple-600 text-purple-50
    @endif

    rounded shadow" data-target="{{ $target }}" type="submit">
    @if ($entity->hfd)
        {{ __('entities.drop_hfd') }}
    @else
        {{ __('entities.add_hfd') }}
    @endif
</button>