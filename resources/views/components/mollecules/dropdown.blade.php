@props([
  'trigger' => 'click',
  'class' => '',
  'id' => '',
  'label' => 'Dropdown',
  'offset' => ['0px', '5px'],
])

{{-- <div>
  <button type="button" class="btn-primary" data-kt-menu-trigger="{{ $trigger }}" data-kt-menu-placement="{{ $placement }}" data-kt-menu-offset="{{ $offset[0].', '.$offset[1] }}">
    {{ $button }}
    <span class="svg-icon fs-3 rotate-180 ms-3 me-0">...</span>
  </button>
  <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-100 mw-300px" data-kt-menu="true">
    {{ $body }} 
  </div>
</div> --}}

<div class="btn-group mb-2">
  <button
    class="btn dropdown-toggle {{ $class }} "
    type="button"
    id="{{ $id }}"
    data-bs-toggle="dropdown"
    aria-expanded="false"
  >
    {{ $label }}
  </button>
  <ul
    class="dropdown-menu"
    aria-labelledby="{{ $id }}"
  >
  {{ $slot }}
  </ul>
</div>