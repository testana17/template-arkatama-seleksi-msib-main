<div class="d-flex justify-content-center align-items-center gap-2">
    @if (request()->routeIs('cms.slideshow.histori'))
        @can($globalModule['update'])
            <button data-action="restore" data-url="{{ route('cms.slideshow.restore', $slideshow->id) }}"
                data-table-id="slideshow-table" data-name="Slide Show" class="btn btn-success"><i
                    class="fas fa-recycle fs-3"></i></button>
        @endcan
    @else
        @can($globalModule['update'])
            <button data-action="edit" data-url="{{ route('cms.slideshow.edit', $slideshow->id) }}"
                data-target="#edit-slideshow_modal" class="me-2 btn btn-warning">
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
                    <a href="{{ route('cms.slideshow-item.index', ['slideshow' => $slideshow]) }}"
                        class="btn w-100 text-start">
                        <i class="fas fa-eye fs-2 me-2"></i>Slide Show Item
                    </a>
                </li>
            @endcan
            @can($globalModule['delete'])
                <li>
                    <button data-url="{{ route('cms.slideshow.destroy', $slideshow->id) }}" data-action="delete"
                        data-table-id="slideshow-table" data-name="{{ $slideshow->name }}" class="btn w-100 text-start">
                        <i class="fas fa-trash fs-2 me-2"></i>
                        Hapus
                    </button>
                </li>
            @endcan
        </ul>
    @endif
</div>
