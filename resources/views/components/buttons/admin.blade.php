<button class="open-modal px-4 py-2

    @if ($user->admin)
        bg-yellow-500 hover:bg-yellow-600 text-yellow-50
    @else
        bg-indigo-500 hover:bg-indigo-600 text-indigo-50
    @endif

    rounded shadow" data-target="{{ $target }}" type="submit">
    @if ($user->admin)
        {{ __('common.deadmin') }}
    @else
        {{ __('common.make_admin') }}
    @endif
</button>
