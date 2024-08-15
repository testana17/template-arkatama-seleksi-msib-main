@props([
    'selected' => false,
])

<div class="radio">
    <input type="radio" {{ $attributes }} @if ($selected) checked @endif>
    <label for="{{ $attributes->get('id') }}" >
        {{ $slot }}
    </label>
</div>
