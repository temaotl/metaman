@component('mail::message')
# Exception Occured

An exception has occured in {{ $data['file'] }} on line {{ $data['line'] }} with the following message:

{{ $data['message'] }}

{{ config('app.name') }}
@endcomponent
