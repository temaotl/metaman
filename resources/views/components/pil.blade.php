@props(['linethrough' => false])

<span
    {{ $attributes->class([
        'px-2 text-xs font-semibold rounded-full',
        'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' => !$linethrough,
        'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' => $linethrough,
        'line-through' => $linethrough,
    ]) }}>
    {{ $slot }}
</span>
