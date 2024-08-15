@props([
    'lists' => [],
    'values' => [],
    'id' => '',
    'childClass' => '',
])

<div {{ $attributes->merge(['class' => 'row checkbox-group']) }}>
    @foreach ($lists as $key => $label)
        <x-atoms.checkbox id="{{ $id ? $id . '_' . $key : $attributes->get('name') . '_' . $key }}" :value="$key"
            checked="{{ in_array($key, $values) ? 'true' : '' }}"
            class="{{ $childClass }}" {{$attributes}}>{{ $label }}</x-atoms.checkbox>
    @endforeach
</div>
<small class="invalid-feedback"></small>
