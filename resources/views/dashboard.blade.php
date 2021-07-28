@extends('layout')
@section('title', __('common.dashboard'))

@section('content')
    <p class="mb-6">
        {!! __('welcome.introduction') !!}
    </p>

    <p>
        {!! __('welcome.contact') !!} <a class="text-blue-500 hover:underline" href="mailto:info@eduid.cz">info@eduid.cz</a>.
    </p>
@endsection