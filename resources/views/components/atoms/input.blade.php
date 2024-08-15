@if (!$attributes->has('ssr'))
    <input {{ $attributes->merge(['class' => 'form-control ']) }}>
    <small class="invalid-feedback"></small>
@else
    @error($attributes->get('name'))
        <input {{ $attributes->merge(['class' => 'form-control is-invalid']) }}>
        <small class="text-danger">{{ $message }}</small>
    @else
        <input {{ $attributes->merge(['class' => 'form-control']) }}>
    @enderror
@endif
