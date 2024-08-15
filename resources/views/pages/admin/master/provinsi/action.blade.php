<div class="d-flex justify-content-center align-items-center gap-2">
    @if (request()->routeIs('master.provinsi.histori'))
        @can($globalModule['update'])
            <button data-action="restore" data-url="{{ route('master.provinsi.restore', $provinsi->id) }}"
                data-table-id="provinsi-table" data-name="Provinsi {{ $provinsi->nama }}" class="btn btn-success">
                <i class="fas fa-recycle fs-3"></i>
            </button>
        @endcan
    @else
        @can($globalModule['update'])
            <button data-action="edit" data-url="{{ route('master.provinsi.edit', $provinsi->id) }}"
                data-modal-id="edit-provinsi_modal" data-title="Provinsi" data-target="#edit-provinsi_modal"
                class="btn btn-warning">
                <i class="fas fa-pen fs-2"></i>
            </button>
        @endcan
        @can($globalModule['delete'])
            <button data-url="{{ route('master.provinsi.destroy', $provinsi->id) }}" data-action="delete"
                data-table-id="provinsi-table" data-name="{{ $provinsi->nama }}" class="btn btn-danger">
                <i class="fas fa-trash fs-2"></i></button>
        @endcan
    @endif
</div>
