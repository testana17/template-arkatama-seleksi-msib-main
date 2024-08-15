<div class="d-flex justify-content-center align-items-center gap-1">
    @can($globalModule['update'])
        <button data-action="edit" title="edit" data-target="#edit-file_modal"
            data-url="{{ route('cms.file-manager.edit', $file->id) }}" class="btn btn-warning ">
            <i class="fas fa-pen fs-4"></i>
        </button>
    @endcan
    <button type="button" title="delete" class="btn btn-light dropdown-toggle" data-bs-boundary="viewport"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-ellipsis-v me-2    "></i>
    </button>
    <ul class="dropdown-menu">
        @can($globalModule['read'])
            <li>
                <button data-action="preview" data-url="{{ getFileInfo($file->file)['preview'] }}"
                    data-modal-id="preview-modal" data-title="Pratinjau File" class="btn w-100 text-start "><i
                        class="fas fa-eye fs-4 me-2"></i>
                    Preview
                </button>
            </li>
            <li>
                <a href="{{ route('cms.file-manager.download', $file->id) }}" class="btn  w-100  text-start me-2 ">
                    <i class="fas fa-download fs-4 me-2"></i>
                    Download
                </a>
            </li>
        @endcan
        @can($globalModule['delete'])
            <li>
                <button data-url="{{ route('cms.file-manager.destroy', $file->id) }}" data-action="delete"
                    data-table-id="filemanagement-table" data-name="{{ $file->keterangan }}"
                    class="btn  w-100 text-start  ">
                    <i class="fas fa-trash fs-4 me-2"></i>
                    Hapus
                </button>
            </li>
        @endcan
    </ul>

</div>
