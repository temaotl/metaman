<button class="open-modal px-4 py-2

    @if ($entity->edugain)
        bg-red-500 hover:bg-red-600 text-red-50
    @else
        bg-green-500 hover:bg-green-600 text-green-50
    @endif

    rounded shadow" data-target="{{ $target }}" type="submit">
    @if ($entity->edugain)
        {{ __('entities.drop_from_edugain') }}
    @else
        {{ __('entities.export_to_edugain') }}
    @endif
</button>