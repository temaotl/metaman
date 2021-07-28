<button class="{{ $target ? 'open-modal' : null }}
            px-4 py-2 bg-{{ $color }}-600 hover:bg-{{ $color }}-700 text-{{ $color }}-50 rounded shadow"
        data-target="{{ $target ?? null }}" type="submit">
    {{ $text }}
</button>