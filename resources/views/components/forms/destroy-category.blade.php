@if (count($category->entities) === 0)
    <form id="destroy" class="inline-block" action="{{ route('categories.destroy', $category) }}" method="post">
        @csrf
        @method('delete')
        <x-buttons.destroy target="destroy"/>
    </form>

    <x-modals.confirm :model="$category" form="destroy"/>
@endif