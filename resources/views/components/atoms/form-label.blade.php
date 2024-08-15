<label {{ $attributes->merge(['class' => 'form-label mb-3']) }} style="color: #5A607F">
    {{ $slot }}
    @if ($attributes->has('required'))
        <span class="text-danger">*</span>
    @endif
</label>
