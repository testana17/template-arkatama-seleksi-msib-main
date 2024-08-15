<div class="d-flex justify-content-center align-items-center gap-2">
    @if (request()->routeIs('master.kabupaten-kota.histori'))
        @can($globalModule['update'])
            <button data-action="restore" data-url="{{ route('master.kabupaten-kota.restore', $kabupatenkota->id) }}"
                data-table-id="kabupatenkota-table" data-name="Kabupaten {{ $kabupatenkota->nama }}" class="btn btn-success">
                <i class="fas fa-recycle fs-3"></i></button>
        @endcan
    @else
        @can($globalModule['update'])
            <button data-action="edit" data-url="{{ route('master.kabupaten-kota.edit', $kabupatenkota->id) }}"
                data-modal-id="edit-kabupatenkota_modal" data-title="Kabupaten" data-target="#edit-kabupatenkota_modal"
                class="btn btn-warning"><i class="fas fa-pen fs-2"></i></a></button>
        @endcan
        @can($globalModule['delete'])
            <button data-url="{{ route('master.kabupaten-kota.destroy', $kabupatenkota->id) }}" data-action="delete"
                data-table-id="kabupatenkota-table" data-name="{{ $kabupatenkota->nama }}" class="btn btn-danger">
                <i class="fas fa-trash fs-2"></i></button>
        @endcan
    @endif
</div>
