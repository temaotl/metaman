@props(['color' => 'blue', 'target' => false])

<button
    {{ $attributes->class([
        'open-modal' => $target,
        'px-4 py-2 rounded shadow',
        'bg-blue-600 hover:bg-blue-700 text-blue-50' => $color === 'blue',
        'bg-red-600 hover:bg-red-700 text-red-50' => $color === 'red',
    ]) }}
    type="submit"
>
    {{ $slot }}
</button>