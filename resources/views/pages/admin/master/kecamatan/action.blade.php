<div class="d-flex justify-content-center align-items-center gap-2">
    @if (request()->routeIs('master.kecamatan.histori'))
        @can($globalModule['update'])
            <button data-action="restore" data-url="{{ route('master.kecamatan.restore', $kecamatan->id) }}"
                data-table-id="kecamatan-table" data-name="Kecamatan {{ $kecamatan->nama }}" class="btn btn-success">
                <i class="fas fa-recycle fs-3"></i></button>
        @endcan
    @else
        @can($globalModule['update'])
            <button data-action="edit" data-url="{{ route('master.kecamatan.edit', $kecamatan->id) }}"
                data-modal-id="edit-kecamatan_modal" data-title="Kecamatan" data-target="#edit-kecamatan_modal"
                class="btn btn-warning"><i class="fas fa-pen fs-2"></i></a></button>
        @endcan
        @can($globalModule['delete'])
            <button data-url="{{ route('master.kecamatan.destroy', $kecamatan->id) }}" data-action="delete"
                data-table-id="kecamatan-table" data-name="{{ $kecamatan->nama }}" class="btn btn-danger">
                <i class="fas fa-trash fs-2"></i></button>
        @endcan
    @endif
</div>
