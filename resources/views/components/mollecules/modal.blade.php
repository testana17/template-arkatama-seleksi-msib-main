@props([
    'size' => '',
    'id',
    'action',
    'method' => 'POST',
    'center' => true,
    'custom' => false,
    'hasForm' => true,
    'hasCloseBtn' => true,
    'resetOnClose' => true,
    'iconClose' => '',
    'tableId' => '',
    'closeOnBlur' => false,
])

<div class="modal fade modal-{{ $size }}" id="{{ $id }}" tabindex="-1"
  @if (!$closeOnBlur) data-bs-backdrop="static" @endif
  @if ($resetOnClose) aria-reset-on-close="true" @endif data-bs-keyboard="false" aria-hidden="true"
  @if ($method == 'PUT') update-modal @endif>
  <div class="modal-dialog modal-dialog-scrollable  {{ $center ? 'modal-dialog-centered' : '' }}">
    @if ($hasForm)
      <form
        data-action="{{ $action }}"  action="{{ $action }}"
        id="{{ $id }}-form" method="POST" enctype='multipart/form-data' class="modal-content form-modal"
        data-table-id="{{ $tableId }}" @if ($custom) custom-action @endif>
        @csrf
        @if ($method !== 'POST')
          @method($method)
        @endif
      @else
        <div class="modal-content p-2">
    @endif
    <div class="modal-header">
      <h5 class="modal-title">{{ $title }}</h5>
      @if ($hasCloseBtn)
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close"
          cancel-btn>
          <i class="fal fa-times fs-4"></i>
        </div>
      @endif
      {{ $iconClose }}
    </div>
    <div class="modal-body">
      {{ $slot }}
    </div>
    <div class="modal-footer">
      @if ($hasCloseBtn)
        <button type="button" class="btn btn-light" data-bs-dismiss="modal" cancel-btn>Close</button>
      @endif
      {{ $footer }}
    </div>
    @if ($hasForm)
      </form>
    @else
  </div>
  @endif
</div>

</div>
