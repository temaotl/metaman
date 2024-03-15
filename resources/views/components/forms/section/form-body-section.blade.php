<div class="odd:bg-gray-50 even:bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 px-4 py-5">
    <dt class="text-sm text-gray-500">
        <label class="text-sm" for="{{ $label ?? 'name'  }}">

            {{ __($name) ?? "Here must be a name" }}
        </label>
    </dt>
    <dd class="sm:col-span-2">
        {!! $errors->first( "$err" , '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}

        {{ $slot  }}
    </dd>
</div>
