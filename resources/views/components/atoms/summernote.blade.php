@props([
    'id',
    'name',
    'placeholder' => 'Enter text here...',
    'tabsize' => 4,
    'height' => '200px',
])

<textarea name="{{ $name }}" id="{{ $id }}" data-plugin="summernote"></textarea>
<small class="invalid-feedback"></small>

@push('scripts')
<script>
  $(document).ready(function() {
      $('#{{ $id }}').summernote({
        placeholder: '{{ $placeholder }}',
        tabsize: {{ $tabsize }},
        height: '{{ $height }}',
      });
  });
</script>
@endpush

@once
    @push("vendor-css")
        <link href="{{ asset('assets/libs/summernote/dist/summernote-bs5.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush
    @push("vendor-scripts")
        <script src="{{ asset('assets/libs/summernote/dist/summernote-bs5.min.js') }}"></script>
    @endpush
@endonce
