<form class="inline-block" action="{{ route("$route.update", $model) }}" method="post">
    @csrf
    @method('patch')
    <input type="hidden" name="action" value="delete_operator">
    <input type="hidden" name="operator" value="{{ $user }}">
    <x-buttons.delete />
</form>
