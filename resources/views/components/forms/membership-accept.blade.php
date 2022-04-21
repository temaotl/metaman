<form class="inline-block" action="{{ route('memberships.update', $membership) }}" method="post">
    @csrf
    @method('patch')
    <x-buttons.accept />
</form>
