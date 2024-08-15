<div class="d-flex justify-content-center align-items-center gap-2">
    @if (request()->routeIs('master.jenjang-pendidikan.histori'))
        @can($globalModule['update'])
            <button data-action="restore" data-url="{{ route('master.jenjang-pendidikan.restore', $jenjangpendidikan->id) }}"
                data-table-id="jenjang-pendidikan-table" data-name="Jenjang Pendidikan {{ $jenjangpendidikan->nama }}"
                class="btn btn-success">
                <i class="fas fa-recycle fs-3"></i></button>
        @endcan
    @else
        @can($globalModule['update'])
            <button data-action="edit" data-url="{{ route('master.jenjang-pendidikan.edit', $jenjangpendidikan->id) }}"
                data-modal-id="edit-jenjang-pendidikan_modal" data-title="Jenjang Pendidikan"
                data-target="#edit-jenjang-pendidikan_modal" class="btn btn-warning">
                <i class="fas fa-pen fs-2"></i></button>
        @endcan
        @can($globalModule['delete'])
            <button data-url="{{ route('master.jenjang-pendidikan.destroy', $jenjangpendidikan->id) }}" data-action="delete"
                data-table-id="jenjang-pendidikan-table" data-name="{{ $jenjangpendidikan->nama }}" class="btn btn-danger">
                <i class="fas fa-trash fs-2"></i></button>
        @endcan
    @endif
</div>
