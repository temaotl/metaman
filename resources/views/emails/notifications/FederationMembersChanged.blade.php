@component('mail::message')
# Hello!

{{ __("notifications.federation_members_{$action}_body", [
    'name' => $name,
]) }}

@foreach ($entities as $entity)
- {{ $entity->name_en ?? $entity->entityid }}
@endforeach

Regards,
{{ config('app.name') }}
@endcomponent
