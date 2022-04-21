@if (session('status'))
    <div
        class="mb-4 p-4 border rounded shadow

        @if (session('color')) bg-{{ session('color') }}-100 text-{{ session('color') }}-700 border-{{ session('color') }}-200
        @else bg-green-100 text-green-700 border-green-200 @endif">
        {{ session('status') }}
    </div>
@endif
