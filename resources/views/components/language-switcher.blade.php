<a href="{{$linkUrl }}">
    @empty(trim($slot))
        {{ strtoupper($switchTo) }}
    @else
        {{ $slot }}
    @endempty
</a>
