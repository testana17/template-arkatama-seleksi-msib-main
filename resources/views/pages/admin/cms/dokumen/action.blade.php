<div class="d-flex justify-content-center align-items-center gap-1">
    @if ($data->trashed())
        @can($globalModule['update'])
            <button data-url="{{ route('cms.document.restore', $data->id) }}" data-action="restore"
                data-table-id="dokumen-table" data-name="{{ $data->nama }}" class="btn btn-success" title="Restore">
                <i class="fas fa-recycle fs-3"></i>
            </button>
        @endcan
    @else
        @can($globalModule['update'])
            <button data-action="edit" title="edit" data-target="#edit-dokumen-modal"
                data-url="{{ route('cms.document.edit', $data->id) }}" data-modal-id="dokumen-modal" data-title="File"
                class="btn btn-warning" title="Ubah">
                <i class="fas fa-pen fs-3"></i>
            </button>
        @endcan
        <button type="button" class="btn btn-light dropdown-toggle" data-bs-boundary="viewport"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-ellipsis-v me-2"></i>
        </button>
        <ul class="dropdown-menu">
            @can($globalModule['read'])
                <li>
                    <button data-action="preview" data-url="{{ getFileInfo($data->file)['preview'] }}"
                        data-modal-id="preview-modal" data-title="Pratinjau Dokumen" class="btn w-100 text-start "><i
                            class="fas fa-eye fs-2 me-2"></i>
                        Pratinjau
                    </button>
                </li>
            @endcan
            @can($globalModule['read'])
                <li>
                    <a href="{{ route('cms.document.download', $data->id) }}" class="btn  w-100  text-start me-2 ">
                        <i class="fas fa-download fs-2 me-2"></i>
                        Unduh
                    </a>
                </li>
            @endcan
            @can($globalModule['delete'])
                <li>
                    <button data-url="{{ route('cms.document.destroy', $data->id) }}" data-action="delete"
                        data-table-id="dokumen-table" data-name="{{ $data->nama }}" class="btn  w-100 text-start  ">
                        <i class="fas fa-trash fs-2 me-2"></i>
                        Hapus
                    </button>
                </li>
            @endcan
        </ul>
    @endif
</div>
