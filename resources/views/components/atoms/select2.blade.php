{{-- available params:
    1. source
    2. parent
    3. multiple (use custom form handling instead)
  --}}

@props([
    'placeholder' => 'Pilih Opsi',
    'lists' => [],
    'allowClear' => true,
    'parent' => null,
    'source' => '',
    'value' => [
        'v' => '',
        't' => '',
    ],
])



@if ($source != '')
    <select name="{{ $attributes->get('name') }}" data-plugin="select-2" data-source="{{ $source }}"
        data-placeholder="{{ $placeholder }}" data-allow-clear="{{ $allowClear }}" data-parent="{{ $parent }}"
        {{ $attributes->merge(['class' => 'form-select ']) }}>

        @if ($value['v'])
            <option selected value="{{ $value['v'] }}">{{ $value['t'] }}</option>
        @else
            <option value="" selected disabled></option>
        @endif
        {{ $slot }}

    </select>
@else
    <select data-plugin="select-2" data-placeholder="{{ $placeholder }}" data-allow-clear="{{ $allowClear }}"
        data-dropwdown-parent="{{ $parent }}" {{ $attributes->merge(['class' => 'form-select ']) }}>
        <option value="" selected disabled></option>
        {{ $slot }}
        @if (count($lists) > 0)
            @foreach ($lists as $key => $label)
                <option value="{{ $key }}" @selected($value['v'] == $key)>{{ $label }}</option>
            @endforeach
        @endif
    </select>
@endif
<small class="invalid-feedback"></small>

@once

    @push('styles')
        <link href="{{ asset('assets/plugins/custom/select2/select2.bundle.css') }}" rel="stylesheet" type="text/css" />
    @endpush
    @push('vendor')
        <script src="{{ asset('assets/plugins/custom/select2/select2.bundle.js') }}"></script>
    @endpush
    @push('scripts')
        <script>
            $(document).on("select2:opening", (e) => {
                $('.modal-body').css('overflow', 'visible');
            });
            $(document).on("select2:open", (e) => {
                $('.modal-body').css('overflow', 'auto');
            });
        </script>
    @endpush
@endonce
