There is a new Identity Provider in eduID.cz:

- entityID: {{ $entity->entityid }}
- Organization: {{ $entity->name_en }}

Go to {{ route('entities.show', $entity) }} for more information.

Thanks,
{{ config('app.name') }}
