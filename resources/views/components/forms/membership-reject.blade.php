<form class="inline-block" action="{{ route('memberships.destroy', $membership) }}" method="post">
    @csrf
    @method('delete')
    <x-buttons.reject/>
</form>
