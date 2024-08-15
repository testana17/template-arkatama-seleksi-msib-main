@if (!$attributes->has('ssr'))
    <textarea {{ $attributes->merge(['class' => 'form-control']) }}>{{ $slot }}</textarea>
    <small class="invalid-feedback"></small>
@else
    @error($attributes->get('name'))
        <textarea {{ $attributes->merge(['class' => 'form-control is-invalid']) }}>{{ $slot }}</textarea>
        <small class="text-danger">{{ $message }}</small>
    @else
        <textarea {{ $attributes->merge(['class' => 'form-control']) }}>{{ $slot }}</textarea>
    @enderror
@endif
