<div class="mb-4">
    <a class="px-2 py-1 mr-2 bg-blue-100 hover:bg-blue-300 rounded-lg shadow" href="{{ route('entities.show', $entity)}}">{{ __('common.details') }}</a>
    <a class="px-2 py-1 mr-2 bg-blue-100 hover:bg-blue-300 rounded-lg shadow" href="{{ route('entities.operators', $entity)}}">{{ __('common.operators') }}</a>
    <a class="px-2 py-1 mr-2 bg-blue-100 hover:bg-blue-300 rounded-lg shadow" href="{{ route('entities.federations', $entity)}}">{{ __('common.federations') }}</a>
</div>
