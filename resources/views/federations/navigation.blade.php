<div class="mb-4">
    <a class="px-2 py-1 mr-2 bg-blue-100 hover:bg-blue-300 rounded-lg shadow" href="{{ route('federations.show', $federation) }}">{{ __('common.details')}}</a>
    <a class="px-2 py-1 mr-2 bg-blue-100 hover:bg-blue-300 rounded-lg shadow" href="{{ route('federations.operators', $federation) }}">{{ __('common.operators')}}</a>
    <a class="px-2 py-1 mr-2 bg-blue-100 hover:bg-blue-300 rounded-lg shadow" href="{{ route('federations.entities', $federation) }}">{{ __('common.entities')}}</a>
    @can('update', $federation)
        <a class="px-2 py-1 mr-2 bg-blue-100 hover:bg-blue-300 rounded-lg shadow" href="{{ route('federations.requests', $federation) }}">{{ __('common.requests')}}</a>
    @endcan
</div>
