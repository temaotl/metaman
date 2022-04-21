@extends('layout')
@section('title', __('common.dashboard'))

@section('content')
    <p class="mb-6">
        {!! __('welcome.introduction') !!}
    </p>

    <p>
        {!! __('welcome.contact') !!} <a class="hover:underline text-blue-500" href="mailto:info@eduid.cz">info@eduid.cz</a>.
    </p>
@endsection
