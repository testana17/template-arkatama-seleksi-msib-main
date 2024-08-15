@props([
    'lists' => [],
    'value' => null,
    'placeholder' => 'Pilih Opsi',
])

@if (!$attributes->has('ssr'))
    <select {{ $attributes->merge(['class' => 'form-select']) }}>
        <option value="" selected disabled>{{ $placeholder }}</option>
        @if (count($lists) > 0)
            @foreach ($lists as $key => $list)
                <option value="{{ $key }}" @selected($key == $value)>{{ $list }}</option>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>
    <small class="invalid-feedback"></small>
@else
    @error($attributes->get('name'))
        <select {{ $attributes->merge(['class' => 'form-select']) }}>
            <option value="" selected disabled>{{ $placeholder }}</option>
            @if (count($lists) > 0)
                @foreach ($lists as $key => $label)
                    <option value="{{ $key }}" @selected(@old($attributes->get('name')) ?? $value == $key)>{{ $label }}</option>
                @endforeach
            @else
                {{ $slot }}
            @endif
        </select>
        <small class="text-danger">{{ $message }}</small>
    @else
        <select {{ $attributes->merge(['class' => 'form-select is-invalid']) }}>
            <option value="" selected disabled>{{ $placeholder }}</option>
            @if (count($lists) > 0)
                @foreach ($lists as $key => $label)
                    <option value="{{ $key }}" @selected(@old($attributes->get('name')) ?? $value == $key)>{{ $label }}</option>
                @endforeach
            @else
                {{ $slot }}
            @endif
        </select>
    @enderror
@endif
