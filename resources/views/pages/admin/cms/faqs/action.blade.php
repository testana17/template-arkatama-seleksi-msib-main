<div class="d-flex justify-content-center align-items-center gap-2">
    @if (request()->routeIs('cms.faqs.histori'))
        @can($globalModule['update'])
            <button data-action="restore" data-url="{{ route('cms.faqs.restore', $faq->id) }}" data-table-id="faqs-table"
                data-name="FAQ" class="btn btn-success p-3 btn-center btn-sm"><i class="fal fa-recycle fs-3"></i></button>
        @endcan
    @else
        @can($globalModule['update'])
            <button data-action="edit" data-url="{{ route('cms.faqs.edit', $faq->id) }}" data-target="#edit-faqs_modal"
                class="btn btn-warning">
                <i class="fas fa-pen fs-5"></i>
            </button>
        @endcan
        <button type="button" class="btn btn-light dropdown-toggle" data-bs-boundary="viewport"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-ellipsis-v me-2    "></i>
        </button>
        <ul class="dropdown-menu">
            @can($globalModule['read'])
                <li>
                    <button data-action="edit" data-url="{{ route('cms.faqs.edit', $faq->id) }}"
                        data-target="#preview-faqs-modal" class="btn w-100 text-start "><i class="fas fa-eye fs-2 me-2"></i>
                        Pratinjau
                    </button>
                </li>
            @endcan
            @can($globalModule['delete'])
                <li>
                    <button data-url="{{ route('cms.faqs.destroy', $faq->id) }}" data-action="delete"
                        data-table-id="faqs-table" class="btn w-100 text-start" data-name="{{ $faq->question }}">
                        <i class="fas fa-trash fs-2 me-2"></i>
                        Hapus
                    </button>
                </li>
            @endcan
        </ul>
    @endif
</div>
