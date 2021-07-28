@component('mail::message')
# Hello!

{{ __("notifications.entity_operators_{$action}_body", [
    'name' => $name,
]) }}

@foreach ($operators as $operator)
- {{ $operator->name }}
@endforeach

Regards,
{{ config('app.name') }}
@endcomponent
