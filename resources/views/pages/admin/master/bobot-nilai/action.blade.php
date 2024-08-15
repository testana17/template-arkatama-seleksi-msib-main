<div class="d-flex justify-content-center align-items-center gap-2">
    @if (request()->routeIs('master.bobot-nilai.histori'))
        @can($globalModule['update'])
            <button data-action="restore" data-url="{{ route('master.bobot-nilai.restore', $bobotnilai->id) }}"
                data-table-id="bobot-nilai-table" data-name="Bobot Nilai {{ $bobotnilai->nilai_huruf }}"
                class="btn btn-success">
                <i class="fas fa-recycle fs-3"></i></button>
        @endcan
    @else
        @can($globalModule['update'])
            <button data-action="edit" data-url="{{ route('master.bobot-nilai.edit', $bobotnilai->id) }}"
                data-modal-id="edit-bobot-nilai_modal" data-title="Bobot Nilai" data-target="#edit-bobot-nilai_modal"
                class="btn btn-warning">
                <i class="fas fa-pen fs-2"></i></a></button>
        @endcan
        @can($globalModule['delete'])
            <button data-url="{{ route('master.bobot-nilai.destroy', $bobotnilai->id) }}" data-action="delete"
                data-table-id="bobot-nilai-table" data-name="Bobot Nilai {{ $bobotnilai->nilai_huruf }}"
                class="btn btn-danger">
                <i class="fas fa-trash fs-2"></i></button>
        @endcan
    @endif
</div>
