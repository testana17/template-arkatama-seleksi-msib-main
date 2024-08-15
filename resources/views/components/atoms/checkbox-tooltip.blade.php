@props(['name' => '', 'class', 'checked' => false,'value','tooltip' => ''])
<div class="d-flex align-items-center gap-3 {{ $class }}">
    <input class="form-check-input cursor-pointer" type="checkbox" {{ $checked ? 'checked' : '' }}
    name="{{ $name }}" {{$attributes}} aria-need-validation="false" />
    <label class="form-check-label d-flex align-items-center gap-3 text-black" for="{{ $attributes->get('id') }}">
        {{ $slot }}
        <i class=" ti ti-info-circle fs-6 text-info" data-bs-toggle="tooltip" data-bs-html="true"
            data-bs-placement="right"
            title="{{ $tooltip }}"></i>
    </label>
</div>
