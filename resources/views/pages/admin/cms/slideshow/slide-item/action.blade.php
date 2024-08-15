<div class="d-flex justify-content-center align-items-center gap-2">
    @if (request()->routeIs('cms.slideshow-item.histori'))
        @can($globalModule['update'])
            <button data-action="restore"
                data-url="{{ route('cms.slideshow-item.restore', ['slideshow' => $slideshow, 'item' => $item]) }}"
                data-table-id="slideshowitem-table" data-name="Slide Item" class="btn btn-success"><i
                    class="fas fa-recycle fs-3"></i></button>
        @endcan
    @else
        @can($globalModule['update'])
            <button data-action="edit"
                data-url="{{ route('cms.slideshow-item.edit', ['slideshow' => $slideshow, 'item' => $item]) }}"
                data-target="#edit-slideshowitem_modal" class="btn btn-warning">
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
                    <button data-action="preview" data-url="{{ getFileInfo($item->image)['preview'] }}"
                        data-modal-id="preview-modal" data-title="Pratinjau Gambar" class="btn w-100 text-start "><i
                            class="fas fa-eye fs-2 me-2"></i>
                        Pratinjau
                    </button>
                </li>
                <li>
                    <a href="{{ route('cms.slideshow-item.download', $item->id) }}" class="btn  w-100  text-start me-2 ">
                        <i class="fas fa-download fs-2 me-2"></i>
                        Unduh
                    </a>
                </li>
            @endcan
            @can($globalModule['delete'])
                <li>
                    <button
                        data-url="{{ route('cms.slideshow-item.destroy', ['slideshow' => $slideshow, 'item' => $item]) }}"
                        data-action="delete" data-table-id="slideshowitem-table" data-name="{{ $item->title }}"
                        class="btn w-100 text-start">
                        <i class="fas fa-trash fs-2 me-2"></i>
                        Hapus
                    </button>
                </li>
            @endcan
        </ul>
    @endif
</div>
