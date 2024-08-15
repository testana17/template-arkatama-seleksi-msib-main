@props(['name' => '', 'class', 'checked' => false])
<div class="{{ $class }}">
    <input type="checkbox" {{ $checked ? 'checked' : '' }} class="form-check-input me-2" name="{{ $name . '[]' }}"
        {{ $attributes }} aria-need-validation="false">
    <label class="form-check-label" for="{{ $attributes->get('id') }}">{{ $slot }}</label>
</div>
