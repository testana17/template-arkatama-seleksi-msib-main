@props([
    'lists' => [],
    'value' => null,
    'name',
    'id' => '',
])

<div class="radio-group">
    @if (count($lists) > 0)
        @foreach ($lists as $key => $label)
            <x-atoms.radio :$name id="{{ $id != '' ? $id . '_' . $key : $name . '_' . $key }}" selected="{{ $value == $key }}"
                :value="$key">
                {{ $label }}
            </x-atoms.radio>
        @endforeach
    @else
        {{ $slot }}
    @endif
</div>
<small class="invalid-feedback"></small>
