@extends('layout')

@section('content')
<form method="POST" action="@yield('form_action')">
    @csrf
    <div class="dark:bg-transparent overflow-x-auto bg-white border rounded-lg">
        <table class="min-w-full border-b border-gray-300" x-data="selectAllCheckboxes()">
            <thead>
            <tr>
                <th
                    class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                    <input class="rounded" type="checkbox" @click="selectAll = !selectAll">

                </th>
                <th
                    class="dark:bg-gray-700 px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                    {{ __('common.name') }}
                </th>
                @yield('specific_head_fields')

            </tr>
            </thead>


            <tbody class="divide-y divide-gray-200">
                @yield('specific_fields')
            </tbody>
        </table>
    </div>
    <div class="dark:bg-transparent px-4 py-4 bg-gray-100">

        @yield('submit_button')
    </div>
</form>
@endsection

@push('scripts')
    <script defer>
        function selectAllCheckboxes() {
            return {
                selectAll: false,
            };
        }
    </script>
@endpush
